@extends('admin.layout')

@section('title', 'Laporan Penjualan')
@section('page_title', 'Laporan & Analitik Penjualan')
@section('page_subtitle', 'Rekap omset, tren pendapatan, performa menu terlaris, dan ekspor laporan transaksi')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Date Range Filter & Export Bar -->
    <div class="bg-white rounded-3xl p-5 border border-stone-200 shadow-sm flex flex-col lg:flex-row items-center justify-between gap-4">
        <!-- Preset Range Buttons -->
        <div class="flex flex-wrap items-center gap-1.5 w-full lg:w-auto">
            <a href="{{ route('admin.reports.index', ['range' => 'today']) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $range === 'today' ? 'bg-brand-600 text-white shadow-sm' : 'bg-stone-100 hover:bg-stone-200 text-stone-700' }}">
                Hari Ini
            </a>
            <a href="{{ route('admin.reports.index', ['range' => 'last_7_days']) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $range === 'last_7_days' ? 'bg-brand-600 text-white shadow-sm' : 'bg-stone-100 hover:bg-stone-200 text-stone-700' }}">
                7 Hari Terakhir
            </a>
            <a href="{{ route('admin.reports.index', ['range' => 'this_month']) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $range === 'this_month' ? 'bg-brand-600 text-white shadow-sm' : 'bg-stone-100 hover:bg-stone-200 text-stone-700' }}">
                Bulan Ini
            </a>
            <a href="{{ route('admin.reports.index', ['range' => 'this_year']) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $range === 'this_year' ? 'bg-brand-600 text-white shadow-sm' : 'bg-stone-100 hover:bg-stone-200 text-stone-700' }}">
                Tahun Ini
            </a>
            <a href="{{ route('admin.reports.index', ['range' => 'all']) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $range === 'all' ? 'bg-brand-600 text-white shadow-sm' : 'bg-stone-100 hover:bg-stone-200 text-stone-700' }}">
                Semua Periode
            </a>
        </div>

        <!-- Custom Range & Export -->
        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto justify-end">
            <form method="GET" action="{{ route('admin.reports.index') }}" class="flex items-center gap-2 text-xs">
                <input type="hidden" name="range" value="custom">
                <input type="date" name="start_date" value="{{ request('start_date', $start->format('Y-m-d')) }}" class="px-2.5 py-1.5 bg-stone-50 border border-stone-200 rounded-xl font-mono focus:outline-none" />
                <span class="text-stone-400 font-bold">s/d</span>
                <input type="date" name="end_date" value="{{ request('end_date', $end->format('Y-m-d')) }}" class="px-2.5 py-1.5 bg-stone-50 border border-stone-200 rounded-xl font-mono focus:outline-none" />
                <button type="submit" class="px-3 py-1.5 bg-stone-800 hover:bg-stone-900 text-white rounded-xl font-bold transition">
                    Terapkan
                </button>
            </form>

            <!-- Export CSV -->
            <a href="{{ route('admin.reports.export', request()->all()) }}"
               class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-600/20 transition flex items-center space-x-1.5 shrink-0">
                <span>📥</span>
                <span>Ekspor Excel (.xls)</span>
            </a>
        </div>
    </div>

    <!-- Active Range Info Banner -->
    <div class="text-xs text-stone-500 font-semibold px-2">
        📅 Menampilkan data periode: <strong class="text-stone-800">{{ $start->format('d M Y') }}</strong> sampai <strong class="text-stone-800">{{ $end->format('d M Y') }}</strong>
    </div>

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Total Omset Kotor -->
        <div class="bg-white rounded-3xl p-5 border border-stone-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-stone-400 uppercase tracking-wider">Omset Penjualan</span>
                <h3 class="text-xl font-black text-stone-900 font-mono mt-1">
                    Rp {{ number_format($totalGrossSales, 0, ',', '.') }}
                </h3>
                <p class="text-[11px] text-stone-500 mt-0.5">Semua pesanan aktif</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-xl shrink-0">
                💰
            </div>
        </div>

        <!-- 2. Omset Pesanan Selesai -->
        <div class="bg-white rounded-3xl p-5 border border-stone-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-stone-400 uppercase tracking-wider">Penjualan Selesai</span>
                <h3 class="text-xl font-black text-emerald-600 font-mono mt-1">
                    Rp {{ number_format($totalCompletedSales, 0, ',', '.') }}
                </h3>
                <p class="text-[11px] text-emerald-700 mt-0.5">{{ $completedOrders }} transaksi sukses</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                ✅
            </div>
        </div>

        <!-- 3. Total Pesanan -->
        <div class="bg-white rounded-3xl p-5 border border-stone-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-stone-400 uppercase tracking-wider">Total Pesanan</span>
                <h3 class="text-xl font-black text-stone-900 font-mono mt-1">
                    {{ $totalOrders }} <span class="text-xs text-stone-400 font-normal">Order</span>
                </h3>
                <p class="text-[11px] text-stone-500 mt-0.5">Rata-rata: Rp {{ number_format($aov, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                📦
            </div>
        </div>

        <!-- 4. Total Diskon Diberikan -->
        <div class="bg-white rounded-3xl p-5 border border-stone-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-stone-400 uppercase tracking-wider">Diskon Promo Diberikan</span>
                <h3 class="text-xl font-black text-amber-600 font-mono mt-1">
                    Rp {{ number_format($totalDiscounts, 0, ',', '.') }}
                </h3>
                <p class="text-[11px] text-stone-500 mt-0.5">Potongan voucher klaim</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                🎟️
            </div>
        </div>
    </div>

    <!-- Charts & Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Daily Revenue Trend (Line Chart) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-stone-200 shadow-sm space-y-4">
            <h3 class="font-bold text-stone-900 text-sm flex items-center justify-between border-b border-stone-100 pb-3">
                <span>Grafik Tren Penjualan Harian</span>
                <span class="text-xs text-stone-400 font-normal">Omset per tanggal</span>
            </h3>
            <div class="h-64">
                <canvas id="dailyTrendChart"></canvas>
            </div>
        </div>

        <!-- Status & Payment Method Breakdown -->
        <div class="space-y-6">
            <!-- Payment Method Card -->
            <div class="bg-white rounded-3xl p-6 border border-stone-200 shadow-sm space-y-3">
                <h3 class="font-bold text-stone-900 text-sm border-b border-stone-100 pb-2">
                    Metode Pembayaran
                </h3>
                <div class="space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center space-x-2 text-stone-600">
                            <span>💳</span>
                            <span>Midtrans (QRIS / VA / E-Wallet)</span>
                        </span>
                        <span class="font-bold text-stone-900 font-mono">{{ $midtransOrdersCount }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center space-x-2 text-stone-600">
                            <span>💵</span>
                            <span>Tunai di Tempat (COD)</span>
                        </span>
                        <span class="font-bold text-stone-900 font-mono">{{ $cashOrdersCount }}</span>
                    </div>
                </div>
            </div>

            <!-- Order Status Breakdown Card -->
            <div class="bg-white rounded-3xl p-6 border border-stone-200 shadow-sm space-y-3">
                <h3 class="font-bold text-stone-900 text-sm border-b border-stone-100 pb-2">
                    Distribusi Status Pesanan
                </h3>
                <div class="space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-amber-700 font-semibold">⏳ Dipesan</span>
                        <span class="font-bold text-stone-900 font-mono">{{ $statusBreakdown['pending'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-700 font-semibold">👨‍🍳 Diproses</span>
                        <span class="font-bold text-stone-900 font-mono">{{ $statusBreakdown['processing'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-purple-700 font-semibold">🛵 Diantar</span>
                        <span class="font-bold text-stone-900 font-mono">{{ $statusBreakdown['on_delivery'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-emerald-700 font-semibold">✅ Selesai</span>
                        <span class="font-bold text-stone-900 font-mono">{{ $statusBreakdown['completed'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-rose-700 font-semibold">❌ Dibatalkan</span>
                        <span class="font-bold text-stone-900 font-mono">{{ $statusBreakdown['cancelled'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 5 Best Selling Menu Items -->
    <div class="bg-white rounded-3xl p-6 border border-stone-200 shadow-sm space-y-4">
        <h3 class="font-bold text-stone-900 text-sm border-b border-stone-100 pb-3 flex items-center justify-between">
            <span>🔥 5 Menu Terlaris di Periode Ini</span>
            <span class="text-xs text-stone-400 font-normal">Berdasarkan total porsi terjual</span>
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
            @forelse($topSellingItems as $index => $top)
                <div class="p-4 rounded-2xl bg-stone-50 border border-stone-100 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center space-x-1.5 mb-1.5">
                            <span class="w-5 h-5 rounded-full bg-brand-500 text-white font-black text-[10px] flex items-center justify-center">
                                #{{ $index + 1 }}
                            </span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600">Terfavorit</span>
                        </div>
                        <h4 class="font-bold text-stone-900 text-xs line-clamp-1" title="{{ $top->food_name }}">
                            {{ $top->food_name }}
                        </h4>
                    </div>
                    <div class="mt-3 pt-2 border-t border-stone-200/60 font-mono text-xs">
                        <div class="text-stone-500 text-[11px]">{{ $top->total_qty }} porsi terjual</div>
                        <div class="font-bold text-stone-900">Rp {{ number_format($top->total_revenue, 0, ',', '.') }}</div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-6 text-center text-xs text-stone-400">
                    Belum ada menu yang terjual pada periode ini
                </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Transactions Table -->
    <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden space-y-2">
        <div class="p-5 border-b border-stone-100 flex items-center justify-between">
            <h3 class="font-bold text-stone-900 text-sm">
                Detail Transaksi Pada Periode Ini
            </h3>
            <span class="text-xs text-stone-400">{{ $recentTransactions->total() }} transaksi tercatat</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-stone-600">
                <thead class="bg-stone-50 border-b border-stone-200 text-stone-700 uppercase font-bold tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3 px-4">ID Pesanan</th>
                        <th class="py-3 px-4">Tanggal & Waktu</th>
                        <th class="py-3 px-4">Pelanggan</th>
                        <th class="py-3 px-4">Metode Bayar</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Diskon</th>
                        <th class="py-3 px-4 text-right">Total Akhir</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($recentTransactions as $trx)
                        <tr class="hover:bg-stone-50/70 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-stone-900">
                                <a href="{{ route('admin.orders.show', $trx->id) }}" class="text-brand-600 hover:underline">
                                    {{ $trx->order_id }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[11px] text-stone-500">
                                {{ $trx->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-stone-900">{{ $trx->customer_name }}</div>
                                <div class="text-[10px] text-stone-400">{{ $trx->customer_phone }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                {{ $trx->payment_channel ?? ($trx->payment_method === 'cash' ? 'Tunai (COD)' : 'Midtrans') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $trx->status_badge_class }}">
                                    {{ $trx->status_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono text-emerald-600 font-bold">
                                @if($trx->discount_amount > 0)
                                    -Rp {{ number_format($trx->discount_amount, 0, ',', '.') }}
                                @else
                                    <span class="text-stone-300">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-black text-stone-900">
                                Rp {{ number_format($trx->total, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center space-x-2">
                                <a href="{{ route('admin.orders.show', $trx->id) }}" class="text-stone-500 hover:text-stone-900 font-bold">
                                    Detail
                                </a>
                                <a href="{{ route('orders.receipt', $trx->order_id) }}" target="_blank" class="text-brand-600 hover:text-brand-800 font-bold">
                                    Struk
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 text-center text-stone-400">
                                Belum ada transaksi yang tercatat dalam rentang tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($recentTransactions->hasPages())
            <div class="p-4 border-t border-stone-200">
                {{ $recentTransactions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const trendData = @json($dailyTrends);
        
        const labels = trendData.length > 0 
            ? trendData.map(d => d.date) 
            : ['Hari Ini'];
            
        const values = trendData.length > 0 
            ? trendData.map(d => d.revenue) 
            : [0];

        const ctx = document.getElementById('dailyTrendChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Omset Penjualan (Rp)',
                    data: values,
                    borderColor: '#ea580c',
                    backgroundColor: 'rgba(234, 88, 12, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#ea580c',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            },
                            font: { size: 10 }
                        },
                        grid: { color: '#f5f5f4' }
                    },
                    x: {
                        ticks: { font: { size: 10 } },
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection
