<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminReportController extends Controller
{
    /**
     * Sales & Order Reporting Dashboard
     */
    public function index(Request $request): View
    {
        $range = $request->query('range', 'this_month');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $now = Carbon::now();

        // Calculate date boundaries
        if ($range === 'today') {
            $start = $now->copy()->startOfDay();
            $end = $now->copy()->endOfDay();
        } elseif ($range === 'last_7_days') {
            $start = $now->copy()->subDays(6)->startOfDay();
            $end = $now->copy()->endOfDay();
        } elseif ($range === 'this_year') {
            $start = $now->copy()->startOfYear();
            $end = $now->copy()->endOfYear();
        } elseif ($range === 'all') {
            $start = Carbon::parse('2020-01-01');
            $end = $now->copy()->endOfDay();
        } elseif ($range === 'custom' && $startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
        } else {
            // this_month default
            $start = $now->copy()->startOfMonth();
            $end = $now->copy()->endOfMonth();
            $range = 'this_month';
        }

        // Base query for the period
        $periodOrders = Order::whereBetween('created_at', [$start, $end]);

        // Key Metrics
        $totalOrders = (clone $periodOrders)->count();
        $completedOrders = (clone $periodOrders)->whereIn('status', ['completed', 'selesai'])->count();
        $totalGrossSales = (clone $periodOrders)->whereNotIn('status', ['cancelled', 'batal'])->sum('total');
        $totalCompletedSales = (clone $periodOrders)->whereIn('status', ['completed', 'selesai'])->sum('total');
        $totalDiscounts = (clone $periodOrders)->whereNotIn('status', ['cancelled', 'batal'])->sum('discount_amount');
        $totalDeliveryFees = (clone $periodOrders)->whereNotIn('status', ['cancelled', 'batal'])->sum('delivery_fee');
        $aov = $totalOrders > 0 ? ($totalGrossSales / $totalOrders) : 0;

        // Payment Method breakdown
        $cashOrdersCount = (clone $periodOrders)->where('payment_method', 'cash')->count();
        $midtransOrdersCount = (clone $periodOrders)->where('payment_method', 'midtrans')->count();

        // Status breakdown
        $statusBreakdown = [
            'pending' => (clone $periodOrders)->whereIn('status', ['pending', 'dipesan'])->count(),
            'processing' => (clone $periodOrders)->whereIn('status', ['processing', 'diproses'])->count(),
            'on_delivery' => (clone $periodOrders)->whereIn('status', ['on_delivery', 'diantar'])->count(),
            'completed' => (clone $periodOrders)->whereIn('status', ['completed', 'selesai'])->count(),
            'cancelled' => (clone $periodOrders)->whereIn('status', ['cancelled', 'batal'])->count(),
        ];

        // Top 5 Best Selling Items
        $topSellingItems = OrderItem::select('food_name', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->whereHas('order', function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end])
                  ->whereNotIn('status', ['cancelled', 'batal']);
            })
            ->groupBy('food_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Daily trend for the selected range (max 30 days)
        $dailyTrends = (clone $periodOrders)
            ->whereNotIn('status', ['cancelled', 'batal'])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Recent transaction list
        $recentTransactions = (clone $periodOrders)
            ->with('items')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.index', compact(
            'range',
            'start',
            'end',
            'totalOrders',
            'completedOrders',
            'totalGrossSales',
            'totalCompletedSales',
            'totalDiscounts',
            'totalDeliveryFees',
            'aov',
            'cashOrdersCount',
            'midtransOrdersCount',
            'statusBreakdown',
            'topSellingItems',
            'dailyTrends',
            'recentTransactions'
        ));
    }

    /**
     * Export Transactions to Professional Excel (.xls) or CSV
     */
    public function export(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $range = $request->query('range', 'this_month');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $format = $request->query('format', 'excel'); // 'excel' or 'csv'

        $now = Carbon::now();

        if ($range === 'today') {
            $start = $now->copy()->startOfDay();
            $end = $now->copy()->endOfDay();
        } elseif ($range === 'last_7_days') {
            $start = $now->copy()->subDays(6)->startOfDay();
            $end = $now->copy()->endOfDay();
        } elseif ($range === 'this_year') {
            $start = $now->copy()->startOfYear();
            $end = $now->copy()->endOfYear();
        } elseif ($range === 'all') {
            $start = Carbon::parse('2020-01-01');
            $end = $now->copy()->endOfDay();
        } elseif ($range === 'custom' && $startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
        } else {
            $start = $now->copy()->startOfMonth();
            $end = $now->copy()->endOfMonth();
        }

        $periodOrders = Order::whereBetween('created_at', [$start, $end]);
        $orders = (clone $periodOrders)->with('items')->latest()->get();

        if ($format === 'csv') {
            $fileName = 'Laporan-Penjualan-Foodie-' . $start->format('Ymd') . '-sd-' . $end->format('Ymd') . '.csv';

            $headers = [
                'Content-type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            $callback = function () use ($orders) {
                $file = fopen('php://output', 'w');
                fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM

                fputcsv($file, [
                    'No',
                    'ID Pesanan',
                    'Tanggal & Waktu',
                    'Nama Pelanggan',
                    'Nomor Telepon',
                    'Alamat Pengiriman',
                    'Layanan Antar',
                    'Metode Bayar',
                    'Kanal Bayar',
                    'Status Pesanan',
                    'Subtotal (Rp)',
                    'Ongkos Kirim (Rp)',
                    'Biaya Layanan (Rp)',
                    'Kode Voucher',
                    'Diskon Voucher (Rp)',
                    'Total Akhir (Rp)',
                    'Rincian Menu',
                ]);

                foreach ($orders as $idx => $order) {
                    $itemList = $order->items->map(function ($it) {
                        return "{$it->food_name} ({$it->qty}x)";
                    })->implode(', ');

                    fputcsv($file, [
                        $idx + 1,
                        $order->order_id,
                        $order->created_at->format('d/m/Y H:i:s'),
                        $order->customer_name,
                        $order->customer_phone,
                        $order->customer_address,
                        $order->delivery_type === 'express' ? 'Prioritas Kilat' : 'Reguler',
                        in_array(strtolower($order->payment_method), ['cash', 'cod', 'tunai']) ? 'Tunai' : 'Transfer',
                        $order->payment_channel ?? '-',
                        $order->status_label,
                        $order->subtotal,
                        $order->delivery_fee,
                        $order->platform_fee,
                        $order->voucher_code ?? '-',
                        $order->discount_amount,
                        $order->total,
                        $itemList,
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // Default: Professional Excel (.xls)
        $summary = [
            'total_orders' => (clone $periodOrders)->count(),
            'completed_orders' => (clone $periodOrders)->whereIn('status', ['completed', 'selesai'])->count(),
            'total_gross_sales' => (clone $periodOrders)->whereNotIn('status', ['cancelled', 'batal'])->sum('total'),
            'total_completed_sales' => (clone $periodOrders)->whereIn('status', ['completed', 'selesai'])->sum('total'),
            'total_discounts' => (clone $periodOrders)->whereNotIn('status', ['cancelled', 'batal'])->sum('discount_amount'),
        ];

        $fileName = 'Laporan-Penjualan-Foodie-' . $start->format('Ymd') . '-sd-' . $end->format('Ymd') . '.xls';
        $content = view('admin.reports.excel', compact('orders', 'start', 'end', 'summary'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ]);
    }
}
