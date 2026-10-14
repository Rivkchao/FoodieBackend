<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Voucher;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Get list of orders
     */
    public function index(): JsonResponse
    {
        $orders = Order::with('items')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pesanan berhasil diambil',
            'data' => $orders,
        ]);
    }

    /**
     * Show single order detail
     */
    public function show(string $orderId): JsonResponse
    {
        $order = Order::with('items')->where('order_id', $orderId)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pesanan berhasil diambil',
            'data' => $order,
        ]);
    }

    /**
     * Store new checkout order with Cash or Midtrans payment and optional Voucher
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:150',
            'customer_phone' => 'required|string|max:30',
            'customer_address' => 'required|string',
            'notes' => 'nullable|string',
            'delivery_type' => 'required|in:standard,express',
            'payment_method' => 'required|string|max:50', // 'cash' (COD) or 'midtrans' / 'gopay' / 'qris'
            'voucher_code' => 'nullable|string|max:50',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($validated) {
            // Generate Unique Order ID (e.g. FD-849203)
            $orderId = 'FD-' . mt_rand(100000, 999999);
            while (Order::where('order_id', $orderId)->exists()) {
                $orderId = 'FD-' . mt_rand(100000, 999999);
            }

            // Calculate subtotal
            $subtotal = 0;
            $midtransItemDetails = [];
            foreach ($validated['items'] as $item) {
                $itemSubtotal = $item['price'] * $item['quantity'];
                $subtotal += $itemSubtotal;

                $midtransItemDetails[] = [
                    'id' => (string) $item['id'],
                    'price' => (int) $item['price'],
                    'quantity' => (int) $item['quantity'],
                    'name' => mb_substr($item['name'], 0, 50),
                ];
            }

            // Delivery Fee logic
            $deliveryFeeBase = ($subtotal >= 100000 || $subtotal === 0) ? 0 : 12000;
            $deliveryFee = $validated['delivery_type'] === 'express' ? ($deliveryFeeBase + 8000) : $deliveryFeeBase;
            $platformFee = $subtotal > 0 ? 2000 : 0;

            // Voucher calculation (server validated)
            $voucherCode = null;
            $discountAmount = 0;
            if (!empty($validated['voucher_code'])) {
                $code = strtoupper(trim($validated['voucher_code']));
                $voucher = Voucher::where('code', $code)->first();
                if ($voucher) {
                    $check = $voucher->checkValidity($subtotal);
                    if ($check['valid']) {
                        $voucherCode = $voucher->code;
                        $discountAmount = $voucher->calculateDiscount($subtotal);
                        $voucher->increment('used_count');
                    }
                }
            }

            $total = max(0, $subtotal - $discountAmount + $deliveryFee + $platformFee);

            if ($deliveryFee > 0) {
                $midtransItemDetails[] = [
                    'id' => 'DELIVERY-FEE',
                    'price' => (int) $deliveryFee,
                    'quantity' => 1,
                    'name' => 'Biaya Pengiriman (' . ucfirst($validated['delivery_type']) . ')',
                ];
            }

            if ($platformFee > 0) {
                $midtransItemDetails[] = [
                    'id' => 'PLATFORM-FEE',
                    'price' => (int) $platformFee,
                    'quantity' => 1,
                    'name' => 'Biaya Layanan Aplikasi',
                ];
            }

            if ($discountAmount > 0) {
                $midtransItemDetails[] = [
                    'id' => 'VOUCHER-DISCOUNT',
                    'price' => -(int) $discountAmount,
                    'quantity' => 1,
                    'name' => 'Diskon Voucher (' . $voucherCode . ')',
                ];
            }

            $isCash = in_array(strtolower($validated['payment_method']), ['cash', 'cod', 'tunai']);

            $order = Order::create([
                'order_id' => $orderId,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_address' => $validated['customer_address'],
                'notes' => $validated['notes'] ?? null,
                'delivery_type' => $validated['delivery_type'],
                'payment_method' => $isCash ? 'cash' : 'midtrans',
                'payment_channel' => $isCash ? 'Tunai (COD)' : null,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'platform_fee' => $platformFee,
                'voucher_code' => $voucherCode,
                'discount_amount' => $discountAmount,
                'total' => $total,
                'status' => 'pending', // 'pending' == Dipesan
            ]);

            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'food_id' => is_numeric($item['id']) ? $item['id'] : null,
                    'food_name' => $item['name'],
                    'price' => $item['price'],
                    'qty' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            // If payment is Midtrans, generate Snap Token
            $snapToken = null;
            if (!$isCash) {
                try {
                    $snapParams = [
                        'transaction_details' => [
                            'order_id' => $order->order_id,
                            'gross_amount' => (int) $total,
                        ],
                        'customer_details' => [
                            'first_name' => $order->customer_name,
                            'phone' => $order->customer_phone,
                            'billing_address' => [
                                'first_name' => $order->customer_name,
                                'address' => $order->customer_address,
                            ],
                            'shipping_address' => [
                                'first_name' => $order->customer_name,
                                'address' => $order->customer_address,
                            ],
                        ],
                        'item_details' => $midtransItemDetails,
                    ];

                    $snapToken = $this->midtransService->createSnapToken($snapParams);
                    $order->update(['snap_token' => $snapToken]);
                } catch (\Exception $e) {
                    Log::error('Midtrans Snap Error: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => $isCash ? 'Pesanan COD berhasil dibuat!' : 'Menyiapkan gateway pembayaran Midtrans...',
                'data' => [
                    'order' => $order->load('items'),
                    'order_id' => $order->order_id,
                    'total' => $order->total,
                    'subtotal' => $order->subtotal,
                    'discount_amount' => $order->discount_amount,
                    'voucher_code' => $order->voucher_code,
                    'payment_method' => $order->payment_method,
                    'snap_token' => $snapToken,
                    'client_key' => config('midtrans.client_key'),
                    'is_production' => config('midtrans.is_production'),
                ],
            ], 201);
        });
    }

    /**
     * Complete payment callback from Midtrans Frontend
     */
    public function completeMidtrans(Request $request, string $orderId): JsonResponse
    {
        $order = Order::where('order_id', $orderId)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan',
            ], 404);
        }

        $trxStatus = $request->input('transaction_status', 'settlement');
        $paymentType = $request->input('payment_type', 'midtrans');

        // Map status
        $status = 'pending';
        if (in_array($trxStatus, ['capture', 'settlement', 'success'])) {
            $status = 'processing'; // Ready to cook & deliver
            $order->paid_at = now();
        } elseif (in_array($trxStatus, ['cancel', 'deny', 'expire'])) {
            $status = 'cancelled';
        }

        $order->status = $status;
        $order->payment_channel = strtoupper(str_replace('_', ' ', $paymentType));
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran berhasil diperbarui!',
            'data' => $order,
        ]);
    }

    /**
     * Mark Cash / COD Order as Paid by Admin / Courier
     */
    public function markAsPaid(string $orderId): JsonResponse
    {
        $order = Order::where('order_id', $orderId)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan',
            ], 404);
        }

        $order->status = 'processing';
        $order->paid_at = now();
        if (empty($order->payment_channel)) {
            $order->payment_channel = 'Tunai (COD Lunas)';
        }
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diubah menjadi Lunas!',
            'data' => $order,
        ]);
    }

    /**
     * HTML Receipt with Fit-to-content Print/Download style
     */
    public function receipt(string $orderId)
    {
        $order = Order::with('items')->where('order_id', $orderId)->firstOrFail();

        return view('receipt', compact('order'));
    }
}
