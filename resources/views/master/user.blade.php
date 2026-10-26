<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data User</title>
    <link rel="stylesheet" href="app.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container-main">
        <h3>Data Master User</h3>
        <table class=tabel-vendor>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pengguna</th>
                    <th>Peran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($user as $pengguna)
                    <tr>
                        <td>{{ $pengguna->id_user ?? $pengguna->iduser }}</td>
                        <td>{{ $pengguna->username }}</td>
                        <td>{{ $pengguna->namarole ?? $pengguna->nama_role}}</td>
                    </tr>
                @empty
                    <tr>
                        <td>Data Pengguna kosong</td>
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
