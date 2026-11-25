<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard - Sistem Inventori & POS</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
</head>
<body>
    <!-- Navigasi Atas Lengkap -->
    <nav class="top-nav">
        <div class="nav-brand">LOGISTICS ERP</div>
        <div class="nav-links">
            <a href="{{ route('dashboard') }}" class="nav-link active">Dashboard</a>

            <div class="nav-dropdown">
                <button type="button" class="nav-link dropbtn">Master Data ▾</button>
                <div class="dropdown-content">
                    <a href="{{ route('master.barang') }}">Master Barang</a>
                    <a href="{{ route('master.satuan') }}">Master Satuan</a>
                    <a href="{{ route('master.vendor') }}">Master Vendor</a>
                    <a href="{{ route('master.user') }}">Master User</a>
                    <a href="{{ route('master.role') }}">Master Role</a>
                    <a href="{{ route('master.margin') }}">Master Margin</a>
                </div>
            </div>

            <a href="{{ route('pengadaan.index') }}" class="nav-link">Pengadaan (PO)</a>
            <a href="{{ route('penerimaan.index') }}" class="nav-link">Penerimaan</a>
            <a href="{{ route('penjualan.index') }}" class="nav-link">Penjualan (POS)</a>
            <a href="{{ route('kartu-stok.index') }}" class="nav-link">Kartu Stok</a>
            <a href="{{ route('retur.index') }}" class="nav-link">Retur Barang</a>
            <a href="/profile" class="nav-link">Profile</a>
        </div>
    </nav>

    <!-- Hero Section: Sambutan & 3D Model -->
    <div class="main-wrapper">
        <div class="main-container-text">
            <div>
                <span class="badge-sistem">SISTEM MANAJEMEN INVENTORI & POS</span>
                <h1>Selamat Datang, {{ session('username', 'Pengguna') }}</h1>
                <p>Kelola data master, transaksi pengadaan vendor, penerimaan gudang, hingga kasir penjualan dalam satu pusat kendali.</p>
            </div>
            <div class="action-buttons">
                <a href="{{ route('penjualan.index') }}" class="btn-pembelian">Lakukan Penjualan (POS)</a>
                <a href="{{ route('pengadaan.index') }}" class="btn-secondary">Buat Pengadaan (PO)</a>
            </div>
        </div>

        <div class="main-container-model">
            <model-viewer
                src="{{ asset('models/anemone_flower_low_poly.glb') }}"
                alt="A 3D model"
                auto-rotate
                camera-controls
                touch-action="pan-y"
                shadow-intensity="1">
            </model-viewer>
        </div>
    </div>

    <!-- Section Katalog Barang Real-Time -->
    <div class="main-wrapper">
        <div class="katalog">
            <div class="katalog-header">
                <div>
                    <h2>Katalog Barang Aktif</h2>
                    <p>Daftar barang tersedia beserta harga modal dan sisa stok gudang terkini.</p>
                </div>
                <input type="text" id="searchKatalog" class="search-input" placeholder="Cari kode atau nama barang...">
            </div>

            <div class="katalog-grid" id="katalogGrid">
                @forelse($katalog ?? [] as $item)
                    <div class="katalog-card" data-search="{{ strtolower($item->id_barang . ' ' . $item->nama_barang . ' ' . $item->jenis_barang) }}">
                        <div class="card-top">
                            <span class="badge-kode">{{ $item->id_barang }}</span>
                            <span class="badge-jenis">{{ $item->jenis_barang }}</span>
                        </div>
                        <h3 class="card-nama">{{ $item->nama_barang }}</h3>
                        <p class="card-harga">Rp {{ number_format($item->harga, 0, ',', '.') }} <span>/ {{ $item->nama_satuan }}</span></p>
                        <div class="card-footer">
                            <span class="stok-label {{ $item->stok <= 5 ? 'stok-kritis' : 'stok-aman' }}">
                                Stok: {{ $item->stok }} {{ $item->nama_satuan }}
                            </span>
                            <a href="{{ route('penjualan.index') }}" class="card-link">Transaksi →</a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <p>Belum ada data barang aktif di katalog.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>
