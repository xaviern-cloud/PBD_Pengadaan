<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Pengguna - Sistem Inventori & POS</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 1.4fr;
            gap: 24px;
            width: 100%;
        }
        .card-box {
            background: rgba(15, 17, 36, 0.88);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(231, 234, 245, 0.14);
            border-radius: 16px;
            padding: 28px;
        }
        .avatar-circle {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, #747ead, #454c83);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: bold;
            color: #fff;
            margin-bottom: 16px;
        }
        .role-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            background: rgba(116, 126, 173, 0.3);
            color: #bcc2ff;
            margin-top: 6px;
        }
        .authority-list {
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid rgba(231, 234, 245, 0.12);
            list-style: none;
        }
        .authority-list li {
            font-size: 13px;
            color: #c7c5d1;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #bcc2ff;
            margin-bottom: 6px;
        }
        .form-control {
            width: 100%;
            padding: 11px 14px;
            border-radius: 8px;
            border: 1px solid rgba(231, 234, 245, 0.22);
            background: rgba(41, 45, 84, 0.6);
            color: #ffffff;
            font-size: 14px;
            outline: none;
        }
        .form-control:focus {
            border-color: #bcc2ff;
        }
        .form-control[disabled] {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .alert-success {
            background: rgba(46, 204, 113, 0.2);
            border: 1px solid #2ecc71;
            color: #8ce99a;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
        }
        .alert-error {
            background: rgba(231, 76, 60, 0.2);
            border: 1px solid #e74c3c;
            color: #ff8787;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
        }
        @media (max-width: 768px) {
            .profile-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @php
        $roleLower = strtolower($user->nama_role ?? '');
        $isKasir = str_contains($roleLower, 'kasir');
    @endphp

    <!-- Navigasi Atas -->
    <nav class="top-nav">
        <div class="nav-brand">LOGISTICS ERP</div>
        <div class="nav-links">
            @if(!$isKasir)
                <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                <a href="{{ route('pengadaan.index') }}" class="nav-link">Pengadaan (PO)</a>
                <a href="{{ route('penerimaan.index') }}" class="nav-link">Penerimaan</a>
            @endif
            <a href="{{ route('penjualan.index') }}" class="nav-link">Penjualan (POS)</a>
            <a href="{{ route('retur.index') }}" class="nav-link">Retur Barang</a>
            @if(!$isKasir)
                <a href="{{ route('kartu-stok.index') }}" class="nav-link">Kartu Stok</a>
            @endif
            <a href="{{ route('profile') }}" class="nav-link active">Profile</a>
        </div>
    </nav>

    <div class="main-wrapper" style="flex-direction: column;">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <div class="profile-grid">
            <!-- Kartu Informasi Akun & Hak Akses -->
            <div class="card-box">
                <div class="avatar-circle">
                    {{ strtoupper(substr($user->username, 0, 1)) }}
                </div>
                <h2 style="font-size: 22px; color: #fff;">{{ $user->username }}</h2>
                <span class="role-badge">{{ strtoupper($user->nama_role ?? 'USER') }} (ID #{{ $user->id_user }})</span>

                <ul class="authority-list">
                    <li style="color: #fff; font-weight: 600; margin-bottom: 10px;">Otoritas Role Anda:</li>
                    @if(str_contains($roleLower, 'admin'))
                        <li>Akses Penuh Semua Master Data (Create, Edit, Delete)</li>
                        <li>Pengadaan (PO), Penerimaan, Penjualan (POS), & Retur</li>
                        <li>Laporan Kartu Stok & Monitoring Sistem</li>
                    @elseif(str_contains($roleLower, 'manager'))
                        <li>Akses Semua Master Data (Create & Edit saja)</li>
                        <li>Tidak memiliki akses Hapus (Delete) data</li>
                        <li>Pengadaan (PO), Penerimaan, Penjualan (POS), Retur, & Kartu Stok</li>
                    @else
                        <li>Mengoperasikan Kasir Penjualan (POS) & Cetak Struk</li>
                        <li>Mengajukan & Memproses Retur Barang</li>
                        <li>Akses terbatas dari Master Data, Pengadaan, & Penerimaan</li>
                    @endif
                </ul>

                <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid rgba(231, 234, 245, 0.12);">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-secondary" style="width: 100%; text-align: center; color: #ff8787; border-color: rgba(231, 76, 60, 0.4);">
                            Keluar dari Sistem (Logout)
                        </button>
                    </form>
                </div>
            </div>

            <!-- Form Edit Profil & Ganti Password -->
            <div class="card-box">
                <h2 style="font-size: 20px; color: #fff; margin-bottom: 6px;">Pengaturan Akun</h2>
                <p style="font-size: 13px; color: #c7c5d1; margin-bottom: 22px;">
                    Perbarui username atau ubah password keamanan akun Anda.
                </p>

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>ID Pengguna</label>
                        <input type="text" class="form-control" value="#{{ $user->id_user }}" disabled>
                    </div>

                    <div class="form-group">
                        <label>Role / Jabatan</label>
                        <input type="text" class="form-control" value="{{ $user->nama_role }}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password Baru <span style="font-weight: 400; color: #c7c5d1;">(Kosongkan jika tidak ingin mengganti)</span></label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 4 karakter">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px;">
                        <a href="{{ $isKasir ? route('penjualan.index') : route('dashboard') }}" class="btn-secondary">Kembali</a>
                        <button type="submit" class="btn-pembelian">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>