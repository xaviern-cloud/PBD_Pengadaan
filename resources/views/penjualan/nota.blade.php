<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Penjualan #INV-{{ $header->id_penjualan }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 p-8">
    <div class="max-w-md mx-auto bg-white p-6 rounded-xl shadow-lg border font-mono text-sm">
        <div class="text-center border-b border-dashed pb-4">
            <h1 class="text-lg font-bold">STRUK NOTA PENJUALAN</h1>
            <p class="text-xs text-gray-500">ERP Logistik & Retail Store</p>
            <p class="text-xs mt-1">No: <b>#INV-{{ str_pad($header->id_penjualan, 4, '0', STR_PAD_LEFT) }}</b> | Kasir: {{ $header->kasir }}</p>
            <p class="text-xs text-gray-500">{{ $header->tanggal }}</p>
        </div>

        <div class="my-4 space-y-2 border-b border-dashed pb-4">
            @foreach($details as $d)
                <div>
                    <div class="flex justify-between font-semibold">
                        <span>{{ $d->nama_barang }}</span>
                        <span>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $d->jumlah }} {{ $d->nama_satuan }} x Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="space-y-1 text-xs">
            <div class="flex justify-between"><span>Subtotal:</span><span>Rp {{ number_format($header->subtotal_nilai, 0, ',', '.') }}</span></div>
            <div class="flex justify-between"><span>PPN (11%):</span><span>Rp {{ number_format($header->ppn, 0, ',', '.') }}</span></div>
            <div class="flex justify-between text-base font-bold border-t pt-2 mt-2">
                <span>GRAND TOTAL:</span><span>Rp {{ number_format($header->total_nilai, 0, ',', '.') }}</span>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">Terima kasih atas kunjungan Anda!</p>

        <div class="mt-6 flex justify-between print:hidden font-sans">
            <a href="{{ route('penjualan.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-xs">&larr; Kasir Baru</a>
            <button onclick="window.print()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-xs">Cetak Struk</button>
        </div>
    </div>
</body>
</html>
