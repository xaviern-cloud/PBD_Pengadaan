<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
</head>
<body>
    <div class="top-nav">
        <a href="/master/barang" class="nav-link">Master Barang</a>
        <a href="/master/satuan" class="nav-link">Master Satuan</a>
        <a href="/master/vendor" class="nav-link">Master Vendor</a>
        <a href="/master/user" class="nav-link">Master User</a>
        <a href="/master/margin" class="nav-link">Master Margin</a>
        <a href="/master/role" class="nav-link">Master Role</a>
        <a href='/profile' class="nav-link">Profile</a>
    </div>
    <div class="main-wrapper">
        <div class="main-container-text">
            <div>
                <h1>Selamat Datang, pengguna</h1>
                <p>Silakan pilih menu di atas atau lakukan transaksi melalui tombol di bawah.</p>
            </div>
            <a href="/penjualan" class="btn-pembelian">Lakukan Pembelian</a>
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
    <div class=main-wrapper>
        <div class="katalog">
            <h1>Katalog Barang</h1>
        </div>
    </div>


</body>
</html>
