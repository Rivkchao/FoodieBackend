@extends('admin.layout')

@section('title', 'Manajemen Voucher & Promo')
@section('page_title', 'Manajemen Voucher & Kode Promo')
@section('page_subtitle', 'Kelola kode diskon, kuota pemakaian, minimal belanja, dan masa berlaku promo')

@section('content')
<div x-data="{ 
    createModal: false,
    editModal: false,
    editData: {
        id: '',
        code: '',
        name: '',
        discount_type: 'fixed',
        discount_value: '',
        min_spend: 0,
        max_discount: '',
        quota: 100,
        valid_until: '',
        is_active: true
    },
    openEdit(v) {
        this.editData = {
            id: v.id,
            code: v.code,
            name: v.name,
            discount_type: v.discount_type,
            discount_value: v.discount_value,
            min_spend: v.min_spend,
            max_discount: v.max_discount || '',
            quota: v.quota,
            valid_until: v.valid_until ? v.valid_until.substring(0, 10) : '',
            is_active: Boolean(v.is_active)
        };
        this.editModal = true;
    }
}" class="space-y-6">

    <!-- KPI Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-3xl p-5 border border-stone-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl shrink-0">
                🎟️
            </div>
            <div>
                <span class="text-xs font-bold text-stone-400 uppercase tracking-wider">Total Voucher</span>
                <h3 class="text-2xl font-black text-stone-900">{{ $stats['total'] }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-5 border border-stone-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl shrink-0">
                ⚡
            </div>
            <div>
                <span class="text-xs font-bold text-stone-400 uppercase tracking-wider">Voucher Aktif</span>
                <h3 class="text-2xl font-black text-emerald-600">{{ $stats['active'] }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-5 border border-stone-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl shrink-0">
                📈
            </div>
            <div>
                <span class="text-xs font-bold text-stone-400 uppercase tracking-wider">Total Klaim / Dipakai</span>
                <h3 class="text-2xl font-black text-blue-600">{{ $stats['total_used'] }} kali</h3>
            </div>
        </div>
    </div>

    <!-- Action & Search Bar -->
    <div class="bg-white rounded-3xl p-4 border border-stone-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
        <form method="GET" action="{{ route('admin.vouchers.index') }}" class="flex-1 w-full flex items-center gap-2">
            <div class="relative flex-1">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari kode voucher (misal: FOODIEHEMAT, DISKON20)..." 
                       class="w-full pl-9 pr-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-brand-500 focus:bg-white" />
                <span class="absolute left-3 top-3 text-stone-400">🔍</span>
            </div>
            <button type="submit" class="px-4 py-2.5 bg-stone-800 hover:bg-stone-900 text-white rounded-xl text-xs font-bold transition">
                Cari
            </button>
            @if($search)
                <a href="{{ route('admin.vouchers.index') }}" class="px-3 py-2.5 bg-stone-200 hover:bg-stone-300 text-stone-700 rounded-xl text-xs font-bold transition">
                    Reset
                </a>
            @endif
        </form>

        <button type="button" 
                @click="createModal = true"
                class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold shadow-md shadow-brand-500/20 transition flex items-center space-x-2 shrink-0">
            <span>➕</span>
            <span>Tambah Voucher Promo</span>
        </button>
    </div>

    <!-- Vouchers Table -->
    <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-stone-600">
                <thead class="bg-stone-50 border-b border-stone-200 text-stone-700 uppercase font-bold tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Kode & Nama Voucher</th>
                        <th class="py-3.5 px-4">Nilai Diskon</th>
                        <th class="py-3.5 px-4">Min. Belanja</th>
                        <th class="py-3.5 px-4">Penggunaan / Kuota</th>
                        <th class="py-3.5 px-4">Masa Berlaku</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($vouchers as $v)
                        <tr class="hover:bg-stone-50/70 transition">
                            <!-- Kode & Nama -->
                            <td class="py-4 px-4 align-top">
                                <div class="flex items-center space-x-2">
                                    <span class="font-mono font-black text-sm text-stone-900 bg-stone-100 px-2 py-1 rounded-lg border border-stone-200">
                                        {{ $v->code }}
                                    </span>
                                </div>
                                <div class="font-bold text-stone-800 mt-1">{{ $v->name }}</div>
                            </td>

                            <!-- Nilai Diskon -->
                            <td class="py-4 px-4 align-top font-mono">
                                @if($v->discount_type === 'percentage')
                                    <div class="text-sm font-black text-emerald-600">
                                        Diskon {{ $v->discount_value }}%
                                    </div>
                                    @if($v->max_discount)
                                        <div class="text-[11px] text-stone-400">
                                            Maks. Rp {{ number_format($v->max_discount, 0, ',', '.') }}
                                        </div>
                                    @endif
                                @else
                                    <div class="text-sm font-black text-emerald-600">
                                        Potongan Rp {{ number_format($v->discount_value, 0, ',', '.') }}
                                    </div>
                                    <div class="text-[11px] text-stone-400">Potongan Tetap</div>
                                @endif
                            </td>

                            <!-- Min. Belanja -->
                            <td class="py-4 px-4 align-top font-mono font-semibold text-stone-800">
                                @if($v->min_spend > 0)
                                    Rp {{ number_format($v->min_spend, 0, ',', '.') }}
                                @else
                                    <span class="text-stone-400">Tanpa Minimal</span>
                                @endif
                            </td>

                            <!-- Kuota & Digunakan -->
                            <td class="py-4 px-4 align-top">
                                <div class="flex items-center justify-between text-[11px] font-bold text-stone-700 mb-1">
                                    <span>{{ $v->used_count }} / {{ $v->quota }}</span>
                                    <span>{{ round(($v->used_count / max(1, $v->quota)) * 100) }}%</span>
                                </div>
                                <div class="w-28 bg-stone-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-brand-500 h-full rounded-full" style="width: {{ min(100, ($v->used_count / max(1, $v->quota)) * 100) }}%"></div>
                                </div>
                            </td>

                            <!-- Masa Berlaku -->
                            <td class="py-4 px-4 align-top">
                                @if($v->valid_until)
                                    <div class="font-semibold text-stone-800">
                                        {{ $v->valid_until->format('d M Y') }}
                                    </div>
                                    @if(now()->gt($v->valid_until->endOfDay()))
                                        <span class="inline-block px-2 py-0.5 bg-rose-100 text-rose-800 font-bold rounded text-[10px] mt-0.5">
                                            Kadaluarsa
                                        </span>
                                    @else
                                        <span class="inline-block px-2 py-0.5 bg-emerald-100 text-emerald-800 font-bold rounded text-[10px] mt-0.5">
                                            Sisa {{ now()->diffInDays($v->valid_until) }} hari
                                        </span>
                                    @endif
                                @else
                                    <span class="text-stone-400 font-medium">Berlaku Selamanya</span>
                                @endif
                            </td>

                            <!-- Status Aktif / Toggle Form -->
                            <td class="py-4 px-4 align-top text-center">
                                <form method="POST" action="{{ route('admin.vouchers.toggle-active', $v->id) }}">
                                    @csrf
                                    <button type="submit" 
                                            class="px-2.5 py-1 rounded-full text-xs font-bold transition shadow-sm {{ $v->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-stone-200 text-stone-600 hover:bg-stone-300' }}">
                                        {{ $v->is_active ? '✓ Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-4 align-top text-center space-x-1">
                                <button type="button" 
                                        @click="openEdit({{ json_encode($v) }})"
                                        class="p-2 bg-stone-100 hover:bg-stone-200 text-stone-700 rounded-lg text-xs font-bold transition"
                                        title="Edit Voucher">
                                    ✏️
                                </button>

                                <form method="POST" action="{{ route('admin.vouchers.destroy', $v->id) }}" onsubmit="return confirm('Hapus voucher {{ $v->code }}?')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-600 rounded-lg text-xs font-bold transition"
                                            title="Hapus Voucher">
                                        🗑️
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-stone-400">
                                <span class="text-4xl block mb-2">🎟️</span>
                                <span class="font-bold text-sm text-stone-600">Belum ada voucher promo</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vouchers->hasPages())
            <div class="p-4 border-t border-stone-200">
                {{ $vouchers->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL CREATE VOUCHER -->
    <div x-show="createModal" 
         x-transition.opacity 
         class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-sm flex items-center justify-center p-4"
         style="display: none;">
        <div @click.away="createModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-stone-100 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-stone-100">
                <h3 class="font-black text-stone-900 text-lg">Buat Voucher Promo Baru</h3>
                <button @click="createModal = false" class="text-stone-400 hover:text-stone-700 text-2xl leading-none">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.vouchers.store') }}" class="space-y-4 pt-4 text-xs">
                @csrf

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Kode Voucher (Huruf Kapital)</label>
                        <input type="text" name="code" required placeholder="Contoh: FOODIE50" class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500 uppercase font-mono font-bold" />
                    </div>
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Nama Promo</label>
                        <input type="text" name="name" required placeholder="Contoh: Diskon Kilat Weekend" class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Tipe Diskon</label>
                        <select name="discount_type" required class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500 font-semibold">
                            <option value="fixed">Nominal Tetap (Rp)</option>
                            <option value="percentage">Persentase (%)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Nilai Diskon (Rp atau %)</label>
                        <input type="number" name="discount_value" required min="1" placeholder="Misal: 15000 atau 20" class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500 font-mono font-bold" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Min. Belanja (Rp)</label>
                        <input type="number" name="min_spend" value="0" min="0" placeholder="0 jika tanpa batas" class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500 font-mono" />
                    </div>
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Maks. Diskon (Untuk %)</label>
                        <input type="number" name="max_discount" placeholder="Kosongkan jika bebas" class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500 font-mono" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Total Kuota Penggunaan</label>
                        <input type="number" name="quota" value="100" min="1" required class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500 font-mono" />
                    </div>
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Masa Berlaku Sampai</label>
                        <input type="date" name="valid_until" class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500 font-mono" />
                    </div>
                </div>

                <div class="flex items-center space-x-2 pt-1">
                    <input type="checkbox" name="is_active" id="create_active" value="1" checked class="rounded text-brand-600 focus:ring-brand-500" />
                    <label for="create_active" class="font-bold text-stone-700 cursor-pointer">Langsung Aktifkan Voucher Ini</label>
                </div>

                <div class="pt-4 border-t flex justify-end space-x-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2 bg-stone-200 text-stone-700 rounded-xl font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl font-bold shadow-md shadow-brand-500/20">Buat Voucher</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT VOUCHER -->
    <div x-show="editModal" 
         x-transition.opacity 
         class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-sm flex items-center justify-center p-4"
         style="display: none;">
        <div @click.away="editModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-stone-100 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-stone-100">
                <h3 class="font-black text-stone-900 text-lg">Edit Voucher: <span x-text="editData.code"></span></h3>
                <button @click="editModal = false" class="text-stone-400 hover:text-stone-700 text-2xl leading-none">&times;</button>
            </div>

            <form :action="'{{ url('admin/vouchers') }}/' + editData.id" method="POST" class="space-y-4 pt-4 text-xs">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Kode Voucher</label>
                        <input type="text" name="code" x-model="editData.code" required class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500 uppercase font-mono font-bold" />
                    </div>
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Nama Promo</label>
                        <input type="text" name="name" x-model="editData.name" required class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Tipe Diskon</label>
                        <select name="discount_type" x-model="editData.discount_type" required class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500 font-semibold">
                            <option value="fixed">Nominal Tetap (Rp)</option>
                            <option value="percentage">Persentase (%)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Nilai Diskon</label>
                        <input type="number" name="discount_value" x-model="editData.discount_value" required min="1" class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500 font-mono font-bold" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Min. Belanja (Rp)</label>
                        <input type="number" name="min_spend" x-model="editData.min_spend" min="0" class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500 font-mono" />
                    </div>
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Maks. Diskon (Rp)</label>
                        <input type="number" name="max_discount" x-model="editData.max_discount" class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500 font-mono" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Total Kuota</label>
                        <input type="number" name="quota" x-model="editData.quota" min="1" required class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500 font-mono" />
                    </div>
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Masa Berlaku Sampai</label>
                        <input type="date" name="valid_until" x-model="editData.valid_until" class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500 font-mono" />
                    </div>
                </div>

                <div class="flex items-center space-x-2 pt-1">
                    <input type="checkbox" name="is_active" id="edit_active" value="1" x-model="editData.is_active" class="rounded text-brand-600 focus:ring-brand-500" />
                    <label for="edit_active" class="font-bold text-stone-700 cursor-pointer">Status Aktif</label>
                </div>

                <div class="pt-4 border-t flex justify-end space-x-2">
                    <button type="button" @click="editModal = false" class="px-4 py-2 bg-stone-200 text-stone-700 rounded-xl font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl font-bold shadow-md shadow-brand-500/20">Perbarui Voucher</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
