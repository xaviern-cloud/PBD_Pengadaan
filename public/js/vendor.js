function openModal(idModal) {
    document.getElementById(idModal).classList.add('active');
}

function closeModal(idModal) {
    document.getElementById(idModal).classList.remove('active');
}

function editVendor(id, nama, badanHukum, status) {
    const formEdit = document.getElementById('formEditVendor');
    formEdit.action = `/master/vendor/${id}`;

    document.getElementById('edit_nama_vendor').value = nama;

    let bhCode = 'P';
    if (badanHukum === 'CV') bhCode = 'C';
    else if (badanHukum === 'UD') bhCode = 'U';
    document.getElementById('edit_badan_hukum').value = bhCode;

    let statusCode = (status === 'Aktif') ? 'A' : 'N';
    document.getElementById('edit_status').value = statusCode;

    openModal('modalEditVendor');
}

function confirmDelete(namaVendor) {
    return confirm(`Apakah Anda yakin ingin menghapus vendor "${namaVendor}"?`);
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal-overlay')) {
        event.target.classList.remove('active');
    }
};