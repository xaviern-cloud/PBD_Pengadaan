<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Master Data Vendor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 rounded shadow-sm">
        <h3 class="mb-4">Data Master Vendor</h3>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID Vendor</th>
                    <th>Nama Vendor</th>
                    <th>Badan Hukum</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vendor as $v)
                    <tr>
                        <td>{{ $v->id_vendor }}</td>
                        <td>{{ $v->nama_vendor }}</td>
                        <td>{{ $v->badan_hukum }}</td>
                        <td>@if($v->status == 1)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Non-Aktif</span>
                            @endif</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Data vendor kosong</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <a href="/">
        <button type="button">Dashboard</button>
    </a>
</body>
</html>
