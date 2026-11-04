function openModal(idModal) {
    document.getElementById(idModal).classList.add('active');
}

function closeModal(idModal) {
    document.getElementById(idModal).classList.remove('active');
}

function editBarang(id, jenis, nama, idSatuan, harga, status) {
    const formEdit = document.getElementById('formEditBarang');
    formEdit.action = `/master/barang/${id}`;

    document.getElementById('edit_jenis_barang').value = jenis;
    document.getElementById('edit_nama_barang').value = nama;
    document.getElementById('edit_id_satuan').value = idSatuan;
    document.getElementById('edit_harga').value = harga;

    let statusCode = (status === 'Aktif' || status == 1) ? '1' : '0';
    document.getElementById('edit_status').value = statusCode;

    openModal('modalEditBarang');
}

function confirmDelete(namaBarang) {
    return confirm(`Apakah Anda yakin ingin menghapus barang "${namaBarang}"?`);
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal-overlay')) {
        event.target.classList.remove('active');
    }
};
