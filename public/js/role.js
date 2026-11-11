function openModal(idModal) {
    document.getElementById(idModal).classList.add('active');
}

function closeModal(idModal) {
    document.getElementById(idModal).classList.remove('active');
}

function editRole(id, namaRole) {
    const formEdit = document.getElementById('formEditRole');
    formEdit.action = `/master/role/${id}`;

    document.getElementById('edit_nama_role').value = namaRole;

    openModal('modalEditRole');
}

function confirmDelete(namaRole) {
    return confirm(`Apakah Anda yakin ingin menghapus role "${namaRole}"?`);
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal-overlay')) {
        event.target.classList.remove('active');
    }
};
