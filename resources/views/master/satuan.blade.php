<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data Satuan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 rounded shadow-sm">
        <h3 class="mb-4">Data Master Satuan</h3>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID Satuan</th>
                    <th>Nama Satuan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($satuan as $satuan)
                    <tr>
                        <td>{{ $satuan->id_satuan}}</td>
                        <td>{{ $satuan->nama_satuan}}</td>
                        <td>@if($satuan->status == 1)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Non-Aktif</span>
                            @endif</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Data Pengguna kosong</td>
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
