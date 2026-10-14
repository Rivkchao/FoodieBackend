<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Admin - Foodie Portal</title>
    <!-- Favicon (Same as Frontend 🍔) -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍔</text></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-gradient-to-br from-stone-900 via-stone-800 to-stone-950 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full">
        <!-- Logo & Branding -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-tr from-brand-600 to-amber-500 rounded-3xl mx-auto flex items-center justify-center text-3xl shadow-xl shadow-brand-500/20 mb-3 animate-bounce">
                🍔
            </div>
            <h1 class="text-3xl font-black text-white tracking-tight">FOODIE ADMIN</h1>
            <p class="text-xs text-stone-400 mt-1">Panel Pengelolaan Pesanan, Menu, Voucher & Laporan</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl p-8 shadow-2xl border border-stone-100">
            <!-- Quick Credential Helper -->
            <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-amber-900 uppercase tracking-wider">Akun Admin Default</span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-200 text-amber-900">Demo Siap Pakai</span>
                </div>
                <div class="text-xs font-mono text-stone-600 space-y-1 mb-3">
                    <div>Email: <strong class="text-stone-900 select-all">admin@foodie.test</strong></div>
                    <div>Password: <strong class="text-stone-900 select-all">password</strong></div>
                </div>
                <button type="button" 
                        onclick="fillAdminCredentials()"
                        class="w-full py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition shadow-sm">
                    ⚡ Isi Akun Admin Otomatis
                </button>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-emerald-50 text-emerald-800 text-xs font-semibold rounded-xl border border-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 bg-rose-50 text-rose-800 text-xs font-semibold rounded-xl border border-rose-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                        Alamat Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', 'admin@foodie.test') }}"
                           required 
                           autofocus
                           placeholder="admin@foodie.test"
                           class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-brand-500 focus:bg-white transition" />
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                        Kata Sandi
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           value="password"
                           required
                           placeholder="••••••••"
                           class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-brand-500 focus:bg-white transition" />
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center space-x-2 text-stone-600 cursor-pointer">
                        <input type="checkbox" name="remember" checked class="rounded border-stone-300 text-brand-600 focus:ring-brand-500" />
                        <span>Ingat sesi login saya</span>
                    </label>
                </div>

                <button type="submit" 
                        class="w-full py-3.5 px-4 bg-gradient-to-r from-brand-600 to-orange-500 hover:from-brand-700 hover:to-orange-600 text-white font-black text-sm rounded-xl shadow-lg shadow-brand-500/30 transition transform active:scale-[0.99] mt-2">
                    Masuk ke Admin Dashboard →
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-stone-500 mt-6">
            &copy; {{ date('Y') }} Foodie Backend System. Hak cipta dilindungi.
        </p>
    </div>

    <script>
        function fillAdminCredentials() {
            document.getElementById('email').value = 'admin@foodie.test';
            document.getElementById('password').value = 'password';
        }
    </script>
</body>
</html>
