function openModal(idModal) {
    document.getElementById(idModal).classList.add('active');
}

function closeModal(idModal) {
    document.getElementById(idModal).classList.remove('active');
}

function editUser(id, username, idRole) {
    const formEdit = document.getElementById('formEditUser');
    formEdit.action = `/master/user/${id}`;

    document.getElementById('edit_username').value = username;
    document.getElementById('edit_id_role').value = idRole;

    openModal('modalEditUser');
}

function confirmDelete(name) {
    return confirm(`Apakah Anda yakin ingin menghapus user "${name}"?`);
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal-overlay')) {
        event.target.classList.remove('active');
    }
};
