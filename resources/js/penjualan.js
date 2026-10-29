let cart = [];

function addToCart(id, nama, harga) {
    let item = cart.find(i => i.id_barang === id);
    if (item) {
        item.jumlah += 1;
    } else {
        cart.push({
            id_barang: id,
            nama_barang: nama,
            harga_satuan: harga,
            jumlah: 1
        });
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
            <td>
                <strong>${item.nama_barang}</strong><br>
                <small class="text-muted">@ Rp ${item.harga_satuan.toLocaleString('id-ID')}</small>
            </td>
            <td>
                <input type="number" class="form-control form-control-sm" value="${item.jumlah}" min="1" onchange="updateQty(${item.id_barang}, this.value)">
            </td>
            <td>Rp ${subtotal.toLocaleString('id-ID')}</td>
            <td>
                <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${item.id_barang})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>`;
    });
    tbody.innerHTML = html;
    calculateTotal();
}

function calculateTotal() {
    let subtotalBarang = cart.reduce((sum, item) => sum + (item.harga_satuan * item.jumlah), 0);
    let selectMargin = document.getElementById('id_margin_penjualan');
    let marginPersen = 0;

    if (selectMargin && selectMargin.options.length > 0 && selectMargin.selectedIndex !== -1) {
        marginPersen = selectMargin.options[selectMargin.selectedIndex]?.getAttribute('data-persen') || 0;
    }

    let nilaiMargin = subtotalBarang * (parseFloat(marginPersen) / 100);
    let subtotalDenganMargin = subtotalBarang + nilaiMargin;
    let ppn = subtotalDenganMargin * 0.11; // PPN 11%
    let totalNilai = subtotalDenganMargin + ppn;

    document.getElementById('disp-subtotal').innerText = 'Rp ' + subtotalBarang.toLocaleString('id-ID');
    document.getElementById('disp-margin').innerText = 'Rp ' + nilaiMargin.toLocaleString('id-ID');
    document.getElementById('disp-ppn').innerText = 'Rp ' + Math.round(ppn).toLocaleString('id-ID');
    document.getElementById('disp-total').innerText = 'Rp ' + Math.round(totalNilai).toLocaleString('id-ID');

    return {
        subtotalBarang,
        nilaiMargin,
        ppn: Math.round(ppn),
        totalNilai: Math.round(totalNilai)
    };
}

function submitTransaction() {
    if (cart.length === 0) {
        alert('Kaseranjang belanja masih kosong!');
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
    .catch(err => {
        console.error(err);
        alert('Gagal menyimpan transaksi!');
    });
}

/**
 * Membuka Modal Edit dan mengisi data Vendor ke dalam Form
 */
function editVendor(id, nama, badanHukum, status) {
    // 1. Ubah Action URL Form Edit
    const formEdit = document.getElementById('formEditVendor');
    formEdit.action = `/master/vendor/${id}`;

    // 2. Isi Input Nama Vendor
    document.getElementById('edit_nama_vendor').value = nama;

    // 3. Mapping Badan Hukum (PT -> P, CV -> C, UD -> U) untuk input select form
    let bhCode = 'P';
    if (badanHukum === 'CV') bhCode = 'C';
    else if (badanHukum === 'UD') bhCode = 'U';
    document.getElementById('edit_badan_hukum').value = bhCode;

    // 4. Mapping Status (Aktif -> A, Non-Aktif -> N) untuk input select form
    let statusCode = (status === 'Aktif') ? 'A' : 'N';
    document.getElementById('edit_status').value = statusCode;

    // 5. Tampilkan Modal Edit Bootstrap
    const modalEdit = new bootstrap.Modal(document.getElementById('modalEditVendor'));
    modalEdit.show();
}

/**
 * Konfirmasi sebelum menghapus vendor
 */
function confirmDelete(namaVendor) {
    return confirm(`Apakah Anda yakin ingin menghapus vendor "${namaVendor}"?`);
}