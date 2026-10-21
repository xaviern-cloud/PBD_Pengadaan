<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Kasir - Transaksi Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .product-card { cursor: pointer; transition: transform 0.15s ease-in-out; }
        .product-card:hover { transform: translateY(-3px); border-color: #0d6efd; }
        .cart-table { max-height: 380px; overflow-y: auto; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#"><i class="bi bi-cart4 me-2"></i>ERP Penjualan</a>
        <div class="text-white me-3">
            <i class="bi bi-person-circle me-1"></i> User: <strong>{{ auth()->user()->username ?? 'Kasir' }}</strong>
        </div>
    </div>
</nav>

<div class="container-fluid px-4">
    <div class="row g-3">
        <!-- Kolom Kiri: Daftar Barang -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title m-0 fw-bold"><i class="bi bi-box-seam me-2"></i>Pilih Barang</h5>
                </div>
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-3 g-3">
                        @foreach($barangs as $b)
                        <div class="col">
                            <div class="card h-100 product-card shadow-sm border"
                                onclick="addToCart({{ $b->id_barang }}, '{{ $b->nama_barang }}', {{ $b->harga }})">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-truncate fw-bold">{{ $b->nama_barang }}</h6>
                                    <span class="badge bg-secondary mb-2">{{ $b->satuan->nama_satuan ?? 'Pcs' }}</span>
                                    <p class="text-primary fw-bold fs-5 mb-0">Rp {{ number_format($b->harga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Keranjang Belanja & Checkout -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title m-0 fw-bold"><i class="bi bg-cart-check me-2"></i>Keranjang Transaksi</h5>
                </div>
                <div class="card-body">
                    <!-- Pilih Margin Penjualan -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Margin Penjualan</label>
                        <select id="id_margin_penjualan" class="form-select" onchange="calculateTotal()">
                            @foreach($margins as $m)
                                <option value="{{ $m->id_margin_penjualan }}" data-persen="{{ $m->persen }}">
                                    Margin {{ $m->persen }}%
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tabel Item -->
                    <div class="cart-table border rounded mb-3">
                        <table class="table table-hover align-middle m-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th width="100">Qty</th>
                                    <th>Subtotal</th>
                                    <th width="40"></th>
                                </tr>
                            </thead>
                            <tbody id="cart-body">
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Keranjang masih kosong</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Ringkasan Pembayaran -->
                    <div class="bg-light p-3 rounded mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Subtotal Barang:</span>
                            <strong id="disp-subtotal">Rp 0</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Margin Penjualan:</span>
                            <strong id="disp-margin">Rp 0</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>PPN (11%):</span>
                            <strong id="disp-ppn">Rp 0</strong>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between text-primary fs-4 fw-bold">
                            <span>Total Bayar:</span>
                            <span id="disp-total">Rp 0</span>
                        </div>
                    </div>

                    <button class="btn btn-success btn-lg w-100 fw-bold" onclick="submitTransaction()">
                        <i class="bi bi-cash-stack me-2"></i>Proses Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let cart = [];

function addToCart(id, nama, harga) {
    let item = cart.find(i => i.id_barang === id);
    if (item) {
        item.jumlah += 1;
    } else {
        cart.push({ id_barang: id, nama_barang: nama, harga_satuan: harga, jumlah: 1 });
    }
    renderCart();
}

function updateQty(id, qty) {
    let item = cart.find(i => i.id_barang === id);
    if (item) {
        item.jumlah = parseInt(qty) || 1;
        renderCart();
    }
}

function removeFromCart(id) {
    cart = cart.filter(i => i.id_barang !== id);
    renderCart();
}

function renderCart() {
    const tbody = document.getElementById('cart-body');
    if (cart.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">Keranjang masih kosong</td></tr>';
        calculateTotal();
        return;
    }

    let html = '';
    cart.forEach(item => {
        let subtotal = item.harga_satuan * item.jumlah;
        html += `
            <tr>
                <td><strong>${item.nama_barang}</strong><br><small class="text-muted">@ Rp ${item.harga_satuan.toLocaleString('id-ID')}</small></td>
                <td><input type="number" class="form-control form-control-sm" value="${item.jumlah}" min="1" onchange="updateQty(${item.id_barang}, this.value)"></td>
                <td>Rp ${subtotal.toLocaleString('id-ID')}</td>
                <td><button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${item.id_barang})"><i class="bi bi-trash"></i></button></td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
    calculateTotal();
}

function calculateTotal() {
    let subtotalBarang = cart.reduce((sum, item) => sum + (item.harga_satuan * item.jumlah), 0);

    let selectMargin = document.getElementById('id_margin_penjualan');
    let marginPersen = selectMargin.options[selectMargin.selectedIndex]?.getAttribute('data-persen') || 0;

    let nilaiMargin = subtotalBarang * (parseFloat(marginPersen) / 100);
    let subtotalDenganMargin = subtotalBarang + nilaiMargin;
    let ppn = subtotalDenganMargin * 0.11; // PPN 11%
    let totalNilai = subtotalDenganMargin + ppn;

    document.getElementById('disp-subtotal').innerText = 'Rp ' + subtotalBarang.toLocaleString('id-ID');
    document.getElementById('disp-margin').innerText = 'Rp ' + nilaiMargin.toLocaleString('id-ID');
    document.getElementById('disp-ppn').innerText = 'Rp ' + Math.round(ppn).toLocaleString('id-ID');
    document.getElementById('disp-total').innerText = 'Rp ' + Math.round(totalNilai).toLocaleString('id-ID');

    return { subtotalBarang, nilaiMargin, ppn: Math.round(ppn), totalNilai: Math.round(totalNilai) };
}

function submitTransaction() {
    if (cart.length === 0) {
        alert('Keranjang belanja masih kosong!');
        return;
    }

    const calc = calculateTotal();
    const payload = {
        id_margin_penjualan: document.getElementById('id_margin_penjualan').value,
        subtotal_nilai: calc.subtotalBarang + calc.nilaiMargin,
        ppn: calc.ppn,
        total_nilai: calc.totalNilai,
        items: cart.map(i => ({
            id_barang: i.id_barang,
            harga_satuan: i.harga_satuan,
            jumlah: i.jumlah,
            subtotal: i.harga_satuan * i.jumlah
        }))
    };

    fetch('/penjualan', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        alert('Transaksi Berhasil Disimpan!');
        cart = [];
        renderCart();
    })
    .catch(err => alert('Gagal menyimpan transaksi!'));
}
</script>

</body>
</html>


