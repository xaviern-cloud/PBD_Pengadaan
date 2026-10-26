<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data Barang</title>
    <link rel="stylesheet" href=app.css>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class=container-main>
        <h3>Data Master Barang</h3>
            <form method="GET" action="{{ route('master.barang') }}" id="filterForm">
                <div>
                    <label for="filterStatus">
                        <i> Filter Status </i>
                    </label>
                        <select name="status" id="filterStatus" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                    <option value="All" {{ ($status ?? 'All') == 'All' ? 'selected' : '' }}>-- Semua Status --</option>
                                    <option value="Aktif" {{ ($status ?? '') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Non-Aktif" {{ ($status ?? '') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                        </div>
                    </form>


        <table class=tabel-vendor>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Jenis</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($barang as $item)
                    <tr class= "{{ $item->Status == 'Aktif' ? 'status-aktif' : 'status-nonaktif'}}">
                        <td>{{ $item->id_barang}}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->jenis_barang }}</td>
                        <td>{{ $item->nama_satuan }}</td>
                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td>{{ $item->Status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td>Data barang kosong</td>
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
