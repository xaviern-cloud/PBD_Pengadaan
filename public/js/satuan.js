function openModal(idModal) {
    document.getElementById(idModal).classList.add('active');
}

function closeModal(idModal) {
    document.getElementById(idModal).classList.remove('active');
}

function editSatuan(id, nama, status) {
    const formEdit = document.getElementById('formEditSatuan');
    formEdit.action = `/master/satuan/${id}`;

    document.getElementById('edit_nama_satuan').value = nama;

    let statusCode = (status === 'Aktif' || status == 1) ? '1' : '0';
    document.getElementById('edit_status').value = statusCode;

    openModal('modalEditSatuan');
}

function confirmDelete(nama) {
    return confirm(`Apakah Anda yakin ingin menghapus satuan "${nama}"?`);
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal-overlay')) {
        event.target.classList.remove('active');
    }
};
