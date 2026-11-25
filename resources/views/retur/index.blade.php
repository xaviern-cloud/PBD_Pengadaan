<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Retur Barang - ERP</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .panel-box { background: rgba(41, 45, 84, 0.92); border: 1px solid rgba(231, 234, 245, 0.15); border-radius: 16px; padding: 24px; margin-bottom: 24px; width: 100%; }
        .alert-success { background: rgba(46, 204, 113, 0.2); border: 1px solid #2ecc71; color: #8ce99a; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; }
        .alert-error { background: rgba(231, 76, 60, 0.2); border: 1px solid #e74c3c; color: #ff8787; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; }
        .form-select, .form-input { padding: 10px 12px; border-radius: 8px; border: 1px solid rgba(231, 234, 245, 0.25); background: #15162B; color: #fff; font-size: 14px; }
        .tabel-erp { width: 100%; border-collapse: collapse; margin-top: 16px; font-size: 14px; }
        .tabel-erp th, .tabel-erp td { padding: 12px; border-bottom: 1px solid rgba(231, 234, 245, 0.12); text-align: left; }
        .tabel-erp th { background: rgba(15, 17, 36, 0.75); color: #bcc2ff; }
    </style>
</head>
<body>
    <!-- Navigasi Dinamis Sesuai Role -->
    <nav class="top-nav">
        <div class="nav-brand">LOGISTICS ERP ({{ strtoupper(session('role', 'USER')) }})</div>
        <div class="nav-links">
            @if(!str_contains(strtolower(session('role', '')), 'kasir'))
                <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                <a href="{{ route('pengadaan.index') }}" class="nav-link">Pengadaan (PO)</a>
                <a href="{{ route('penerimaan.index') }}" class="nav-link">Penerimaan</a>
            @endif
            <a href="{{ route('penjualan.index') }}" class="nav-link">Penjualan (POS)</a>
            <a href="{{ route('retur.index') }}" class="nav-link active">Retur Barang</a>
            @if(!str_contains(strtolower(session('role', '')), 'kasir'))
                <a href="{{ route('kartu-stok.index') }}" class="nav-link">Kartu Stok</a>
            @endif
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="nav-link" style="color:#ff8787;">Logout</button>
            </form>
        </div>
    </nav>

    <div class="main-wrapper" style="flex-direction: column;">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <!-- Form Pilih Dokumen Penerimaan -->
        <div class="panel-box">
            <h2 style="margin-bottom: 12px;">Ajukan Retur Barang ke Vendor</h2>
            <form method="GET" action="{{ route('retur.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap;">
                <select name="id_penerimaan" class="form-select" style="flex: 1; min-width: 280px;" required>
                    <option value="">-- Pilih Dokumen Penerimaan Barang --</option>
                    @foreach($penerimaanList as $pen)
                        <option value="{{ $pen->id_penerimaan }}" {{ $selectedPenerimaanId == $pen->id_penerimaan ? 'selected' : '' }}>
                            Penerimaan #{{ $pen->id_penerimaan }} (PO #{{ $pen->id_pengadaan }}) - {{ $pen->nama_vendor }} ({{ $pen->tanggal_terima }})
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn-pembelian">Tampilkan Item</button>
            </form>

            @if($selectedPenerimaanId)
                <form method="POST" action="{{ route('retur.store') }}" style="margin-top: 24px;">
                    @csrf
                    <input type="hidden" name="id_penerimaan" value="{{ $selectedPenerimaanId }}">

                    <div style="overflow-x: auto;">
                        <table class="tabel-erp">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Diterima</th>
                                    <th>Sudah Retur</th>
                                    <th>Sisa Bisa Retur</th>
                                    <th>Stok Gudang</th>
                                    <th>Qty Retur</th>
                                    <th>Alasan Retur</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($detailItems as $item)
                                    <tr>
                                        <td><strong>{{ $item->id_barang }}</strong></td>
                                        <td>{{ $item->nama_barang }} ({{ $item->nama_satuan }})</td>
                                        <td>{{ $item->jumlah_terima }}</td>
                                        <td>{{ $item->sudah_diretur }}</td>
                                        <td><strong>{{ $item->sisa_bisa_retur }}</strong></td>
                                        <td>{{ $item->stok_gudang }}</td>
                                        <td>
                                            <input type="hidden" name="items[{{ $item->id_detail_penerimaan }}][id_barang]" value="{{ $item->id_barang }}">
                                            <input type="hidden" name="items[{{ $item->id_detail_penerimaan }}][sisa_max]" value="{{ min($item->sisa_bisa_retur, $item->stok_gudang) }}">
                                            <input type="number" name="items[{{ $item->id_detail_penerimaan }}][jumlah_retur]"
                                                   min="0" max="{{ min($item->sisa_bisa_retur, $item->stok_gudang) }}" value="0"
                                                   class="form-input" style="width: 85px;">
                                        </td>
                                        <td>
                                            <input type="text" name="items[{{ $item->id_detail_penerimaan }}][alasan]"
                                                   placeholder="Contoh: Barang cacat / rusak" class="form-input" style="width: 220px;">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" style="text-align: center; padding: 20px;">Seluruh item pada penerimaan ini sudah diretur atau habis.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(count($detailItems) > 0)
                        <div style="margin-top: 16px; text-align: right;">
                            <button type="submit" class="btn-pembelian">Proses Retur & Potong Stok</button>
                        </div>
                    @endif
                </form>
            @endif
        </div>

        <!-- Tabel Riwayat Retur -->
        <div class="panel-box">
            <h2 style="margin-bottom: 12px;">Riwayat Transaksi Retur</h2>
            <div style="overflow-x: auto;">
                <table class="tabel-erp">
                    <thead>
                        <tr>
                            <th>No. Retur</th>
                            <th>Tanggal</th>
                            <th>Ref. Penerimaan</th>
                            <th>Vendor</th>
                            <th>Diproses Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $r)
                            <tr>
                                <td><strong>#RTR-{{ $r->id_retur }}</strong></td>
                                <td>{{ $r->tanggal_retur }}</td>
                                <td>Penerimaan #{{ $r->id_penerimaan }}</td>
                                <td>{{ $r->nama_vendor }}</td>
                                <td>{{ $r->diproses_oleh }}</td>
                                <td>
                                    <a href="{{ route('retur.nota', $r->id_retur) }}" class="card-link">Lihat Nota →</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 20px;">Belum ada transaksi retur.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
