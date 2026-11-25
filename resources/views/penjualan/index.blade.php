<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kasir POS Penjualan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#15162B] text-white min-h-screen p-6">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex flex-wrap justify-between items-center bg-[#292D54] p-4 rounded-xl border border-indigo-500/30">
            <div>
                <h1 class="text-xl font-bold text-emerald-300">Point of Sale (POS) - Kasir Penjualan</h1>
                <p class="text-xs text-gray-300">Harga Jual dihitung otomatis oleh SQL Function <code>get_harga_jual()</code> & Stok oleh <code>fn_cek_stok()</code></p>
            </div>
            <div class="flex items-center gap-3 text-sm">
                <!-- Pilih Margin Aktif -->
                <form action="{{ route('penjualan.index') }}" method="GET" class="flex items-center gap-2">
                    <label class="text-xs text-gray-300">Margin Aktif:</label>
                    <select name="idmargin" onchange="this.form.submit()" class="px-2 py-1 rounded bg-[#15162B] border border-indigo-400/40 text-xs">
                        @foreach($margins as $m)
                            <option value="{{ $m->id_margin_penjualan }}" {{ $selectedMargin == $m->id_margin_penjualan ? 'selected' : '' }}>
                                {{ $m->persen }}%
                            </option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('kartu-stok.index') }}" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 rounded-lg">Kartu Stok</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="px-3 py-1.5 bg-red-600/80 hover:bg-red-600 rounded-lg">Logout</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-500 text-emerald-200 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500 text-red-200 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Katalog Produk -->
            <div class="lg:col-span-2 bg-[#292D54] p-5 rounded-xl border border-indigo-500/30">
                <h2 class="font-semibold text-indigo-300 mb-4">Katalog Barang Tersedia</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($barangs as $b)
                        <div class="bg-[#15162B] p-4 rounded-xl border border-indigo-400/20 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-mono text-indigo-400">{{ $b->id_barang }}</span>
                                    <span class="text-xs px-2 py-0.5 rounded {{ $b->stok > 0 ? 'bg-emerald-500/20 text-emerald-300' : 'bg-red-500/20 text-red-300' }}">
                                        Stok: {{ $b->stok }} {{ $b->nama_satuan }}
                                    </span>
                                </div>
                                <h3 class="font-semibold mt-1">{{ $b->nama_barang }}</h3>
                                <p class="text-xs text-gray-400">Modal: Rp {{ number_format($b->harga_modal, 0, ',', '.') }}</p>
                                <p class="text-base font-bold text-emerald-400 mt-1">Jual: Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</p>
                            </div>
                            <form action="{{ route('penjualan.cart.add') }}" method="POST" class="mt-3 flex gap-2">
                                @csrf
                                <input type="hidden" name="id_barang" value="{{ $b->id_barang }}">
                                <input type="hidden" name="idmargin" value="{{ $selectedMargin }}">
                                <input type="number" name="jumlah" value="1" min="1" max="{{ max(1, $b->stok) }}" {{ $b->stok <= 0 ? 'disabled' : '' }}
                                       class="w-20 px-2 py-1.5 rounded bg-[#292D54] border border-indigo-400/30 text-sm text-center">
                                <button type="submit" {{ $b->stok <= 0 ? 'disabled' : '' }}
                                        class="flex-1 py-1.5 rounded text-sm font-semibold {{ $b->stok > 0 ? 'bg-indigo-600 hover:bg-indigo-500' : 'bg-gray-600 cursor-not-allowed' }}">
                                    + Keranjang
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Keranjang Kasir (Session) -->
            <div class="bg-[#292D54] p-5 rounded-xl border border-indigo-500/30 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-semibold text-emerald-300">Keranjang Belanja</h2>
                        @if(!empty($cart))
                            <form action="{{ route('penjualan.cart.clear') }}" method="POST">
                                @csrf
                                <button class="text-xs text-red-400 hover:underline">Kosongkan</button>
                            </form>
                        @endif
                    </div>

                    <div class="space-y-2 max-h-80 overflow-y-auto">
                        @php $subtotal = 0; @endphp
                        @forelse($cart as $item)
                            @php $subtotal += $item['subtotal']; @endphp
                            <div class="bg-[#15162B] p-3 rounded-lg flex justify-between items-center text-sm">
                                <div>
                                    <p class="font-semibold">{{ $item['nama_barang'] }}</p>
                                    <p class="text-xs text-gray-400">{{ $item['jumlah'] }} x Rp {{ number_format($item['harga_jual'], 0, ',', '.') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-emerald-300">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                                    <form action="{{ route('penjualan.cart.remove', $item['id_barang']) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="text-xs text-red-400">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 text-center py-8">Keranjang kasir masih kosong.</p>
                        @endforelse
                    </div>
                </div>

                @php
                    $ppn = round($subtotal * 0.11);
                    $total = $subtotal + $ppn;
                @endphp

                <div class="mt-4 pt-4 border-t border-indigo-400/20 space-y-1 text-sm">
                    <div class="flex justify-between text-gray-300"><span>Subtotal:</span><b>Rp {{ number_format($subtotal, 0, ',', '.') }}</b></div>
                    <div class="flex justify-between text-gray-300"><span>PPN (11%):</span><b>Rp {{ number_format($ppn, 0, ',', '.') }}</b></div>
                    <div class="flex justify-between text-lg font-bold text-emerald-400 pt-1">
                        <span>Total Bayar:</span><span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <form action="{{ route('penjualan.checkout') }}" method="POST" class="pt-3">
                        @csrf
                        <input type="hidden" name="idmargin" value="{{ $selectedMargin }}">
                        <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 rounded-lg font-bold">
                            Bayar & Cetak Struk Nota
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Riwayat Penjualan -->
        <div class="bg-[#292D54] p-5 rounded-xl border border-indigo-500/30">
            <h2 class="font-semibold text-indigo-300 mb-3">Riwayat Transaksi Penjualan</h2>
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-300 uppercase bg-[#15162B]">
                    <tr>
                        <th class="px-3 py-2">No. Struk</th>
                        <th class="px-3 py-2">Tanggal</th>
                        <th class="px-3 py-2">Kasir</th>
                        <th class="px-3 py-2">Margin</th>
                        <th class="px-3 py-2 text-right">Total Bayar</th>
                        <th class="px-3 py-2 text-center">Struk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-indigo-400/20">
                    @foreach($riwayat as $r)
                        <tr>
                            <td class="px-3 py-2 font-mono">#INV-{{ str_pad($r->id_penjualan, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-3 py-2">{{ $r->tanggal }}</td>
                            <td class="px-3 py-2">{{ $r->kasir }}</td>
                            <td class="px-3 py-2">{{ $r->margin_persen }}%</td>
                            <td class="px-3 py-2 text-right font-bold text-emerald-300">Rp {{ number_format($r->total_nilai, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-center">
                                <a href="{{ route('penjualan.nota', $r->id_penjualan) }}" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-500 rounded text-xs">Lihat Nota</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
