<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kartu Stok</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#15162B] text-white min-h-screen p-6">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex flex-wrap justify-between items-center bg-[#292D54] p-4 rounded-xl border border-indigo-500/30">
            <div>
                <h1 class="text-xl font-bold text-indigo-300">Laporan Mutasi Kartu Stok (Otomatis via Trigger)</h1>
                <p class="text-xs text-gray-300">Setiap Penerimaan ('M'), Penjualan ('K'), dan Retur ('R') tercatat otomatis oleh Trigger MySQL</p>
            </div>
            <div class="flex gap-2 text-sm">
                <a href="{{ route('dashboard') }}" class="px-3 py-1.5 bg-slate-700 rounded-lg">Dashboard</a>
                <a href="{{ route('pengadaan.index') }}" class="px-3 py-1.5 bg-indigo-600 rounded-lg">Pengadaan</a>
                <a href="{{ route('penerimaan.index') }}" class="px-3 py-1.5 bg-amber-600 rounded-lg">Penerimaan</a>
                <a href="{{ route('penjualan.index') }}" class="px-3 py-1.5 bg-emerald-600 rounded-lg">Penjualan</a>
            </div>
        </div>

        <!-- Filter Jenis Mutasi -->
        <div class="flex gap-2 text-xs">
            <a href="{{ route('kartu-stok.index', ['jenis' => 'All']) }}" class="px-3 py-1.5 rounded-lg {{ $jenis == 'All' ? 'bg-indigo-600' : 'bg-[#292D54]' }}">Semua Mutasi</a>
            <a href="{{ route('kartu-stok.index', ['jenis' => 'M']) }}" class="px-3 py-1.5 rounded-lg {{ $jenis == 'M' ? 'bg-emerald-600' : 'bg-[#292D54]' }}">Masuk (Penerimaan)</a>
            <a href="{{ route('kartu-stok.index', ['jenis' => 'K']) }}" class="px-3 py-1.5 rounded-lg {{ $jenis == 'K' ? 'bg-red-600' : 'bg-[#292D54]' }}">Keluar (Penjualan)</a>
        </div>

        <div class="bg-[#292D54] p-5 rounded-xl border border-indigo-500/30 overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-300 uppercase bg-[#15162B]">
                    <tr>
                        <th class="px-3 py-2">ID Log</th>
                        <th class="px-3 py-2">Waktu</th>
                        <th class="px-3 py-2">Kode</th>
                        <th class="px-3 py-2">Nama Barang</th>
                        <th class="px-3 py-2">Keterangan Mutasi</th>
                        <th class="px-3 py-2 text-center">Masuk</th>
                        <th class="px-3 py-2 text-center">Keluar</th>
                        <th class="px-3 py-2 text-center">Saldo Stok</th>
                        <th class="px-3 py-2 text-center">Ref ID</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-indigo-400/20">
                    @foreach($kartuStok as $ks)
                        <tr>
                            <td class="px-3 py-2 font-mono text-xs">#{{ $ks->id_kartu_stok }}</td>
                            <td class="px-3 py-2 text-xs">{{ $ks->tanggal }}</td>
                            <td class="px-3 py-2 font-mono text-indigo-300">{{ $ks->id_barang }}</td>
                            <td class="px-3 py-2">{{ $ks->nama_barang }}</td>
                            <td class="px-3 py-2">
                                <span class="px-2 py-0.5 rounded text-xs {{ str_contains($ks->jenis_transaksi, 'Masuk') ? 'bg-emerald-500/20 text-emerald-300' : 'bg-red-500/20 text-red-300' }}">
                                    {{ $ks->jenis_transaksi }}
                                </span>
                            </td>
                            <td class="px-3 py-2 text-center text-emerald-400 font-semibold">{{ $ks->masuk > 0 ? '+'.$ks->masuk : '-' }}</td>
                            <td class="px-3 py-2 text-center text-red-400 font-semibold">{{ $ks->keluar > 0 ? '-'.$ks->keluar : '-' }}</td>
                            <td class="px-3 py-2 text-center font-bold">{{ $ks->stok_akhir }} {{ $ks->nama_satuan }}</td>
                            <td class="px-3 py-2 text-center font-mono text-xs">#{{ $ks->ref_id_transaksi }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
