<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Vendor - ERP Pengadaan & Penjualan</title>
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
</head>
<body>
    <div class=container-main>
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif
        <h1>Data Master Vendor</h1>
        
        <button class="button-tambah-vendor" onclick="openModal('modalTambahVendor')">Tambah Vendor </button>      

            <div class="card-body">
                <div class="tabel-vendor">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 70px;">ID</th>
                                <th>NAMA VENDOR</th>
                                <th>BADAN HUKUM</th>
                                <th>
                                    <div class="th-Status">
                                        <span></span>
                                        <form method="GET" action="{{ route('master.vendor') }}">
                                            <select name="Status" class="dropdown-status" onchange="this.form.submit()">
                                                <option value="All" {{ ($Status ?? 'All') == 'All' ? 'selected' : '' }}>Semua Status</option>
                                                <option value="Aktif" {{ ($Status ?? '') == 'Aktif' ? 'selected' : '' }}>Status Aktif</option>
                                                <option value="Non-Aktif" {{ ($Status ?? '') == 'Non-Aktif' ? 'selected' : '' }}>Status Non-Aktif</option>
                                            </select>
                                        </form>
                                    </div>
                                </th>
                                <th style="width: 140px; text-align: center;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vendor as $v)
                            <tr>
                                <td>{{ $v->id_vendor }}</td>
                                <td>{{ $v->nama_vendor }}</strong></td>
                                <td>{{ $v->badan_hukum }}</td>
                                <td>
                                    <span class="badge {{ $v->Status == 'Aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">
                                        {{ $v->Status }}
                                    </span>
                                </td>
                                <td>
                                    <button class="button-edit" 
                                            onclick="editVendor({{ $v->id_vendor }}, '{{ addslashes($v->nama_vendor) }}', '{{ $v->badan_hukum }}', '{{ $v->Status }}')">
                                        Edit
                                    </button>

                                    <form action="{{ route('master.vendor.destroy', $v->id_vendor) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete('{{ $v->nama_vendor }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button-hapus">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: #777; padding: 20px;">Data vendor tidak ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <a href="/">
                <button class=button-penjualan type="button">Dashboard</button>
            </a>
        </div>
    </div>
    </div>

    <!-- MODAL TAMBAH VENDOR -->
    <div class="modal-overlay" id="modalTambahVendor">
        <div class="modal-box">
            <form action="{{ route('master.vendor.store') }}" method="POST">
                @csrf
                <div class="modal-header modal-header-primary">
                    <strong>Tambah Vendor Baru</strong>
                    <button type="button" class="modal-close" onclick="closeModal('modalTambahVendor')">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Vendor</label>
                        <input type="text" name="nama_vendor" class="form-control" required placeholder="Contoh: PT Sumber Makmur">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Badan Hukum</label>
                        <select name="badan_hukum" class="form-select" required>
                            <option value="P">PT (Perseroan Terbatas)</option>
                            <option value="C">CV (Commanditaire Vennootschap)</option>
                            <option value="U">UD (Usaha Dagang)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modalTambahVendor')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Vendor</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT VENDOR -->
    <div class="modal-overlay" id="modalEditVendor">
        <div class="modal-box">
            <form id="formEditVendor" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header modal-header-warning">
                    <strong>Edit Data Vendor</strong>
                    <button type="button" class="modal-close" onclick="closeModal('modalEditVendor')">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Vendor</label>
                        <input type="text" name="nama_vendor" id="edit_nama_vendor" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Badan Hukum</label>
                        <select name="badan_hukum" id="edit_badan_hukum" class="form-select" required>
                            <option value="P">PT (Perseroan Terbatas)</option>
                            <option value="C">CV (Commanditaire Vennootschap)</option>
                            <option value="U">UD (Usaha Dagang)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="Status" id="edit_Status" class="form-select" required>
                            <option value="A">Aktif</option>
                            <option value="N">Non-Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditVendor')">Batal</button>
                    <button type="submit" class="btn btn-warning">Update Vendor</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/vendor.js') }}"></script>
</body>
</html>