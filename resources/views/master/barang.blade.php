<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Barang - ERP Pengadaan & Penjualan</title>
    <link rel="stylesheet" href="{{ asset('css/barang.css') }}">
</head>
<body>
    <div class="container-main">
        <h1>Master Data Barang</h1>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <button class="button-tambah-vendor" onclick="openModal('modalTambahBarang')">+ Tambah Barang</button>

        <div style="overflow-x: auto; width: 100%;">
            <table class="tabel-vendor">
                <thead>
                    <tr>
                        <th>ID BARANG</th>
                        <th>NAMA BARANG</th>
                        <th>JENIS</th>
                        <th>SATUAN</th>
                        <th>HARGA</th>
                        <th>
                            STATUS
                            <form method="GET" action="{{ route('master.barang') }}" style="display: inline-block; margin-left: 8px;">
                                <select name="status" class="dropdown-status" onchange="this.form.submit()">
                                    <option value="All" {{ ($status ?? 'All') == 'All' ? 'selected' : '' }}>Semua</option>
                                    <option value="Aktif" {{ ($status ?? '') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Non-Aktif" {{ ($status ?? '') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                            </form>
                        </th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barang as $b)
                    <tr>
                        <td><strong>{{ $b->id_barang }}</strong></td>
                        <td>{{ $b->nama_barang }}</td>
                        <td>{{ $b->jenis_barang }}</td>
                        <td>{{ $b->nama_satuan }}</td>
                        <td>Rp {{ number_format($b->harga, 0, ',', '.') }}</td>
                        <td>
                            <!-- Null-safety operator untuk mencegah error Undefined property $status -->
                            <span class="{{ ($b->status ?? '') == 'Aktif' ? 'status-aktif' : 'status-nonaktif' }}">
                                {{ $b->status ?? 'Non-Aktif' }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <button type="button" class="button-edit"
                                        onclick="editBarang('{{ $b->id_barang }}', '{{ $b->jenis_barang }}', '{{ addslashes($b->nama_barang) }}', '{{ $b->id_satuan ?? '' }}', '{{ $b->harga }}', '{{ $b->status ?? '' }}')">
                                    Edit
                                </button>

                                <form action="{{ route('master.barang.destroy', $b->id_barang) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete('{{ $b->nama_barang }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button-hapus">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #fff; padding: 20px;">Data barang tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <a href="/">
        <button class=button-penjualan type="button">Dashboard</button>
        </a>
    </div>

    <!-- MODAL TAMBAH BARANG -->
    <div class="modal-overlay" id="modalTambahBarang">
        <div class="modal-box">
            <form action="{{ route('master.barang.store') }}" method="POST">
                @csrf
                <div class="modal-header modal-header-primary">
                    <strong>Tambah Barang Baru</strong>
                    <button type="button" class="modal-close" onclick="closeModal('modalTambahBarang')">&times;</button>
                </div>
                <div class="modal-body">
                    <label>Jenis Barang</label>
                    <select name="jenis_barang" required>
                        <option value="BB">Bahan Baku (BB)</option>
                        <option value="BSJ">Barang Setengah Jadi (BSJ)</option>
                        <option value="BJ">Barang Jadi (BJ)</option>
                        <option value="BO">Barang Operasional (BO)</option>
                        <option value="BK">Barang Konsumen (BK)</option>
                    </select>

                    <label>Nama Barang</label>
                    <input type="text" name="nama_barang" required placeholder="Contoh: Tepung Terigu">

                    <label>Satuan</label>
                    <select name="id_satuan" required>
                        @foreach($satuanList ?? [] as $s)
                            <option value="{{ $s->id_satuan }}">{{ $s->nama_satuan }}</option>
                        @endforeach
                    </select>

                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" required placeholder="Contoh: 15000">
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-hapus" style="background-color: #6c757d;" onclick="closeModal('modalTambahBarang')">Batal</button>
                    <button type="submit" class="button-tambah-vendor" style="background-color: #0d6efd;">Simpan Barang</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT BARANG -->
    <div class="modal-overlay" id="modalEditBarang">
        <div class="modal-box">
            <form id="formEditBarang" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header modal-header-warning">
                    <strong>Edit Data Barang</strong>
                    <button type="button" class="modal-close" onclick="closeModal('modalEditBarang')">&times;</button>
                </div>
                <div class="modal-body">
                    <label>Jenis Barang</label>
                    <select name="jenis_barang" id="edit_jenis_barang" required>
                        <option value="BB">Bahan Baku (BB)</option>
                        <option value="BSJ">Barang Setengah Jadi (BSJ)</option>
                        <option value="BJ">Barang Jadi (BJ)</option>
                        <option value="BO">Barang Operasional (BO)</option>
                        <option value="BK">Barang Konsumen (BK)</option>
                    </select>

                    <label>Nama Barang</label>
                    <input type="text" name="nama_barang" id="edit_nama_barang" required>

                    <label>Satuan</label>
                    <select name="id_satuan" id="edit_id_satuan" required>
                        @foreach($satuanList ?? [] as $s)
                            <option value="{{ $s->id_satuan }}">{{ $s->nama_satuan }}</option>
                        @endforeach
                    </select>

                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" id="edit_harga" required>

                    <label>Status</label>
                    <select name="status" id="edit_status" required>
                        <option value="1">Aktif</option>
                        <option value="0">Non-Aktif</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-hapus" style="background-color: #6c757d;" onclick="closeModal('modalEditBarang')">Batal</button>
                    <button type="submit" class="button-edit" style="background-color: #ffc107; color: black;">Update Barang</button>
                </div>
            </form>
        </div>
    </div>


    <script src="{{ asset('js/barang.js') }}"></script>
</body>
</html>
