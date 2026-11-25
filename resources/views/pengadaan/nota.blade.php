<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Pengadaan #PO-{{ $header->id_pengadaan }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 p-8">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-lg border">
        <div class="flex justify-between items-start border-b pb-4">
            <div>
                <h1 class="text-2xl font-bold text-indigo-900">PURCHASE ORDER (NOTA PENGADAAN)</h1>
                <p class="text-sm text-gray-500">No. Dokumen: <b>#PO-{{ str_pad($header->id_pengadaan, 4, '0', STR_PAD_LEFT) }}</b></p>
                <p class="text-sm text-gray-500">Tanggal: {{ $header->tanggal }}</p>
            </div>
            <div class="text-right">
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800">
                    Status: {{ $header->status }}
                </span>
                <p class="text-sm mt-2">Vendor: <b>{{ $header->nama_vendor }}</b></p>
                <p class="text-xs text-gray-500">Petugas: {{ $header->dibuat_oleh }}</p>
            </div>
        </div>

        <table class="w-full text-sm text-left my-6 border-collapse">
            <thead>
                <tr class="bg-gray-100 border-y">
                    <th class="py-2 px-3">Kode</th>
                    <th class="py-2 px-3">Nama Barang</th>
                    <th class="py-2 px-3 text-right">Harga Satuan</th>
                    <th class="py-2 px-3 text-center">Jumlah</th>
                    <th class="py-2 px-3 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($details as $d)
                    <tr>
                        <td class="py-2 px-3 font-mono">{{ $d->id_barang }}</td>
                        <td class="py-2 px-3">{{ $d->nama_barang }}</td>
                        <td class="py-2 px-3 text-right">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                        <td class="py-2 px-3 text-center">{{ $d->jumlah }} {{ $d->nama_satuan }}</td>
                        <td class="py-2 px-3 text-right font-semibold">Rp {{ number_format($d->sub_total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex justify-end border-t pt-4">
            <div class="w-64 space-y-1 text-sm">
                <div class="flex justify-between"><span>Subtotal:</span><b>Rp {{ number_format($header->subtotal, 0, ',', '.') }}</b></div>
                <div class="flex justify-between"><span>PPN (11%):</span><b>Rp {{ number_format($header->ppn, 0, ',', '.') }}</b></div>
                <div class="flex justify-between text-base font-bold text-indigo-900 border-t pt-1">
                    <span>Total Nilai:</span><span>Rp {{ number_format($header->total_nilai, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-between print:hidden">
            <a href="{{ route('pengadaan.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm">&larr; Kembali</a>
            <div class="space-x-2">
                <a href="{{ route('penerimaan.index', ['id_pengadaan' => $header->id_pengadaan]) }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm">Proses Penerimaan &rarr;</a>
                <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm">Cetak Nota</button>
            </div>
        </div>
    </div>
</body>
</html>
