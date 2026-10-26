<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Master Data Vendor</title>
    <link rel="stylesheet" href="app.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="container-main">
        <h3>Data Master Vendor</h3>
        <table class="tabel-vendor">
            <div>
                    <label for="filterStatus">
                        <i> Filter Status </i>
                    </label>
                        <select name="status" id="filterStatus" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                    <option value="All" {{ ($Status ?? 'All') == 'All' ? 'selected' : '' }}>-- Semua Status --</option>
                                    <option value="Aktif" {{ ($Status ?? '') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Non-Aktif" {{ ($Status ?? '') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                        </div>
                    </form>
            <thead>
                <tr>
                    <th>ID VENDOR</th>
                    <th>NAMA VENDOR</th>
                    <th>BADAN HUKUM</th>
                    <th>STATUS</th>
                </tr>
            </thead>

            <tbody>
                @forelse($vendor as $v)
                    <tr class="{{ $v->Status == 'AKTIF' ? 'status-aktif' : 'status-nonaktif'}}">
                            <td>{{ $v->id_vendor }}</td>
                            <td>{{ $v->nama_vendor }}</td>
                            <td>{{ $v->badan_hukum }}</td>
                            <td>{{ $v->Status}}</td>
                    </tr>
                @empty
                    <tr>
                        <td>Data vendor kosong</td>
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
