<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data Satuan</title>
    <link rel="stylesheet" href=app.css>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class=container-main>
        <h3>Data Master Satuan</h3>

        <table class=tabel-vendor>
            <thead>
                <tr>
                    <th>ID Satuan</th>
                    <th>Nama Satuan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($satuan as $satuan)
                    <tr class= '{{ $satuan->Status == 'AKTIF' ? 'status-aktif' : 'status-nonaktif'}}'>
                        <td>{{$satuan->id_satuan}}</td>
                        <td>{{$satuan->nama_satuan}}</td>
                        <td>{{$satuan->Status}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Data Pengguna kosong</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <a href="/">
        <button class=button-penjualan type="button">Dashboard</button>
    </a>
    </div>

</body>
</html>
