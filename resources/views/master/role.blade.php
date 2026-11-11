<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Role - ERP</title>
    <link rel="stylesheet" href="{{ asset('css/role.css') }}">
</head>
<body>
    <div class="container-main">
        <h1>Master Data Role (v_role)</h1>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <button class="button-tambah" onclick="openModal('modalTambahRole')">+ Tambah Role</button>

        <div style="overflow-x: auto; width: 100%;">
            <table class="tabel-custom">
                <thead>
                    <tr>
                        <th>ID ROLE</th>
                        <th>NAMA ROLE</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($role as $r)
                    <tr>
                        <td><strong>{{ $r->id_role }}</strong></td>
                        <td>{{ $r->nama_role }}</td>
                        <td>
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <button type="button" class="button-edit"
                                        onclick="editRole({{ $r->id_role }}, '{{ addslashes($r->nama_role) }}')">
                                    Edit
                                </button>
                                <form action="{{ route('master.role.destroy', $r->id_role) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete('{{ $r->nama_role }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button-hapus">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 20px;">Data role kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal-overlay" id="modalTambahRole">
        <div class="modal-box">
            <form action="{{ route('master.role.store') }}" method="POST">
                @csrf
                <div class="modal-header modal-header-primary">
                    <span>Tambah Role</span>
                    <button type="button" class="modal-close" onclick="closeModal('modalTambahRole')">&times;</button>
                </div>
                <div class="modal-body">
                    <label>Nama Role</label>
                    <input type="text" name="nama_role" required placeholder="Contoh: Admin / Kasir / Manager">
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-hapus" onclick="closeModal('modalTambahRole')">Batal</button>
                    <button type="submit" class="button-edit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal-overlay" id="modalEditRole">
        <div class="modal-box">
            <form id="formEditRole" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header modal-header-warning">
                    <span>Edit Role</span>
                    <button type="button" class="modal-close" onclick="closeModal('modalEditRole')">&times;</button>
                </div>
                <div class="modal-body">
                    <label>Nama Role</label>
                    <input type="text" name="nama_role" id="edit_nama_role" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-hapus" onclick="closeModal('modalEditRole')">Batal</button>
                    <button type="submit" class="button-edit">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/role.js') }}"></script>
</body>
</html>
