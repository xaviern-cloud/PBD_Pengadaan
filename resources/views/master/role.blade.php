<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data Role</title>
    <link rel="stylesheet" href=app.css>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container-main">
        <h3>Data Master Role</h3>

        <table class="tabel-vendor">
            <thead>
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
    <a href="/">
        <button class='button-penjualan' type="button">Dashboard</button>
    </a>
    </div>

</body>
</html>
