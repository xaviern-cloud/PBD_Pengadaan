<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Penerimaan Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#15162B] text-white min-h-screen p-6">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex flex-wrap justify-between items-center bg-[#292D54] p-4 rounded-xl border border-indigo-500/30">
            <div>
                <h1 class="text-xl font-bold text-amber-300">Penerimaan Barang dari Vendor</h1>
                <p class="text-xs text-gray-300">Otomatis menambah stok masuk ('M') ke Kartu Stok & update status PO ('S'/'F')</p>
            </div>
            <div class="flex gap-2 text-sm">
                <a href="{{ route('pengadaan.index') }}" class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 rounded-lg">&larr; Pengadaan</a>
                <a href="{{ route('kartu-stok.index') }}" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 rounded-lg">Cek Kartu Stok</a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-500 text-emerald-200 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500 text-red-200 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
        @endif

        <!-- Pilih PO yang Belum Selesai -->
        <div class="bg-[#292D54] p-5 rounded-xl border border-indigo-500/30">
            <form action="{{ route('penerimaan.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="w-full md:w-1/2">
                    <label class="block text-xs text-gray-300 mb-1">Pilih Dokumen Pengadaan (PO Pending / Sebagian)</label>
                    <select name="id_pengadaan" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-lg bg-[#15162B] border border-indigo-400/30 text-sm">
                        <option value="">-- Pilih Nomor PO --</option>
                        @foreach($poList as $po)
                            <option value="{{ $po->id_pengadaan }}" {{ $selectedPoId == $po->id_pengadaan ? 'selected' : '' }}>
                                #PO-{{ str_pad($po->id_pengadaan, 4, '0', STR_PAD_LEFT) }} - {{ $po->nama_vendor }} ({{ $po->status }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

            @if($selectedPoId && count($remainingItems) > 0)
                <form action="{{ route('penerimaan.store') }}" method="POST" class="mt-6">
                    @csrf
                    <input type="hidden" name="id_pengadaan" value="{{ $selectedPoId }}">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-300 uppercase bg-[#15162B]">
                            <tr>
                                <th class="px-3 py-2">Kode</th>
                                <th class="px-3 py-2">Nama Barang</th>
                                <th class="px-3 py-2 text-center">Pesan</th>
                                <th class="px-3 py-2 text-center">Sudah Terima</th>
                                <th class="px-3 py-2 text-center">Sisa</th>
                                <th class="px-3 py-2 text-center">Jumlah Terima Sekarang</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-indigo-400/20">
                            @foreach($remainingItems as $item)
                                <tr>
                                    <td class="px-3 py-2 font-mono text-indigo-300">{{ $item->id_barang }}</td>
                                    <td class="px-3 py-2">{{ $item->nama_barang }}</td>
                                    <td class="px-3 py-2 text-center">{{ $item->jumlah_pesan }}</td>
                                    <td class="px-3 py-2 text-center">{{ $item->sudah_diterima }}</td>
                                    <td class="px-3 py-2 text-center font-bold text-amber-300">{{ $item->sisa_belum_diterima }}</td>
                                    <td class="px-3 py-2 text-center">
                                        <input type="hidden" name="items[{{ $item->id_barang }}][sisa_max]" value="{{ $item->sisa_belum_diterima }}">
                                        <input type="hidden" name="items[{{ $item->id_barang }}][harga_satuan]" value="{{ $item->harga_satuan }}">
                                        <input type="number" name="items[{{ $item->id_barang }}][jumlah_terima]"
                                               value="{{ $item->sisa_belum_diterima }}" min="0" max="{{ $item->sisa_belum_diterima }}"
                                               class="w-24 px-2 py-1 rounded bg-[#15162B] border border-indigo-400/40 text-center">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4 text-right">
                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 rounded-lg font-semibold text-sm">
                            Simpan Penerimaan & Cetak Nota
                        </button>
                    </div>
                </form>
            @endif
        </div>

        <!-- Riwayat Penerimaan -->
        <div class="bg-[#292D54] p-5 rounded-xl border border-indigo-500/30">
            <h2 class="font-semibold text-indigo-300 mb-4">Riwayat Bukti Penerimaan Barang</h2>
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-300 uppercase bg-[#15162B]">
                    <tr>
                        <th class="px-3 py-2">No. Terima</th>
                        <th class="px-3 py-2">Ref. PO</th>
                        <th class="px-3 py-2">Tanggal Terima</th>
                        <th class="px-3 py-2">Vendor</th>
                        <th class="px-3 py-2">Diterima Oleh</th>
                        <th class="px-3 py-2 text-center">Nota</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-indigo-400/20">
                    @foreach($riwayat as $r)
                        <tr>
                            <td class="px-3 py-2 font-mono">#RCV-{{ str_pad($r->id_penerimaan, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-3 py-2 font-mono">#PO-{{ str_pad($r->id_pengadaan, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-3 py-2">{{ $r->tanggal_terima }}</td>
                            <td class="px-3 py-2">{{ $r->nama_vendor }}</td>
                            <td class="px-3 py-2">{{ $r->diterima_oleh }}</td>
                            <td class="px-3 py-2 text-center">
                                <a href="{{ route('penerimaan.nota', $r->id_penerimaan) }}" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-500 rounded text-xs">Lihat Nota</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
