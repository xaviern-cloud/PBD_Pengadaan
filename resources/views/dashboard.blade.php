<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="app.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class=container-main>
        <h1>Dashboard</h1>

        <div class=container-second>
            <a href="/master/vendor">
                <button class=button-master-view  type="button">View Master Vendor</button>
            </a>
            <a href="/master/user">
                <button class=button-master-view type="button">View Master User</button>
            </a>
            <a href="/master/margin">
                <button class=button-master-view type="button">View Master Margin</button>
            </a>
            <a href="/master/barang">
                <button class=button-master-view  type="button">View Master Barang</button>
            </a>
            <a href="/master/satuan">
                <button class=button-master-view  type="button">View Master Satuan</button>
            </a>
            <a href="/master/role">
                <button class=button-master-view type="button">View Master Role</button>
            </a>
            <a href="/penjualan">
                <button class=button-penjualan type="button">Halaman Store</button>
            </a>
            <a href="/master/gabungan">
                <button class=button-master-view type="button">View Master Gabungan</button>
        </div>
    </div>
</body>

</html>
