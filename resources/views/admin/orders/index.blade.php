@extends('admin.layout')

@section('title', 'Manajemen Pesanan')
@section('page_title', 'Manajemen & Status Pesanan')
@section('page_subtitle', 'Pantau alur pesanan dari Dipesan, Diproses, Diantar, hingga Selesai')

@section('content')
<div class="space-y-6">

    <!-- Status Tabs & Counters -->
    <div class="bg-white rounded-2xl p-2 border border-stone-200 shadow-sm flex flex-wrap gap-1.5">
        <a href="{{ route('admin.orders.index', ['status' => 'all', 'search' => $search]) }}"
           class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 {{ $statusFilter === 'all' ? 'bg-stone-900 text-white shadow-sm' : 'text-stone-600 hover:bg-stone-100' }}">
            <span>📋 Semua Pesanan</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $statusFilter === 'all' ? 'bg-stone-700 text-white' : 'bg-stone-200 text-stone-700' }}">{{ $counts['all'] }}</span>
        </a>

        <!-- 1. Dipesan (Pending) -->
        <a href="{{ route('admin.orders.index', ['status' => 'pending', 'search' => $search]) }}"
           class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 {{ $statusFilter === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'text-stone-600 hover:bg-amber-50 hover:text-amber-700' }}">
            <span>⏳ Dipesan</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $statusFilter === 'pending' ? 'bg-amber-700 text-white' : 'bg-amber-100 text-amber-800' }}">{{ $counts['pending'] }}</span>
        </a>

        <!-- 2. Diproses (Processing) -->
        <a href="{{ route('admin.orders.index', ['status' => 'processing', 'search' => $search]) }}"
           class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 {{ $statusFilter === 'processing' ? 'bg-blue-600 text-white shadow-sm' : 'text-stone-600 hover:bg-blue-50 hover:text-blue-700' }}">
            <span>👨‍🍳 Diproses</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $statusFilter === 'processing' ? 'bg-blue-800 text-white' : 'bg-blue-100 text-blue-800' }}">{{ $counts['processing'] }}</span>
        </a>

        <!-- 3. Diantar (On Delivery) -->
        <a href="{{ route('admin.orders.index', ['status' => 'on_delivery', 'search' => $search]) }}"
           class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 {{ $statusFilter === 'on_delivery' ? 'bg-purple-600 text-white shadow-sm' : 'text-stone-600 hover:bg-purple-50 hover:text-purple-700' }}">
            <span>🛵 Diantar</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $statusFilter === 'on_delivery' ? 'bg-purple-800 text-white' : 'bg-purple-100 text-purple-800' }}">{{ $counts['on_delivery'] }}</span>
        </a>

        <!-- 4. Selesai (Completed) -->
        <a href="{{ route('admin.orders.index', ['status' => 'completed', 'search' => $search]) }}"
           class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 {{ $statusFilter === 'completed' ? 'bg-emerald-600 text-white shadow-sm' : 'text-stone-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
            <span>✅ Selesai</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $statusFilter === 'completed' ? 'bg-emerald-800 text-white' : 'bg-emerald-100 text-emerald-800' }}">{{ $counts['completed'] }}</span>
        </a>

        <!-- 5. Dibatalkan (Cancelled) -->
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled', 'search' => $search]) }}"
           class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 {{ $statusFilter === 'cancelled' ? 'bg-rose-600 text-white shadow-sm' : 'text-stone-600 hover:bg-rose-50 hover:text-rose-700' }}">
            <span>❌ Batal</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $statusFilter === 'cancelled' ? 'bg-rose-800 text-white' : 'bg-rose-100 text-rose-800' }}">{{ $counts['cancelled'] }}</span>
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-2xl p-4 border border-stone-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex-1 w-full flex items-center gap-2">
            <input type="hidden" name="status" value="{{ $statusFilter }}">
            <div class="relative flex-1">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari ID Pesanan (misal FD-849203), Nama Pelanggan, atau No. Telepon..." 
                       class="w-full pl-9 pr-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-brand-500 focus:bg-white transition" />
                <span class="absolute left-3 top-3 text-stone-400">🔍</span>
            </div>
            <button type="submit" class="px-4 py-2.5 bg-stone-800 hover:bg-stone-900 text-white rounded-xl text-xs font-bold transition">
                Cari
            </button>
            @if($search)
                <a href="{{ route('admin.orders.index', ['status' => $statusFilter]) }}" class="px-3 py-2.5 bg-stone-200 hover:bg-stone-300 text-stone-700 rounded-xl text-xs font-bold transition">
                    Reset
                </a>
            @endif
        </form>

        <div class="text-xs text-stone-500 shrink-0">
            Menampilkan <strong>{{ $orders->total() }}</strong> total pesanan
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-stone-600">
                <thead class="bg-stone-50 border-b border-stone-200 text-stone-700 uppercase font-bold tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">ID & Waktu</th>
                        <th class="py-3.5 px-4">Pelanggan</th>
                        <th class="py-3.5 px-4">Menu Dipesan</th>
                        <th class="py-3.5 px-4">Biaya & Diskon</th>
                        <th class="py-3.5 px-4">Pembayaran</th>
                        <th class="py-3.5 px-4 text-center">Status Pesanan</th>
                        <th class="py-3.5 px-4 text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-stone-50/70 transition">
                            <!-- ID & Waktu -->
                            <td class="py-4 px-4 font-mono font-bold text-stone-900 align-top">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-brand-600 hover:underline">
                                    {{ $order->order_id }}
                                </a>
                                <div class="text-[10px] text-stone-400 font-sans mt-0.5">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </div>
                                <span class="inline-flex items-center justify-center mt-1 w-6 h-6 rounded-lg text-xs {{ $order->delivery_type === 'express' ? 'bg-amber-100 text-amber-800' : 'bg-stone-100 text-stone-700' }}" title="{{ $order->delivery_type === 'express' ? 'Prioritas' : 'Reguler' }}">
                                    {{ $order->delivery_type === 'express' ? '⚡' : '🚲' }}
                                </span>
                            </td>

                            <!-- Pelanggan -->
                            <td class="py-4 px-4 align-top">
                                <div class="font-bold text-stone-900">{{ $order->customer_name }}</div>
                                <div class="text-[11px] text-stone-500">{{ $order->customer_phone }}</div>
                                <div class="text-[10px] text-stone-400 line-clamp-1 max-w-xs mt-0.5" title="{{ $order->customer_address }}">
                                    📍 {{ $order->customer_address }}
                                </div>
                            </td>

                            <!-- Menu Dipesan -->
                            <td class="py-4 px-4 align-top">
                                <ul class="space-y-1 max-w-xs">
                                    @foreach($order->items as $item)
                                        <li class="flex items-center justify-between text-[11px]">
                                            <span class="font-semibold text-stone-800 truncate mr-2">{{ $item->food_name }}</span>
                                            <span class="text-stone-500 font-mono shrink-0">{{ $item->qty }}x</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>

                            <!-- Biaya & Voucher -->
                            <td class="py-4 px-4 align-top font-mono">
                                <div class="text-[11px] text-stone-500">Subtotal: Rp {{ number_format($order->subtotal, 0, ',', '.') }}</div>
                                @if($order->discount_amount > 0)
                                    <div class="text-[11px] text-emerald-600 font-bold">
                                        Voucher: -Rp {{ number_format($order->discount_amount, 0, ',', '.') }}
                                        <span class="text-[9px] bg-emerald-100 text-emerald-800 px-1 rounded">{{ $order->voucher_code }}</span>
                                    </div>
                                @endif
                                <div class="text-xs font-black text-brand-600 mt-1">
                                    Total: Rp {{ number_format($order->total, 0, ',', '.') }}
                                </div>
                            </td>

                            <!-- Pembayaran -->
                            <td class="py-4 px-4 align-top">
                                @if(in_array(strtolower($order->payment_method), ['cash', 'cod', 'tunai']))
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                        <span>💵</span>
                                        <span>Tunai</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-800 border border-blue-200" title="{{ $order->payment_channel ?? 'Transfer Digital' }}">
                                        <span>💳</span>
                                        <span>Transfer</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Status Badge -->
                            <td class="py-4 px-4 align-top text-center">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold border {{ $order->status_badge_class }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>

                            <!-- Aksi Alur Kerja (Kompak) -->
                            <td class="py-3.5 px-2 align-top text-center space-y-1.5 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1">
                                    <!-- Shortcut Workflow Status -->
                                    @if(in_array($order->status, ['pending', 'dipesan']))
                                        <form method="POST" action="{{ route('admin.orders.status', $order->id) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="processing">
                                            <button type="submit" 
                                                    title="Mulai masak hidangan (Diproses)"
                                                    class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[11px] font-bold shadow-sm transition flex items-center space-x-1">
                                                <span>👨‍🍳</span>
                                                <span>Proses</span>
                                            </button>
                                        </form>
                                    @elseif(in_array($order->status, ['processing', 'diproses']))
                                        <form method="POST" action="{{ route('admin.orders.status', $order->id) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="on_delivery">
                                            <button type="submit" 
                                                    title="Kirim ke kurir pengantaran (Diantar)"
                                                    class="px-2 py-1 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-[11px] font-bold shadow-sm transition flex items-center space-x-1">
                                                <span>🛵</span>
                                                <span>Antar</span>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Tombol Selesai Pesanan -->
                                    @if(!in_array($order->status, ['completed', 'selesai', 'cancelled', 'batal']))
                                        <form method="POST" action="{{ route('admin.orders.status', $order->id) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" 
                                                    title="Selesaikan pesanan"
                                                    class="px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[11px] font-bold shadow-sm transition flex items-center space-x-1">
                                                <span>✅</span>
                                                <span>Selesai</span>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Dropdown Ganti Status Lengkap -->
                                    <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="inline-block">
                                        @csrf
                                        <select name="status" 
                                                onchange="this.form.submit()" 
                                                class="px-1.5 py-1 bg-stone-100 hover:bg-stone-200 border border-stone-300 rounded-lg text-[11px] font-semibold text-stone-700 focus:outline-none focus:border-brand-500 cursor-pointer">
                                            <option disabled selected>Ubah...</option>
                                            <option value="pending" {{ in_array($order->status, ['pending', 'dipesan']) ? 'disabled' : '' }}>⏳ Dipesan</option>
                                            <option value="processing" {{ in_array($order->status, ['processing', 'diproses']) ? 'disabled' : '' }}>👨‍🍳 Diproses</option>
                                            <option value="on_delivery" {{ in_array($order->status, ['on_delivery', 'diantar']) ? 'disabled' : '' }}>🛵 Diantar</option>
                                            <option value="completed" {{ in_array($order->status, ['completed', 'selesai']) ? 'disabled' : '' }}>✅ Selesai</option>
                                            <option value="cancelled" {{ in_array($order->status, ['cancelled', 'batal']) ? 'disabled' : '' }}>❌ Dibatalkan</option>
                                        </select>
                                    </form>
                                </div>

                                <div class="flex items-center justify-center gap-2 pt-1 border-t border-stone-100 text-[10px]">
                                    <!-- Detail Link -->
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-stone-500 hover:text-stone-900 font-bold">
                                        Detail
                                    </a>
                                    <span class="text-stone-300">•</span>
                                    <!-- Print Receipt -->
                                    <a href="{{ route('orders.receipt', $order->order_id) }}" target="_blank" class="text-brand-600 hover:text-brand-800 font-bold">
                                        Struk 🖨️
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-stone-400">
                                <span class="text-4xl block mb-2">🍽️</span>
                                <span class="font-bold text-sm text-stone-600">Tidak ada pesanan pada kategori ini</span>
                                <p class="text-xs text-stone-400 mt-1">Pesanan yang dibuat oleh pelanggan dari web checkout akan muncul di sini secara real-time.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-stone-200">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
