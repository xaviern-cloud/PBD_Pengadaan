<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Satuan - ERP</title>
    <link rel="stylesheet" href="{{ asset('css/satuan.css') }}">
</head>
<body>
    <div class="container-main">
        <h1>Master Data Satuan</h1>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <button class="button-tambah" onclick="openModal('modalTambahSatuan')">+ Tambah Satuan</button>

        <div style="overflow-x: auto; width: 100%;">
            <table class="tabel-custom">
                <thead>
                    <tr>
                        <th>ID SATUAN</th>
                        <th>NAMA SATUAN</th>
                        <th>
                            STATUS
                            <form method="GET" action="{{ route('master.satuan') }}" style="display: inline-block; margin-left: 8px;">
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
                    @forelse($satuan as $s)
                    <tr>
                        <td><strong>{{ $s->id_satuan }}</strong></td>
                        <td>{{ $s->nama_satuan }}</td>
                        <td>
                            <span class="{{ ($s->status ?? '') == 'Aktif' ? 'status-aktif' : 'status-nonaktif' }}">
                                {{ $s->status ?? 'Non-Aktif' }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <button type="button" class="button-edit"
                                        onclick="editSatuan({{ $s->id_satuan }}, '{{ addslashes($s->nama_satuan) }}', '{{ $s->status ?? '' }}')">
                                    Edit
                                </button>
                                <form action="{{ route('master.satuan.destroy', $s->id_satuan) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete('{{ $s->nama_satuan }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button-hapus">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 20px;">Data satuan kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <a href="/">
        <button class='button-penjualan' type="button">Dashboard</button>
        </a>
    </div>

    <!-- Modal Tambah -->
    <div class="modal-overlay" id="modalTambahSatuan">
        <div class="modal-box">
            <form action="{{ route('master.satuan.store') }}" method="POST">
                @csrf
                <div class="modal-header modal-header-primary">
                    <span>Tambah Satuan</span>
                    <button type="button" class="modal-close" onclick="closeModal('modalTambahSatuan')">&times;</button>
                </div>
                <div class="modal-body">
                    <label>Nama Satuan</label>
                    <input type="text" name="nama_satuan" required placeholder="Contoh: Kilogram / Pcs / Box">
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-hapus" onclick="closeModal('modalTambahSatuan')">Batal</button>
                    <button type="submit" class="button-edit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal-overlay" id="modalEditSatuan">
        <div class="modal-box">
            <form id="formEditSatuan" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header modal-header-warning">
                    <span>Edit Satuan</span>
                    <button type="button" class="modal-close" onclick="closeModal('modalEditSatuan')">&times;</button>
                </div>
                <div class="modal-body">
                    <label>Nama Satuan</label>
                    <input type="text" name="nama_satuan" id="edit_nama_satuan" required>

                    <label>Status</label>
                    <select name="status" id="edit_status" required>
                        <option value="1">Aktif</option>
                        <option value="0">Non-Aktif</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-hapus" onclick="closeModal('modalEditSatuan')">Batal</button>
                    <button type="submit" class="button-edit">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/satuan.js') }}"></script>
</body>
</html>
