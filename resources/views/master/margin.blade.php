
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Margin - ERP</title>
    <link rel="stylesheet" href="{{ asset('css/margin.css') }}">
</head>
<body>
    <div class="container-main">
        <h1>Master Margin Penju<!DOCTYPE html>alan (v_margin_penjualan)</h1>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <button class="button-tambah" onclick="openModal('modalTambahMargin')">+ Tambah Margin</button>

        <div style="overflow-x: auto; width: 100%;">
            <table class="tabel-custom">
                <thead>
                    <tr>
                        <th>ID MARGIN</th>
                        <th>PERSEN (%)</th>
                        <th>DIBUAT OLEH</th>
                        <th>
                            STATUS
                            <form method="GET" action="{{ route('master.margin') }}" style="display: inline-block; margin-left: 8px;">
                                <select name="status" class="dropdown-status" onchange="this.form.submit()">
                                    <option value="All" {{ ($status ?? 'All') == 'All' ? 'selected' : '' }}>-- Semua --</option>
                                    <option value="Aktif" {{ ($status ?? '') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Non-Aktif" {{ ($status ?? '') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                            </form>
                        </th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($margin as $m)
                    <tr>
                        <td><strong>{{ $m->id_margin_penjualan }}</strong></td>
                        <td>{{ $m->persen }}%</td>
                        <td>{{ $m->dibuat_oleh ?? '-' }}</td>
                        <td>
                            <span class="{{ ($m->status ?? '') == 'Aktif' ? 'status-aktif' : 'status-nonaktif' }}">
                                {{ $m->status ?? 'Non-Aktif' }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <button type="button" class="button-edit"
                                        onclick="editMargin({{ $m->id_margin_penjualan }}, '{{ $m->persen }}', '{{ $m->id_user ?? '' }}', '{{ $m->status ?? '' }}')">
                                    Edit
                                </button>
                                <form action="{{ route('master.margin.destroy', $m->id_margin_penjualan) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete('{{ $m->persen }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button-hapus">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px;">Data margin kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal-overlay" id="modalTambahMargin">
        <div class="modal-box">
            <form action="{{ route('master.margin.store') }}" method="POST">
                @csrf
                <div class="modal-header modal-header-primary">
                    <span>Tambah Margin Penjualan</span>
                    <button type="button" class="modal-close" onclick="closeModal('modalTambahMargin')">&times;</button>
                </div>
                <div class="modal-body">
                    <label>Persentase Margin (%)</label>
                    <input type="number" step="0.01" name="persen" required placeholder="Contoh: 10.00">

                    <label>Dibuat Oleh (User)</label>
                    <select name="id_user" required>
                        @foreach($userList ?? [] as $u)
                            <option value="{{ $u->id_user }}">{{ $u->username }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-hapus" onclick="closeModal('modalTambahMargin')">Batal</button>
                    <button type="submit" class="button-edit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal-overlay" id="modalEditMargin">
        <div class="modal-box">
            <form id="formEditMargin" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header modal-header-warning">
                    <span>Edit Margin Penjualan</span>
                    <button type="button" class="modal-close" onclick="closeModal('modalEditMargin')">&times;</button>
                </div>
                <div class="modal-body">
                    <label>Persentase Margin (%)</label>
                    <input type="number" step="0.01" name="persen" id="edit_persen" required>

                    <label>Dibuat Oleh (User)</label>
                    <select name="id_user" id="edit_id_user" required>
                        @foreach($userList ?? [] as $u)
                            <option value="{{ $u->id_user }}">{{ $u->username }}</option>
                        @endforeach
                    </select>

                    <label>Status</label>
                    <select name="status" id="edit_status" required>
                        <option value="1">Aktif</option>
                        <option value="0">Non-Aktif</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-hapus" onclick="closeModal('modalEditMargin')">Batal</button>
                    <button type="submit" class="button-edit">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/margin.js') }}"></script>
</body>
</html>
