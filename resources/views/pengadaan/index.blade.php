<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengadaan Barang (PO)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#15162B] text-white min-h-screen p-6">
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header & Navigasi Cepat -->
        <div class="flex flex-wrap justify-between items-center bg-[#292D54] p-4 rounded-xl border border-indigo-500/30">
            <div>
                <h1 class="text-xl font-bold text-indigo-300">Transaksi Pengadaan (Purchase Order)</h1>
                <p class="text-xs text-gray-300">Login sebagai: <b>{{ session('username', 'Admin') }}</b> ({{ session('role', 'Admin') }})</p>
            </div>
            <div class="flex gap-2 text-sm">
                <a href="{{ route('dashboard') }}" class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 rounded-lg">Dashboard</a>
                <a href="{{ route('penerimaan.index') }}" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-500 rounded-lg">Penerimaan &rarr;</a>
                <a href="{{ route('kartu-stok.index') }}" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 rounded-lg">Kartu Stok</a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-500 text-emerald-200 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500 text-red-200 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Tambah Item ke Draft Session -->
            <div class="bg-[#292D54] p-5 rounded-xl border border-indigo-500/30">
                <h2 class="font-semibold text-indigo-300 mb-4">1. Tambah Item ke Keranjang PO</h2>
                <form action="{{ route('pengadaan.cart.add') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs text-gray-300 mb-1">Pilih Barang</label>
                        <select name="id_barang" id="selectBarangPO" onchange="setHargaDefault()" required
                                class="w-full px-3 py-2 rounded-lg bg-[#15162B] border border-indigo-400/30 text-sm">
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barangs as $b)
                                <option value="{{ $b->id_barang }}" data-harga="{{ $b->harga }}">
                                    [{{ $b->id_barang }}] {{ $b->nama_barang }} ({{ $b->nama_satuan }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-300 mb-1">Harga Beli Satuan (Rp)</label>
                        <input type="number" name="harga_satuan" id="hargaSatuanPO" min="1" required
                               class="w-full px-3 py-2 rounded-lg bg-[#15162B] border border-indigo-400/30 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-300 mb-1">Jumlah Pesan</label>
                        <input type="number" name="jumlah" value="10" min="1" required
                               class="w-full px-3 py-2 rounded-lg bg-[#15162B] border border-indigo-400/30 text-sm">
                    </div>
                    <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-500 rounded-lg text-sm font-semibold">
                        + Tambah ke Draft PO
                    </button>
                </form>
            </div>

            <!-- Keranjang Session & Submit PO -->
            <div class="lg:col-span-2 bg-[#292D54] p-5 rounded-xl border border-indigo-500/30 flex flex-col justify-between">
                <div>
                    <h2 class="font-semibold text-indigo-300 mb-4">2. Daftar Item Draft PO (Session)</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-300 uppercase bg-[#15162B]">
                                <tr>
                                    <th class="px-3 py-2">Kode</th>
                                    <th class="px-3 py-2">Nama Barang</th>
                                    <th class="px-3 py-2 text-right">Harga</th>
                                    <th class="px-3 py-2 text-center">Qty</th>
                                    <th class="px-3 py-2 text-right">Subtotal</th>
                                    <th class="px-3 py-2 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-indigo-400/20">
                                @php $subtotal = 0; @endphp
                                @forelse($cart as $item)
                                    @php $subtotal += $item['sub_total']; @endphp
                                    <tr>
                                        <td class="px-3 py-2 font-mono text-indigo-300">{{ $item['id_barang'] }}</td>
                                        <td class="px-3 py-2">{{ $item['nama_barang'] }}</td>
                                        <td class="px-3 py-2 text-right">Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }}</td>
                                        <td class="px-3 py-2 text-center">{{ $item['jumlah'] }} {{ $item['nama_satuan'] }}</td>
                                        <td class="px-3 py-2 text-right font-semibold">Rp {{ number_format($item['sub_total'], 0, ',', '.') }}</td>
                                        <td class="px-3 py-2 text-center">
                                            <form action="{{ route('pengadaan.cart.remove', $item['id_barang']) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="text-red-400 hover:text-red-300 text-xs">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-3 py-6 text-center text-gray-400">Belum ada barang di keranjang PO.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @php
                    $ppn = round($subtotal * 0.11);
                    $total = $subtotal + $ppn;
                @endphp

                <form action="{{ route('pengadaan.store') }}" method="POST" class="mt-4 pt-4 border-t border-indigo-400/20 flex flex-wrap items-end justify-between gap-4">
                    @csrf
                    <div class="w-full md:w-1/2">
                        <label class="block text-xs text-gray-300 mb-1">Pilih Vendor Tujuan</label>
                        <select name="id_vendor" required class="w-full px-3 py-2 rounded-lg bg-[#15162B] border border-indigo-400/30 text-sm">
                            <option value="">-- Pilih Vendor --</option>
                            @foreach($vendors as $v)
                                <option value="{{ $v->id_vendor }}">{{ $v->nama_vendor }} ({{ $v->badan_hukum }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-right text-sm space-y-1">
                        <p class="text-gray-300">Subtotal: <b>Rp {{ number_format($subtotal, 0, ',', '.') }}</b></p>
                        <p class="text-gray-300">PPN (11%): <b>Rp {{ number_format($ppn, 0, ',', '.') }}</b></p>
                        <p class="text-lg font-bold text-emerald-400">Total PO: Rp {{ number_format($total, 0, ',', '.') }}</p>
                        <button type="submit" class="mt-2 px-5 py-2 bg-emerald-600 hover:bg-emerald-500 rounded-lg font-semibold">
                            Simpan PO & Lihat Nota
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Riwayat Pengadaan -->
        <div class="bg-[#292D54] p-5 rounded-xl border border-indigo-500/30">
            <h2 class="font-semibold text-indigo-300 mb-4">Riwayat Dokumen Pengadaan (PO)</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-300 uppercase bg-[#15162B]">
                        <tr>
                            <th class="px-3 py-2">No. PO</th>
                            <th class="px-3 py-2">Tanggal</th>
                            <th class="px-3 py-2">Vendor</th>
                            <th class="px-3 py-2">Dibuat Oleh</th>
                            <th class="px-3 py-2 text-right">Total Nilai</th>
                            <th class="px-3 py-2 text-center">Status</th>
                            <th class="px-3 py-2 text-center">Nota</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-indigo-400/20">
                        @foreach($pengadaan as $p)
                            <tr>
                                <td class="px-3 py-2 font-mono">#PO-{{ str_pad($p->id_pengadaan, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-3 py-2">{{ $p->tanggal }}</td>
                                <td class="px-3 py-2">{{ $p->nama_vendor }}</td>
                                <td class="px-3 py-2">{{ $p->dibuat_oleh }}</td>
                                <td class="px-3 py-2 text-right font-semibold">Rp {{ number_format($p->total_nilai, 0, ',', '.') }}</td>
                                <td class="px-3 py-2 text-center">
                                    <span class="px-2 py-0.5 rounded text-xs {{ $p->kode_status == 'F' ? 'bg-emerald-500/20 text-emerald-300' : ($p->kode_status == 'S' ? 'bg-amber-500/20 text-amber-300' : 'bg-indigo-500/20 text-indigo-300') }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <a href="{{ route('pengadaan.nota', $p->id_pengadaan) }}" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-500 rounded text-xs">
                                        Lihat Nota
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function setHargaDefault() {
            const sel = document.getElementById('selectBarangPO');
            const harga = sel.options[sel.selectedIndex]?.getAttribute('data-harga') || '';
            document.getElementById('hargaSatuanPO').value = harga;
        }
    </script>
</body>
</html>
