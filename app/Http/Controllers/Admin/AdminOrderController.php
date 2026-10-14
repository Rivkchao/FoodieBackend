<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminOrderController extends Controller
{
    /**
     * List Orders with Filter & Status Counters
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $search = $request->query('search');

        $query = Order::with('items')->latest();

        if ($statusFilter !== 'all') {
            if ($statusFilter === 'pending') {
                $query->whereIn('status', ['pending', 'dipesan']);
            } elseif ($statusFilter === 'processing') {
                $query->whereIn('status', ['processing', 'diproses']);
            } elseif ($statusFilter === 'on_delivery') {
                $query->whereIn('status', ['on_delivery', 'diantar']);
            } elseif ($statusFilter === 'completed') {
                $query->whereIn('status', ['completed', 'selesai']);
            } elseif ($statusFilter === 'cancelled') {
                $query->whereIn('status', ['cancelled', 'batal']);
            } else {
                $query->where('status', $statusFilter);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_phone', 'LIKE', "%{$search}%")
                  ->orWhere('customer_address', 'LIKE', "%{$search}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        // Status Counts for Tabs
        $counts = [
            'all' => Order::count(),
            'pending' => Order::whereIn('status', ['pending', 'dipesan'])->count(),
            'processing' => Order::whereIn('status', ['processing', 'diproses'])->count(),
            'on_delivery' => Order::whereIn('status', ['on_delivery', 'diantar'])->count(),
            'completed' => Order::whereIn('status', ['completed', 'selesai'])->count(),
            'cancelled' => Order::whereIn('status', ['cancelled', 'batal'])->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusFilter', 'search', 'counts'));
    }

    /**
     * Show single order detail
     */
    public function show(int $id): View
    {
        $order = Order::with('items')->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order workflow status (Dipesan -> Diproses -> Diantar -> Selesai / Dibatalkan)
     */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,processing,on_delivery,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $newStatus = $request->input('status');

        $order->status = $newStatus;

        // Auto mark as paid if completed
        if ($newStatus === 'completed' && !$order->paid_at) {
            $order->paid_at = now();
            if (empty($order->payment_channel)) {
                $order->payment_channel = 'Tunai (COD Lunas)';
            }
        }

        $order->save();

        $statusNames = [
            'pending' => 'Dipesan',
            'processing' => 'Diproses',
            'on_delivery' => 'Diantar',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return back()->with('success', "Status pesanan #{$order->order_id} berhasil diubah menjadi: {$statusNames[$newStatus]}!");
    }

    /**
     * Mark Order as Paid (COD Pelunasan)
     */
    public function markPaid(int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);
        $order->paid_at = now();
        if (empty($order->payment_channel)) {
            $order->payment_channel = 'Tunai (COD Lunas)';
        }
        if ($order->status === 'pending') {
            $order->status = 'processing';
        }
        $order->save();

        return back()->with('success', "Pesanan #{$order->order_id} berhasil ditandai Lunas!");
    }

    /**
     * Delete order
     */
    public function destroy(int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);
        $order->items()->delete();
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', "Pesanan #{$order->order_id} berhasil dihapus.");
    }
}
