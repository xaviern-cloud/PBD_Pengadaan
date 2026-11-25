<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem ERP Pengadaan & POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#15162B] text-white min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-[#292D54] border border-indigo-500/30 rounded-2xl p-8 shadow-2xl">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold tracking-wide text-indigo-300">ERP LOGISTIK & POS</h1>
            <p class="text-sm text-gray-300 mt-1">Silakan masuk sesuai otoritas akun Anda</p>
        </div>

        @if($errors->has('login_error'))
            <div class="bg-red-500/20 border border-red-500 text-red-200 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ $errors->first('login_error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-500 text-emerald-200 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm text-gray-300 mb-1">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required
                       class="w-full px-4 py-2.5 rounded-lg bg-[#15162B] border border-indigo-400/30 focus:outline-none focus:border-indigo-400 text-white">
            </div>
            <div>
                <label class="block text-sm text-gray-300 mb-1">Password</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-4 py-2.5 rounded-lg bg-[#15162B] border border-indigo-400/30 focus:outline-none focus:border-indigo-400 text-white">
            </div>
            <button type="submit"
                    class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 rounded-lg font-semibold transition">
                Masuk ke Sistem
            </button>
        </form>

        <!-- Quick Login Helper untuk Demo Dosen -->
        <div class="mt-6 pt-4 border-t border-indigo-400/20">
            <p class="text-xs text-gray-400 mb-2 text-center">Akun Cepat Demo (Klik untuk isi otomatis):</p>
            <div class="grid grid-cols-3 gap-2 text-xs">
                <button type="button" onclick="fillLogin('admin', 'admin123')"
                        class="py-1.5 px-2 bg-purple-600/30 hover:bg-purple-600/50 border border-purple-400/40 rounded text-purple-200">
                    Admin
                </button>
                <button type="button" onclick="fillLogin('manager', 'manager123')"
                        class="py-1.5 px-2 bg-amber-600/30 hover:bg-amber-600/50 border border-amber-400/40 rounded text-amber-200">
                    Manager
                </button>
                <button type="button" onclick="fillLogin('kasir', 'kasir123')"
                        class="py-1.5 px-2 bg-emerald-600/30 hover:bg-emerald-600/50 border border-emerald-400/40 rounded text-emerald-200">
                    Kasir
                </button>
            </div>
        </div>
    </div>

    <script>
        function fillLogin(user, pass) {
            document.getElementById('username').value = user;
            document.getElementById('password').value = pass;
        }
    </script>
</body>
</html>
