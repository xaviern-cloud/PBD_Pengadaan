<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Penerimaan #RCV-{{ $header->id_penerimaan }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 p-8">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-lg border">
        <div class="flex justify-between items-start border-b pb-4">
            <div>
                <h1 class="text-2xl font-bold text-emerald-900">BUKTI PENERIMAAN BARANG (GRN)</h1>
                <p class="text-sm text-gray-500">No. Penerimaan: <b>#RCV-{{ str_pad($header->id_penerimaan, 4, '0', STR_PAD_LEFT) }}</b></p>
                <p class="text-sm text-gray-500">Ref. Purchase Order: <b>#PO-{{ str_pad($header->id_pengadaan, 4, '0', STR_PAD_LEFT) }}</b></p>
            </div>
            <div class="text-right text-sm">
                <p>Tanggal: <b>{{ $header->tanggal_terima }}</b></p>
                <p>Vendor: <b>{{ $header->nama_vendor }}</b></p>
                <p>Penerima: <b>{{ $header->diterima_oleh }}</b></p>
            </div>
        </div>

        <table class="w-full text-sm text-left my-6 border-collapse">
            <thead>
                <tr class="bg-gray-100 border-y">
                    <th class="py-2 px-3">Kode</th>
                    <th class="py-2 px-3">Nama Barang</th>
                    <th class="py-2 px-3 text-center">Jumlah Diterima</th>
                    <th class="py-2 px-3 text-right">Harga Satuan</th>
                    <th class="py-2 px-3 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @php $totalTerima = 0; @endphp
                @foreach($details as $d)
                    @php $totalTerima += $d->sub_total_terima; @endphp
                    <tr>
                        <td class="py-2 px-3 font-mono">{{ $d->id_barang }}</td>
                        <td class="py-2 px-3">{{ $d->nama_barang }}</td>
                        <td class="py-2 px-3 text-center font-bold text-emerald-700">+{{ $d->jumlah_terima }} {{ $d->nama_satuan }}</td>
                        <td class="py-2 px-3 text-right">Rp {{ number_format($d->harga_satuan_terima, 0, ',', '.') }}</td>
                        <td class="py-2 px-3 text-right">Rp {{ number_format($d->sub_total_terima, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right font-bold text-base border-t pt-3">
            Total Nilai Barang Diterima: Rp {{ number_format($totalTerima, 0, ',', '.') }}
        </div>

        <div class="mt-8 flex justify-between print:hidden">
            <a href="{{ route('penerimaan.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm">&larr; Kembali</a>
            <div class="space-x-2">
                <a href="{{ route('kartu-stok.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm">Lihat Mutasi Kartu Stok</a>
                <button onclick="window.print()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm">Cetak Bukti</button>
            </div>
        </div>
    </div>
</body>
</html>
