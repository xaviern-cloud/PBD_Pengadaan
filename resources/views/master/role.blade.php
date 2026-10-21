<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data Role</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 rounded shadow-sm">
        <h3 class="mb-4">Data Master Role</h3>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID Role</th>
                    <th>Nama Role</th>
                </tr>
            </thead>
            <tbody>
                @forelse($role as $role)
                    <tr>
                        <td>{{ $role->id_role}}</td>
                        <td>{{ $role->nama_role}}</td>
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
