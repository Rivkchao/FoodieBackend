@extends('admin.layout')

@section('title', 'Manajemen Menu')
@section('page_title', 'Manajemen Menu Makanan & Minuman')
@section('page_subtitle', 'Kelola daftar katalog hidangan, harga, kategori, dan foto menu')

@section('content')
<div x-data="{ 
    createModal: false,
    editModal: false,
    editData: {
        id: '',
        name: '',
        category: '',
        price: '',
        description: '',
        image_url: '',
        prepTime: '',
        calories: '',
        ingredients: '',
        popular: false
    },
    openEdit(food) {
        this.editData = {
            id: food.id,
            name: food.name,
            category: food.category,
            price: food.price,
            description: food.description,
            image_url: food.image,
            prepTime: food.prepTime || '15-20 min',
            calories: food.calories || '500 kkal',
            ingredients: Array.isArray(food.ingredients) ? food.ingredients.join(', ') : '',
            popular: Boolean(food.popular)
        };
        this.editModal = true;
    }
}" class="space-y-6">

    <!-- Top Action Bar -->
    <div class="bg-white rounded-3xl p-5 border border-stone-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Search & Category Filters -->
        <form method="GET" action="{{ route('admin.menu.index') }}" class="flex flex-wrap items-center gap-2 flex-1 w-full">
            <div class="relative flex-1 min-w-[200px]">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari nama menu atau deskripsi..." 
                       class="w-full pl-9 pr-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs focus:outline-none focus:border-brand-500 focus:bg-white" />
                <span class="absolute left-3 top-2.5 text-stone-400">🔍</span>
            </div>

            <select name="category" onchange="this.form.submit()" class="px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs font-semibold text-stone-700 focus:outline-none">
                <option value="all">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ $categoryFilter === $cat->slug ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-stone-800 hover:bg-stone-900 text-white rounded-xl text-xs font-bold transition">
                Filter
            </button>

            @if($search || $categoryFilter !== 'all')
                <a href="{{ route('admin.menu.index') }}" class="px-3 py-2 bg-stone-200 hover:bg-stone-300 text-stone-700 rounded-xl text-xs font-bold transition">
                    Reset
                </a>
            @endif
        </form>

        <!-- Tambah Menu Button -->
        <button type="button" 
                @click="createModal = true"
                class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold shadow-md shadow-brand-500/20 transition flex items-center space-x-2 shrink-0">
            <span>➕</span>
            <span>Tambah Menu Baru</span>
        </button>
    </div>

    <!-- Menu Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse($foods as $food)
            <div class="bg-white rounded-3xl border border-stone-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                <!-- Image Header -->
                <div class="relative h-44 w-full bg-stone-100 overflow-hidden">
                    <img src="{{ $food->image }}" 
                         alt="{{ $food->name }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                         onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&auto=format&fit=crop&q=80'" />
                    
                    <!-- Category Badge -->
                    <span class="absolute top-3 left-3 px-2.5 py-1 bg-stone-900/80 backdrop-blur-sm text-white text-[10px] font-bold rounded-lg uppercase tracking-wider">
                        {{ $food->category }}
                    </span>

                    <!-- Popular Badge & Toggle Form -->
                    <form method="POST" action="{{ route('admin.menu.toggle-popular', $food->id) }}" class="absolute top-3 right-3">
                        @csrf
                        <button type="submit" 
                                title="Klik untuk ubah status populer"
                                class="px-2 py-1 rounded-lg text-[10px] font-bold shadow-sm transition flex items-center space-x-1 {{ $food->popular ? 'bg-amber-500 text-white' : 'bg-white/80 text-stone-600 hover:bg-white' }}">
                            <span>⭐</span>
                            <span>{{ $food->popular ? 'Populer' : 'Biasa' }}</span>
                        </button>
                    </form>
                </div>

                <!-- Body Info -->
                <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                    <div>
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-bold text-stone-900 text-sm line-clamp-1" title="{{ $food->name }}">
                                {{ $food->name }}
                            </h3>
                        </div>
                        <p class="text-xs text-stone-500 line-clamp-2 mt-1 leading-relaxed">
                            {{ $food->description }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between text-xs text-stone-400 pt-2 border-t border-stone-100 font-mono">
                        <span>⏱️ {{ $food->prepTime ?? '15-20 min' }}</span>
                        <span>🔥 {{ $food->calories ?? '500 kkal' }}</span>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <span class="text-base font-black text-brand-600 font-mono">
                            Rp {{ number_format($food->price, 0, ',', '.') }}
                        </span>

                        <div class="flex items-center space-x-1.5">
                            <!-- Edit Button -->
                            <button type="button" 
                                    @click="openEdit({{ json_encode($food) }})"
                                    class="p-2 bg-stone-100 hover:bg-stone-200 text-stone-700 rounded-lg text-xs font-bold transition"
                                    title="Edit Menu">
                                ✏️
                            </button>

                            <!-- Delete Form -->
                            <form method="POST" action="{{ route('admin.menu.destroy', $food->id) }}" onsubmit="return confirm('Hapus menu {{ $food->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-2 bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-600 rounded-lg text-xs font-bold transition"
                                        title="Hapus Menu">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-stone-400 bg-white rounded-3xl border border-stone-200">
                <span class="text-4xl block mb-2">🍔</span>
                <span class="font-bold text-sm text-stone-600">Belum ada hidangan menu</span>
            </div>
        @endforelse
    </div>

    @if($foods->hasPages())
        <div class="p-4 bg-white rounded-2xl border border-stone-200 shadow-sm">
            {{ $foods->links() }}
        </div>
    @endif

    <!-- MODAL CREATE MENU -->
    <div x-show="createModal" 
         x-transition.opacity 
         class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-sm flex items-center justify-center p-4"
         style="display: none;">
        <div @click.away="createModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-stone-100 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-stone-100">
                <h3 class="font-black text-stone-900 text-lg">Tambah Hidangan Baru</h3>
                <button @click="createModal = false" class="text-stone-400 hover:text-stone-700 text-2xl leading-none">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.menu.store') }}" enctype="multipart/form-data" class="space-y-4 pt-4 text-xs">
                @csrf

                <div>
                    <label class="block font-bold text-stone-700 mb-1">Nama Menu</label>
                    <input type="text" name="name" required placeholder="Contoh: Double Cheeseburger Deluxe" class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500" />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Kategori</label>
                        <select name="category" required class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Harga (Rp)</label>
                        <input type="number" name="price" required min="0" placeholder="55000" class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500 font-mono" />
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-stone-700 mb-1">URL Gambar (Unsplash / Web Image)</label>
                    <input type="url" name="image_url" placeholder="https://images.unsplash.com/..." class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500" />
                </div>

                <div>
                    <label class="block font-bold text-stone-700 mb-1">Atau Upload Gambar Dari Komputer</label>
                    <input type="file" name="image_file" accept="image/*" class="w-full text-stone-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100" />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Waktu Masak</label>
                        <input type="text" name="prepTime" value="15-20 min" class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500" />
                    </div>
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Kalori</label>
                        <input type="text" name="calories" value="550 kkal" class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500" />
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-stone-700 mb-1">Bahan Utama (Pisahkan dengan koma)</label>
                    <input type="text" name="ingredients" placeholder="Beef Patty, Brioche Bun, Cheddar, Truffle Mayo" class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500" />
                </div>

                <div>
                    <label class="block font-bold text-stone-700 mb-1">Deskripsi Hidangan</label>
                    <textarea name="description" rows="3" required placeholder="Jelaskan cita rasa hidangan..." class="w-full p-3 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500"></textarea>
                </div>

                <div class="flex items-center space-x-2 pt-1">
                    <input type="checkbox" name="popular" id="create_popular" value="1" class="rounded text-brand-600 focus:ring-brand-500" />
                    <label for="create_popular" class="font-bold text-stone-700 cursor-pointer">Tandai sebagai Menu Populer (Rekomendasi Utama)</label>
                </div>

                <div class="pt-4 border-t flex justify-end space-x-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2 bg-stone-200 text-stone-700 rounded-xl font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl font-bold shadow-md shadow-brand-500/20">Simpan Menu</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT MENU -->
    <div x-show="editModal" 
         x-transition.opacity 
         class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-sm flex items-center justify-center p-4"
         style="display: none;">
        <div @click.away="editModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-stone-100 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-stone-100">
                <h3 class="font-black text-stone-900 text-lg">Edit Menu: <span x-text="editData.name"></span></h3>
                <button @click="editModal = false" class="text-stone-400 hover:text-stone-700 text-2xl leading-none">&times;</button>
            </div>

            <form :action="'{{ url('admin/menu') }}/' + editData.id" method="POST" enctype="multipart/form-data" class="space-y-4 pt-4 text-xs">
                @csrf
                @method('PUT')

                <div>
                    <label class="block font-bold text-stone-700 mb-1">Nama Menu</label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500" />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Kategori</label>
                        <select name="category" x-model="editData.category" required class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Harga (Rp)</label>
                        <input type="number" name="price" x-model="editData.price" required min="0" class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500 font-mono" />
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-stone-700 mb-1">URL Gambar</label>
                    <input type="url" name="image_url" x-model="editData.image_url" class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500" />
                </div>

                <div>
                    <label class="block font-bold text-stone-700 mb-1">Ganti File Gambar (Opsional)</label>
                    <input type="file" name="image_file" accept="image/*" class="w-full text-stone-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100" />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Waktu Masak</label>
                        <input type="text" name="prepTime" x-model="editData.prepTime" class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500" />
                    </div>
                    <div>
                        <label class="block font-bold text-stone-700 mb-1">Kalori</label>
                        <input type="text" name="calories" x-model="editData.calories" class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500" />
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-stone-700 mb-1">Bahan Utama (Pisahkan koma)</label>
                    <input type="text" name="ingredients" x-model="editData.ingredients" class="w-full px-3 py-2 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500" />
                </div>

                <div>
                    <label class="block font-bold text-stone-700 mb-1">Deskripsi Hidangan</label>
                    <textarea name="description" rows="3" x-model="editData.description" required class="w-full p-3 bg-stone-50 border rounded-xl focus:outline-none focus:border-brand-500"></textarea>
                </div>

                <div class="flex items-center space-x-2 pt-1">
                    <input type="checkbox" name="popular" id="edit_popular" value="1" x-model="editData.popular" class="rounded text-brand-600 focus:ring-brand-500" />
                    <label for="edit_popular" class="font-bold text-stone-700 cursor-pointer">Tandai sebagai Menu Populer</label>
                </div>

                <div class="pt-4 border-t flex justify-end space-x-2">
                    <button type="button" @click="editModal = false" class="px-4 py-2 bg-stone-200 text-stone-700 rounded-xl font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl font-bold shadow-md shadow-brand-500/20">Perbarui Menu</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
