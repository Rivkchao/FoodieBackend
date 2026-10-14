<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Foodie Restaurant & Delivery</title>
    <!-- Favicon (Same as Frontend 🍔) -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍔</text></svg>">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @yield('styles')
</head>
<body class="bg-stone-100 text-stone-800 antialiased min-h-screen flex flex-col md:flex-row">

    <!-- Mobile Sidebar Toggle Overlay -->
    <div x-data="{ sidebarOpen: false }" class="flex-1 flex flex-col md:flex-row min-h-screen">

        <!-- Mobile Top Navbar -->
        <header class="md:hidden bg-stone-900 text-white px-4 py-3 flex items-center justify-between shadow sticky top-0 z-30">
            <div class="flex items-center space-x-2.5">
                <span class="text-2xl">🍔</span>
                <span class="font-black text-lg text-brand-500">FOODIE <span class="text-xs font-semibold text-stone-400">ADMIN</span></span>
            </div>
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg bg-stone-800 text-stone-300 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </header>

        <!-- Sidebar Navigation -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
               class="fixed md:static inset-y-0 left-0 z-40 w-64 bg-stone-900 text-stone-300 flex flex-col justify-between transition-transform duration-200 ease-in-out md:translate-x-0 shrink-0 shadow-xl">
            
            <div>
                <!-- Brand Logo -->
                <div class="p-6 border-b border-stone-800 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-500 flex items-center justify-center text-white text-xl shadow-md">
                            🍔
                        </div>
                        <div>
                            <h1 class="text-lg font-black text-white tracking-wide">FOODIE</h1>
                            <p class="text-[10px] text-stone-400 uppercase tracking-widest font-semibold">Admin Portal</p>
                        </div>
                    </div>
                    <button @click="sidebarOpen = false" class="md:hidden text-stone-400 hover:text-white">&times;</button>
                </div>

                <!-- Navigation Links -->
                @php
                    $pendingOrdersCount = \App\Models\Order::whereIn('status', ['pending', 'dipesan'])->count();
                @endphp
                <nav class="p-4 space-y-1.5 text-sm font-semibold">
                    <div class="px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-stone-500">
                        Manajemen Restoran
                    </div>

                    <!-- 1. Pesanan (Orders) -->
                    <a href="{{ route('admin.orders.index') }}" 
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.orders.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/30' : 'hover:bg-stone-800 hover:text-white' }}">
                        <div class="flex items-center space-x-3">
                            <span class="text-base">📦</span>
                            <span>Pesanan Masuk</span>
                        </div>
                        @if($pendingOrdersCount > 0)
                            <span class="px-2 py-0.5 text-xs font-black rounded-full {{ request()->routeIs('admin.orders.*') ? 'bg-white text-brand-600' : 'bg-brand-500 text-white animate-pulse' }}">
                                {{ $pendingOrdersCount }}
                            </span>
                        @endif
                    </a>

                    <!-- 2. Manajemen Menu -->
                    <a href="{{ route('admin.menu.index') }}" 
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.menu.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/30' : 'hover:bg-stone-800 hover:text-white' }}">
                        <span class="text-base">🍔</span>
                        <span>Manajemen Menu</span>
                    </a>

                    <!-- 3. Voucher & Promo -->
                    <a href="{{ route('admin.vouchers.index') }}" 
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.vouchers.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/30' : 'hover:bg-stone-800 hover:text-white' }}">
                        <span class="text-base">🎟️</span>
                        <span>Voucher & Promo</span>
                    </a>

                    <!-- 4. Laporan Penjualan (Reporting) -->
                    <a href="{{ route('admin.reports.index') }}" 
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.reports.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/30' : 'hover:bg-stone-800 hover:text-white' }}">
                        <span class="text-base">📊</span>
                        <span>Laporan Penjualan</span>
                    </a>

                    <div class="pt-4 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-stone-500">
                        Akses Cepat
                    </div>

                    <!-- Link to Customer Frontend -->
                    <a href="http://localhost:5173" target="_blank"
                       class="flex items-center space-x-3 px-3.5 py-2 rounded-xl text-xs text-stone-400 hover:bg-stone-800 hover:text-white transition">
                        <span>🌐</span>
                        <span>Buka Web Pelanggan</span>
                    </a>
                </nav>
            </div>

            <!-- User Info & Logout Button -->
            <div class="p-4 border-t border-stone-800">
                <div class="flex items-center justify-between bg-stone-800/80 p-3 rounded-xl mb-3">
                    <div class="flex items-center space-x-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-full bg-brand-500/20 text-brand-400 font-bold flex items-center justify-center text-xs shrink-0">
                            👨‍🍳
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Admin Foodie' }}</p>
                            <p class="text-[10px] text-stone-400 truncate">{{ Auth::user()->email ?? 'admin@foodie.test' }}</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full py-2 px-3 bg-stone-800 hover:bg-rose-600/90 text-stone-300 hover:text-white rounded-lg text-xs font-bold transition flex items-center justify-center space-x-2">
                        <span>🚪</span>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            
            <!-- Desktop Topbar -->
            <header class="bg-white border-b border-stone-200 px-6 py-4 hidden md:flex items-center justify-between sticky top-0 z-20 shadow-sm">
                <div>
                    <h2 class="text-xl font-black text-stone-900">@yield('page_title', 'Dashboard')</h2>
                    <p class="text-xs text-stone-500 mt-0.5">@yield('page_subtitle', 'Panel Kontrol Admin Foodie')</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        <span>Restoran Buka & Online</span>
                    </div>
                    <span class="text-xs font-medium text-stone-400">
                        {{ now()->isoFormat('dddd, D MMMM Y') }}
                    </span>
                </div>
            </header>

            <!-- Main Page Body -->
            <main class="p-4 sm:p-6 lg:p-8 flex-1">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm animate-fade-in">
                        <div class="flex items-center space-x-3">
                            <span class="text-xl">✅</span>
                            <span class="text-xs sm:text-sm font-bold">{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-bold">&times;</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between shadow-sm animate-fade-in">
                        <div class="flex items-center space-x-3">
                            <span class="text-xl">⚠️</span>
                            <span class="text-xs sm:text-sm font-bold">{{ session('error') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-900 font-bold">&times;</button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

    </div>

    @yield('scripts')
</body>
</html>
