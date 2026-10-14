@extends('admin.layout')

@section('title', 'Detail Pesanan #' . $order->order_id)
@section('page_title', 'Detail Pesanan #' . $order->order_id)
@section('page_subtitle', 'Informasi lengkap pemesanan, item hidangan, dan status pengantaran')

@section('content')
<div class="max-w-5xl space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-stone-600 hover:text-stone-900 flex items-center space-x-1.5">
            <span>←</span>
            <span>Kembali ke Daftar Pesanan</span>
        </a>

        <div class="flex items-center space-x-2">
            <a href="{{ route('orders.receipt', $order->order_id) }}" target="_blank"
               class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center space-x-1.5">
                <span>🖨️</span>
                <span>Cetak Struk Thermal</span>
            </a>

            <form method="POST" action="{{ route('admin.orders.destroy', $order->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-2 bg-stone-200 hover:bg-rose-600 hover:text-white text-stone-700 rounded-xl text-xs font-bold transition">
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <!-- Status & Quick Workflow Action Banner -->
    <div class="bg-white rounded-3xl p-6 border border-stone-200 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-stone-100">
            <div>
                <span class="text-xs font-bold text-stone-400 uppercase tracking-wider">Status Pesanan Saat Ini</span>
                <div class="mt-1 flex flex-wrap items-center gap-2">
                    <span class="px-3.5 py-1.5 rounded-full text-sm font-black border {{ $order->status_badge_class }}">
                        {{ $order->status_label }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold {{ $order->delivery_type === 'express' ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-stone-100 text-stone-700 border border-stone-200' }}">
                        <span>{{ $order->delivery_type === 'express' ? '⚡ Prioritas Kilat' : '🚲 Pengiriman Reguler' }}</span>
                    </span>
                    <span class="text-xs text-stone-500 ml-1">
                        Dibuat pada: <strong>{{ $order->created_at->format('d M Y, H:i') }} WIB</strong>
                    </span>
                </div>
            </div>

            <!-- Workflow Quick Actions -->
            <div class="flex flex-wrap items-center gap-2">
                @if(in_array($order->status, ['pending', 'dipesan']))
                    <form method="POST" action="{{ route('admin.orders.status', $order->id) }}">
                        @csrf
                        <input type="hidden" name="status" value="processing">
                        <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center space-x-1.5">
                            <span>👨‍🍳</span>
                            <span>Terima & Mulai Masak (Diproses)</span>
                        </button>
                    </form>
                @endif

                @if(in_array($order->status, ['processing', 'diproses']))
                    <form method="POST" action="{{ route('admin.orders.status', $order->id) }}">
                        @csrf
                        <input type="hidden" name="status" value="on_delivery">
                        <button type="submit" class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center space-x-1.5">
                            <span>🛵</span>
                            <span>Kirim ke Pelanggan (Diantar)</span>
                        </button>
                    </form>
                @endif

                @if(in_array($order->status, ['on_delivery', 'diantar']))
                    <form method="POST" action="{{ route('admin.orders.status', $order->id) }}">
                        @csrf
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center space-x-1.5">
                            <span>✅</span>
                            <span>Tandai Selesai</span>
                        </button>
                    </form>
                @elseif(!in_array($order->status, ['completed', 'selesai', 'cancelled', 'batal']))
                    <form method="POST" action="{{ route('admin.orders.status', $order->id) }}">
                        @csrf
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="px-3.5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center space-x-1.5">
                            <span>✅</span>
                            <span>Selesaikan Langsung</span>
                        </button>
                    </form>
                @endif

                <!-- Ubah Status Manual Dropdown -->
                <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="flex items-center space-x-2">
                    @csrf
                    <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-stone-100 hover:bg-stone-200 border border-stone-300 rounded-xl text-xs font-bold text-stone-700 focus:outline-none cursor-pointer">
                        <option disabled selected>Ganti Status...</option>
                        <option value="pending">⏳ Dipesan</option>
                        <option value="processing">👨‍🍳 Diproses</option>
                        <option value="on_delivery">🛵 Diantar</option>
                        <option value="completed">✅ Selesai</option>
                        <option value="cancelled">❌ Dibatalkan</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Workflow Visual Progress Bar -->
        @php
            $step = match(strtolower($order->status)) {
                'pending', 'dipesan' => 1,
                'processing', 'diproses' => 2,
                'on_delivery', 'diantar' => 3,
                'completed', 'selesai' => 4,
                default => 0,
            };
        @endphp
        @if($step > 0)
            <div class="pt-2">
                <div class="grid grid-cols-4 gap-2 text-center text-[11px] font-bold">
                    <div class="{{ $step >= 1 ? 'text-amber-600' : 'text-stone-400' }}">1. Dipesan ⏳</div>
                    <div class="{{ $step >= 2 ? 'text-blue-600' : 'text-stone-400' }}">2. Diproses 👨‍🍳</div>
                    <div class="{{ $step >= 3 ? 'text-purple-600' : 'text-stone-400' }}">3. Diantar 🛵</div>
                    <div class="{{ $step >= 4 ? 'text-emerald-600' : 'text-stone-400' }}">4. Selesai ✅</div>
                </div>
                <div class="w-full bg-stone-100 h-2.5 rounded-full overflow-hidden mt-2">
                    <div class="h-full bg-gradient-to-r from-amber-400 via-blue-500 to-emerald-500 rounded-full transition-all duration-500" 
                         style="width: {{ ($step / 4) * 100 }}%"></div>
                </div>
            </div>
        @else
            <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700 font-bold flex items-center space-x-2">
                <span>❌</span>
                <span>Pesanan ini berstatus Dibatalkan.</span>
            </div>
        @endif
    </div>

    <!-- Main Order Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Left 2 Cols: Order Items & Pricing Breakdown -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl p-6 border border-stone-200 shadow-sm space-y-4">
                <h3 class="font-bold text-stone-900 text-sm border-b border-stone-100 pb-3 flex items-center justify-between">
                    <span>Menu Makanan & Minuman</span>
                    <span class="text-xs text-stone-400 font-normal">{{ $order->items->count() }} item</span>
                </h3>

                <div class="divide-y divide-stone-100">
                    @foreach($order->items as $item)
                        <div class="py-3 flex items-center justify-between text-xs">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-sm shrink-0">
                                    🍽️
                                </div>
                                <div>
                                    <h4 class="font-bold text-stone-900 text-sm">{{ $item->food_name }}</h4>
                                    <p class="text-stone-500 font-mono">{{ $item->qty }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <span class="font-black text-stone-900 font-mono text-sm">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <!-- Pricing Summary -->
                <div class="border-t border-stone-200 pt-4 space-y-2 text-xs font-mono">
                    <div class="flex justify-between text-stone-600">
                        <span>Subtotal Menu</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-stone-600">
                        <span class="flex items-center gap-1.5">
                            Ongkos Kirim
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold {{ $order->delivery_type === 'express' ? 'bg-amber-100 text-amber-800' : 'bg-stone-100 text-stone-700' }}">
                                {{ $order->delivery_type === 'express' ? '⚡ Prioritas Kilat' : '🚲 Reguler Foodie' }}
                            </span>
                        </span>
                        <span>{{ $order->delivery_fee == 0 ? 'GRATIS' : 'Rp ' . number_format($order->delivery_fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-stone-600">
                        <span>Biaya Layanan Aplikasi</span>
                        <span>Rp {{ number_format($order->platform_fee, 0, ',', '.') }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-emerald-600 font-bold bg-emerald-50 p-2 rounded-lg">
                            <span>Diskon Voucher ({{ $order->voucher_code }})</span>
                            <span>-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-base font-black text-brand-600 border-t border-stone-200 pt-3">
                        <span class="font-sans font-bold text-stone-900">Total Akhir Pembayaran</span>
                        <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right 1 Col: Customer Details & Payment -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-3xl p-6 border border-stone-200 shadow-sm space-y-4">
                <h3 class="font-bold text-stone-900 text-sm border-b border-stone-100 pb-3">
                    Informasi Pelanggan
                </h3>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-stone-400 block font-semibold">Nama Penerima:</span>
                        <span class="font-bold text-stone-900 text-sm">{{ $order->customer_name }}</span>
                    </div>

                    <div>
                        <span class="text-stone-400 block font-semibold">Nomor WhatsApp / Telepon:</span>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}" target="_blank" class="font-bold text-brand-600 hover:underline">
                            📞 {{ $order->customer_phone }} (Hubungi WhatsApp)
                        </a>
                    </div>

                    <div>
                        <span class="text-stone-400 block font-semibold">Layanan Pengiriman:</span>
                        <div class="mt-1">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold {{ $order->delivery_type === 'express' ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-stone-100 text-stone-700 border border-stone-200' }}">
                                <span>{{ $order->delivery_type === 'express' ? '⚡ Prioritas Kilat (Estimasi 25 Menit)' : '🚲 Reguler Foodie (Estimasi 35-45 Menit)' }}</span>
                            </span>
                        </div>
                    </div>

                    <div>
                        <span class="text-stone-400 block font-semibold">Alamat Pengiriman:</span>
                        <p class="font-medium text-stone-800 bg-stone-50 p-2.5 rounded-xl border border-stone-100 mt-1">
                            📍 {{ $order->customer_address }}
                        </p>
                    </div>

                    @if($order->notes)
                        <div>
                            <span class="text-stone-400 block font-semibold">Catatan Pelanggan:</span>
                            <p class="font-medium text-amber-800 bg-amber-50 p-2.5 rounded-xl border border-amber-100 mt-1 italic">
                                💬 "{{ $order->notes }}"
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-white rounded-3xl p-6 border border-stone-200 shadow-sm space-y-4">
                <h3 class="font-bold text-stone-900 text-sm border-b border-stone-100 pb-3">
                    Informasi Pembayaran
                </h3>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-stone-500">Metode Bayar:</span>
                        @if(in_array(strtolower($order->payment_method), ['cash', 'cod', 'tunai']))
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                <span>💵</span>
                                <span>Tunai COD</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-800 border border-blue-200" title="{{ $order->payment_channel ?? 'Transfer Digital' }}">
                                <span>💳</span>
                                <span>Transfer</span>
                            </span>
                        @endif
                    </div>

                    @if($order->payment_channel && !in_array(strtolower($order->payment_method), ['cash', 'cod', 'tunai']))
                        <div class="flex justify-between items-center">
                            <span class="text-stone-500">Kanal Bayar:</span>
                            <span class="font-bold text-stone-900">
                                {{ $order->payment_channel }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
