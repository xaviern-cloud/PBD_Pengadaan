<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master User - ERP</title>
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
</head>
<body>
    <div class="container-main">
        <h1>Master Data User (v_user)</h1>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <button class="button-tambah" onclick="openModal('modalTambahUser')">+ Tambah User</button>

        <div style="overflow-x: auto; width: 100%;">
            <table class="tabel-custom">
                <thead>
                    <tr>
                        <th>ID USER</th>
                        <th>USERNAME</th>
                        <th>ROLE SYSTEM</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user as $u)
                    <tr>
                        <td><strong>{{ $u->id_user }}</strong></td>
                        <td>{{ $u->username }}</td>
                        <td>{{ $u->nama_role }}</td>
                        <td>
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <button type="button" class="button-edit"
                                        onclick="editUser({{ $u->id_user }}, '{{ addslashes($u->username) }}', '{{ $u->id_role ?? '' }}')">
                                    Edit
                                </button>
                                <form action="{{ route('master.user.destroy', $u->id_user) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete('{{ $u->username }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button-hapus">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 20px;">Data user kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal-overlay" id="modalTambahUser">
        <div class="modal-box">
            <form action="{{ route('master.user.store') }}" method="POST">
                @csrf
                <div class="modal-header modal-header-primary">
                    <span>Tambah User</span>
                    <button type="button" class="modal-close" onclick="closeModal('modalTambahUser')">&times;</button>
                </div>
                <div class="modal-body">
                    <label>Username</label>
                    <input type="text" name="username" required placeholder="Contoh: kasir1">

                    <label>Password</label>
                    <input type="password" name="password" required placeholder="••••••••">

                    <label>Role</label>
                    <select name="id_role" required>
                        @foreach($roleList ?? [] as $r)
                            <option value="{{ $r->id_role }}">{{ $r->nama_role }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-hapus" onclick="closeModal('modalTambahUser')">Batal</button>
                    <button type="submit" class="button-edit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal-overlay" id="modalEditUser">
        <div class="modal-box">
            <form id="formEditUser" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header modal-header-warning">
                    <span>Edit User</span>
                    <button type="button" class="modal-close" onclick="closeModal('modalEditUser')">&times;</button>
                </div>
                <div class="modal-body">
                    <label>Username</label>
                    <input type="text" name="username" id="edit_username" required>

                    <label>Password Baru (Opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diubah">

                    <label>Role</label>
                    <select name="id_role" id="edit_id_role" required>
                        @foreach($roleList ?? [] as $r)
                            <option value="{{ $r->id_role }}">{{ $r->nama_role }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-hapus" onclick="closeModal('modalEditUser')">Batal</button>
                    <button type="submit" class="button-edit">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/user.js') }}"></script>
</body>
</html>
