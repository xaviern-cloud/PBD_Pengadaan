<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data Margin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 rounded shadow-sm">
        <h3 class="mb-4">Data Master Margin</h3>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
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
                    <tr>
                        <td>{{ $item->id_margin_penjualan}}</td>
                        <td>{{ $item->persen}}</td>
                        <td>@if($item->status == 1)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Non-Aktif</span>
                            @endif</td>
                        <td>{{ $item->dibuat_oleh}}</td>
                        <td>{{ $item->created_at}}</td>
                        <td>{{ $item->updated_at}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Data Margin kosong</td>
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
