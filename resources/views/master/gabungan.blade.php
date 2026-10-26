<!DOCTYPE html>
<html lang="id">
<head>
    <title>Master Data Gabungan</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class='container-main'>
        <h3>Data Master Gabungan</h3>
        <div class="container-big">
            @include('master.margin', ['margin' => $margin])
            @include('master.vendor', ['vendor' => $vendor])
            @include('master.user', ['user' => $user])
            @include('master.barang', ['barang' => $barang])
            @include('master.role', ['role' => $role])
            @include('master.satuan', ['satuan' => $satuan])
        </div>
    </div>
</div>

</body>
</html>
