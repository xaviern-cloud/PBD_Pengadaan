<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Master Data Margin</title>
    <link rel="stylesheet" href="app.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container-main">
        <h3>Data Master Margin</h3>
        <table class="tabel-vendor">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Persentase</th>
                    <th>Status</th>
                    <th>Dibuat Oleh</th>
                    <th>Dibuat Tanggal</th>
                    <th>Diupdate Pada</th>
                </tr>
            </thead>

            <tbody>
                @forelse($margin as $item)
                    <tr class="{{ $item->Status == 'AKTIF' ? 'status-aktif' : 'status-nonaktif'}}">
                        <td>{{ $item->id_margin_penjualan}}</td>
                        <td>{{ $item->persen}} %</td>
                        <td>{{ $item->Status}}</td>
                        <td>{{ $item->dibuat_oleh}}</td>
                        <td>{{ $item->created_at}}</td>
                        <td>{{ $item->updated_at}}</td>
                    </tr>
                @empty
                    <tr>
                        <td>Data Margin kosong</td>
                    </tr>
                @endforelse
            </tbody>
        </table>


    <a href="/">
        <button class='button-penjualan' type="button">Dashboard</button>
    </a>

    </div>
</body>
</html>
