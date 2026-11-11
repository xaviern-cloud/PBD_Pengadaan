<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Vendor - ERP</title>
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
</head>
<body>
    <div class="container-main">
        <h1>Master Data Vendor (v_vendor)</h1>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <button class="button-tambah-vendor" onclick="openModal('modalTambahVendor')">+ Tambah Vendor</button>

        <div style="overflow-x: auto; width: 100%;">
            <table class="tabel-vendor">
                <thead>
                    <tr>
                        <th>ID VENDOR</th>
                        <th>NAMA VENDOR</th>
                        <th>BADAN HUKUM</th>
                        <th>
                            STATUS
                            <form method="GET" action="{{ route('master.vendor') }}" style="display: inline-block; margin-left: 8px;">
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
                    @forelse($vendor as $v)
                    <tr>
                        <td><strong>{{ $v->id_vendor }}</strong></td>
                        <td>{{ $v->nama_vendor }}</td>
                        <td>{{ $v->badan_hukum }}</td>
                        <td>
                            <span class="badge {{ ($v->status ?? $v->STATUS ?? '') == 'Aktif' ? 'status-aktif' : 'status-nonaktif' }}">
                                {{ $v->status ?? $v->STATUS ?? 'Non-Aktif' }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <button type="button" class="button-edit"
                                        onclick="editVendor({{ $v->id_vendor }}, '{{ addslashes($v->nama_vendor) }}', '{{ $v->badan_hukum }}', '{{ $v->status ?? '' }}')">
                                    Edit
                                </button>
                                <form action="{{ route('master.vendor.destroy', $v->id_vendor) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete('{{ $v->nama_vendor }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button-hapus">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px;">Data vendor kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal-overlay" id="modalTambahVendor">
        <div class="modal-box">
            <form action="{{ route('master.vendor.store') }}" method="POST">
                @csrf
                <div class="modal-header modal-header-primary">
                    <span>Tambah Vendor</span>
                    <button type="button" class="modal-close" onclick="closeModal('modalTambahVendor')">&times;</button>
                </div>
                <div class="modal-body">
                    <label>Nama Vendor</label>
                    <input type="text" name="nama_vendor" required placeholder="Contoh: PT Sumber Makmur">

                    <label>Badan Hukum</label>
                    <select name="badan_hukum" required>
                        <option value="P">PT (Perseroan Terbatas)</option>
                        <option value="C">CV (Commanditaire Vennootschap)</option>
                        <option value="U">UD (Usaha Dagang)</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-hapus" onclick="closeModal('modalTambahVendor')">Batal</button>
                    <button type="submit" class="button-edit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal-overlay" id="modalEditVendor">
        <div class="modal-box">
            <form id="formEditVendor" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header modal-header-warning">
                    <span>Edit Vendor</span>
                    <button type="button" class="modal-close" onclick="closeModal('modalEditVendor')">&times;</button>
                </div>
                <div class="modal-body">
                    <label>Nama Vendor</label>
                    <input type="text" name="nama_vendor" id="edit_nama_vendor" required>

                    <label>Badan Hukum</label>
                    <select name="badan_hukum" id="edit_badan_hukum" required>
                        <option value="P">PT (Perseroan Terbatas)</option>
                        <option value="C">CV (Commanditaire Vennootschap)</option>
                        <option value="U">UD (Usaha Dagang)</option>
                    </select>

                    <label>Status</label>
                    <select name="status" id="edit_status" required>
                        <option value="A">Aktif</option>
                        <option value="N">Non-Aktif</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-hapus" onclick="closeModal('modalEditVendor')">Batal</button>
                    <button type="submit" class="button-edit">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/vendor.js') }}"></script>
</body>
</html>
