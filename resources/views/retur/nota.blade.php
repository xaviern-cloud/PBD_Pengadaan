<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Retur #{{ $header->id_retur }}</title>
    <style>
        body { font-family: sans-serif; background: #15162B; color: #E7EAF5; padding: 32px; }
        .nota-box { max-width: 750px; margin: auto; background: #292D54; padding: 32px; border-radius: 16px; border: 1px solid rgba(255,255,255,0.15); }
        .header-top { display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.15); padding-bottom: 16px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.12); text-align: left; }
        th { background: rgba(15, 17, 36, 0.6); color: #bcc2ff; }
        .btn { display: inline-block; padding: 10px 18px; background: #747ead; color: #fff; text-decoration: none; border-radius: 8px; border: none; cursor: pointer; font-size: 14px; }
        @media print { body { background: #fff; color: #000; } .nota-box { background: #fff; border: 1px solid #000; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="nota-box">
        <div class="header-top">
            <div>
                <h2 style="margin:0;">BUKTI RETUR BARANG KE VENDOR</h2>
                <p style="margin:4px 0 0; color:#bcc2ff;">No. Retur: #RTR-{{ $header->id_retur }}</p>
            </div>
            <div style="text-align: right;">
                <p style="margin:0;"><strong>Tanggal:</strong> {{ $header->tanggal_retur }}</p>
                <p style="margin:4px 0 0;"><strong>Ref. Penerimaan:</strong> #{{ $header->id_penerimaan }} (PO #{{ $header->id_pengadaan }})</p>
            </div>
        </div>

        <p><strong>Vendor Tujuan:</strong> {{ $header->nama_vendor }}</p>
        <p><strong>Diproses Oleh:</strong> {{ $header->diproses_oleh }}</p>

        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Qty Retur</th>
                    <th>Alasan Pengembalian</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details as $d)
                    <tr>
                        <td><strong>{{ $d->id_barang }}</strong></td>
                        <td>{{ $d->nama_barang }} ({{ $d->nama_satuan }})</td>
                        <td>{{ $d->jumlah }}</td>
                        <td>{{ $d->alasan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="no-print" style="margin-top: 24px; display: flex; gap: 12px;">
            <a href="{{ route('retur.index') }}" class="btn">← Kembali ke Retur</a>
            <button onclick="window.print()" class="btn" style="background: #2ecc71; color: #15162B; font-weight: bold;">Cetak Bukti Retur</button>
        </div>
    </div>
</body>
</html>
