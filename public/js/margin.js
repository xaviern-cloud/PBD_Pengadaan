function openModal(idModal) {
    document.getElementById(idModal).classList.add('active');
}

function closeModal(idModal) {
    document.getElementById(idModal).classList.remove('active');
}

function editMargin(id, persen, idUser, status) {
    const formEdit = document.getElementById('formEditMargin');
    formEdit.action = `/master/margin/${id}`;

    document.getElementById('edit_persen').value = persen;
    document.getElementById('edit_id_user').value = idUser;

    let statusCode = (status === 'Aktif' || status == 1) ? '1' : '0';
    document.getElementById('edit_status').value = statusCode;

    openModal('modalEditMargin');
}

function confirmDelete(persen) {
    return confirm(`Apakah Anda yakin ingin menghapus margin ${persen}%?`);
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal-overlay')) {
        event.target.classList.remove('active');
    }
};
