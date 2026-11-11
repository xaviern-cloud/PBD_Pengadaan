<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta content="web_dashboard" name="shell-type"/>
    <title>Dashboard & Overview - LogiTrack ERP</title>

    <!-- Google Fonts & Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet"/>

    <!-- Tailwind CDN & Custom Config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "surface-bright": "#36374e",
                        "surface-dim": "#101126",
                        "primary-container": "#454c83",
                        "surface-container-highest": "#323349",
                        "tertiary": "#bec5e5",
                        "background": "#101126",
                        "secondary-fixed-dim": "#bac4f7",
                        "secondary-fixed": "#dde1ff",
                        "on-secondary": "#232e58",
                        "tertiary-fixed": "#dbe1ff",
                        "outline-variant": "#46464f",
                        "outline": "#90909a",
                        "error-container": "#93000a",
                        "on-tertiary-fixed-variant": "#3e4660",
                        "on-surface": "#e1e0fd",
                        "on-background": "#e1e0fd",
                        "on-secondary-fixed": "#0d1841",
                        "on-secondary-fixed-variant": "#3a446f",
                        "secondary": "#bac4f7",
                        "primary-fixed": "#dfe0ff",
                        "primary-fixed-dim": "#bcc2ff",
                        "inverse-surface": "#e1e0fd",
                        "on-surface-variant": "#c7c5d1",
                        "inverse-primary": "#535a92",
                        "on-primary-fixed": "#0d154b",
                        "surface": "#101126",
                        "primary": "#bcc2ff",
                        "surface-tint": "#bcc2ff",
                        "error": "#ffb4ab",
                        "on-tertiary-container": "#bac2e2",
                        "on-error": "#690005",
                        "surface-container-low": "#191a2f",
                        "surface-container-high": "#27283e",
                        "on-tertiary-fixed": "#121b32",
                        "on-tertiary": "#273048",
                        "inverse-on-surface": "#2e2e45",
                        "surface-container": "#1d1e33",
                        "on-primary": "#242b61",
                        "on-primary-container": "#b8bffe",
                        "on-error-container": "#ffdad6",
                        "on-primary-fixed-variant": "#3b4279",
                        "secondary-container": "#3d4772",
                        "surface-variant": "#323349",
                        "tertiary-container": "#48506a",
                        "tertiary-fixed-dim": "#bec5e5",
                        "surface-container-lowest": "#0b0c21",
                        "on-secondary-container": "#acb6e8"
                    },
                    borderRadius: {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    spacing: {
                        "space-xs": "0.25rem",
                        "space-md": "0.5rem",
                        "margin": "1rem",
                        "margin-desktop": "1.5rem",
                        "space-xl": "1rem",
                        "space-sm": "0.375rem",
                        "gutter-desktop": "1rem",
                        "space-lg": "0.75rem",
                        "gutter": "0.75rem"
                    },
                    fontFamily: {
                        "body-sm": ["Inter"],
                        "title-md": ["Inter"],
                        "headline-lg": ["Inter"],
                        "headline-md": ["Inter"],
                        "display": ["Inter"],
                        "body-lg": ["Inter"],
                        "body-md": ["Inter"],
                        "label-sm": ["Inter"],
                        "headline-sm": ["Inter"],
                        "label-md": ["Inter"],
                        "code-tabular": ["Inter"]
                    },
                    fontSize: {
                        "body-sm": ["0.75rem", { "lineHeight": "1rem", "letterSpacing": "0.01em", "fontWeight": "400" }],
                        "title-md": ["0.9375rem", { "lineHeight": "1.375rem", "letterSpacing": "0em", "fontWeight": "600" }],
                        "headline-lg": ["1.5rem", { "lineHeight": "2rem", "letterSpacing": "-0.015em", "fontWeight": "600" }],
                        "headline-md": ["1.25rem", { "lineHeight": "1.75rem", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "display": ["2rem", { "lineHeight": "2.5rem", "letterSpacing": "-0.02em", "fontWeight": "600" }],
                        "body-lg": ["0.875rem", { "lineHeight": "1.25rem", "letterSpacing": "0em", "fontWeight": "400" }],
                        "body-md": ["0.8125rem", { "lineHeight": "1.125rem", "letterSpacing": "0em", "fontWeight": "400" }],
                        "label-sm": ["0.6875rem", { "lineHeight": "0.875rem", "letterSpacing": "0.04em", "fontWeight": "500" }],
                        "headline-sm": ["1.125rem", { "lineHeight": "1.5rem", "letterSpacing": "-0.005em", "fontWeight": "500" }],
                        "label-md": ["0.75rem", { "lineHeight": "1rem", "letterSpacing": "0.02em", "fontWeight": "600" }],
                        "code-tabular": ["0.8125rem", { "lineHeight": "1.125rem", "letterSpacing": "-0.01em", "fontWeight": "500" }]
                    }
                }
            }
        };
    </script>

    <!-- CSS External -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}"/>
</head>
<body class="bg-surface font-body-md text-on-surface antialiased">
    <!-- Sidebar Navigation -->
    <aside class="fixed left-0 top-0 h-full w-64 bg-surface-container-high z-50 flex flex-col justify-between select-none">
        <div class="flex flex-col">
            <div class="h-14 px-margin flex items-center justify-between bg-surface-container-high">
                <div class="flex items-center gap-space-md">
                    <img alt="LogiTrack ERP Logo" class="h-8 w-auto object-contain" src="https://lh3.googleusercontent.com/aida/ALJJyrDrDFharSV4S-JsxmwzVEA8AN9dcbUq9fd8mky0uFvETsEfF2GS_XId5rT7fr3QX6c5bqpm67iYg75hC7XHzkqlJrpVMzASP4_XZI4G7YBuCzuqJIq-cVge2PCjeosT5K3AnEbHYg68ujWdKf3Ssm4TrQjQjNr7P6FMGBwzWgtzrMRBVVgZ5iXSKfLdVyK2a5D2AitmwPF4dDcX9p-TQ6Gbz2r0oE4jp-rYzd7eslppTw"/>
                    <div class="flex flex-col">
                        <span class="font-title-md text-title-md tracking-tight text-on-surface uppercase leading-none">LOGITRACK ERP</span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant font-code-tabular tracking-wider mt-0.5">ENTERPRISE v4.2</span>
                    </div>
                </div>
            </div>

            <div class="px-space-md py-space-xs">
                <div class="px-space-md py-space-xs font-label-sm text-label-sm uppercase tracking-wider text-outline">Sistem & Logistik</div>
            </div>

            <nav class="flex flex-col gap-0.5 px-space-md">
                <a class="flex items-center gap-space-md px-space-md py-space-sm rounded bg-primary-container text-on-primary-container font-label-md transition-colors" href="{{ route('dashboard') ?? '#' }}">
                    <span class="material-symbols-outlined text-[18px]">grid_view</span>
                    <span class="font-body-md text-body-md truncate">Dashboard & Overview</span>
                </a>
                <a class="flex items-center gap-space-md px-space-md py-space-sm rounded text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors" href="{{ route('master.barang') ?? '#' }}">
                    <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                    <span class="font-body-md text-body-md truncate">Master Inventory & Katalog</span>
                </a>
                <a class="flex items-center gap-space-md px-space-md py-space-sm rounded text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors" href="#">
                    <span class="material-symbols-outlined text-[18px]">local_shipping</span>
                    <span class="font-body-md text-body-md truncate">PO & Procurement Vendors</span>
                </a>
                <a class="flex items-center gap-space-md px-space-md py-space-sm rounded text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors" href="#">
                    <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                    <span class="font-body-md text-body-md truncate">Stock Ledger & Mutasi</span>
                </a>
                <a class="flex items-center gap-space-md px-space-md py-space-sm rounded text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors" href="{{ route('penjualan.index') ?? '#' }}">
                    <span class="material-symbols-outlined text-[18px]">point_of_sale</span>
                    <span class="font-body-md text-body-md truncate">POS Terminal Kasir</span>
                </a>
                <a class="flex items-center gap-space-md px-space-md py-space-sm rounded text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors" href="#">
                    <span class="material-symbols-outlined text-[18px]">analytics</span>
                    <span class="font-body-md text-body-md truncate">Laporan & Analytics</span>
                </a>
                <a class="flex items-center gap-space-md px-space-md py-space-sm rounded text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors" href="#">
                    <span class="material-symbols-outlined text-[18px]">settings</span>
                    <span class="font-body-md text-body-md truncate">Pengaturan Sistem</span>
                </a>
            </nav>
        </div>

        <div class="p-space-md m-space-md rounded bg-surface-container-low flex flex-col gap-space-xs">
            <div class="flex items-center justify-between">
                <span class="font-label-sm text-label-sm text-outline uppercase">Cabang Aktif</span>
                <span class="font-code-tabular text-label-sm px-1.5 py-0.5 rounded bg-surface-container-highest text-secondary">WH-01</span>
            </div>
            <div class="font-label-md text-label-md text-on-surface truncate">Gudang Pusat Jakarta Timur</div>
            <div class="flex items-center gap-1.5 pt-1 text-secondary">
                <span class="h-1.5 w-1.5 rounded-full bg-secondary animate-pulse"></span>
                <span class="font-label-sm text-label-sm font-code-tabular text-on-surface-variant">Online • Sync 12s ago</span>
            </div>
        </div>
    </aside>

    <!-- Main Content Body -->
    <div class="pl-64">
        <!-- Top Navbar Header -->
        <header class="fixed top-0 left-64 right-0 h-14 bg-surface z-40 px-margin flex items-center justify-between shadow-[0_1px_8px_rgba(0,0,0,0.25)]">
            <div class="flex items-center gap-space-lg flex-1 max-w-xl">
                <div class="flex items-center w-full h-8 px-space-md rounded bg-surface-container-low text-on-surface-variant focus-within:ring-1 focus-within:ring-primary focus-within:text-on-surface">
                    <span class="material-symbols-outlined text-[18px] mr-space-sm text-outline">search</span>
                    <input class="bg-transparent border-none outline-none font-body-sm text-body-sm w-full placeholder:text-outline text-on-surface" placeholder="Cari SKU, batch no, manifest pengiriman, PO vendor..." type="text"/>
                    <kbd class="font-code-tabular text-label-sm bg-surface-container px-1.5 py-0.5 rounded text-outline-variant text-on-surface-variant">Ctrl + K</kbd>
                </div>
                <div class="flex items-center h-8 px-space-md rounded bg-surface-container-low cursor-pointer hover:bg-surface-container">
                    <span class="material-symbols-outlined text-[18px] text-outline mr-space-sm">warehouse</span>
                    <span class="font-label-md text-label-md text-on-surface whitespace-nowrap mr-space-sm">WH-01 JKT-TIMUR</span>
                    <span class="material-symbols-outlined text-[18px] text-outline">expand_more</span>
                </div>
            </div>
            <div class="flex items-center gap-space-md">
                <button class="h-8 px-space-md rounded bg-primary-container text-on-primary-container hover:bg-primary-container/80 flex items-center gap-1.5 font-label-md text-label-md transition-all shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    <span>+ Transaksi Baru</span>
                </button>
                <button class="relative h-8 w-8 rounded bg-surface-container-low text-on-surface-variant hover:text-on-surface hover:bg-surface-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">notifications</span>
                    <span class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-error text-on-error font-code-tabular text-label-sm flex items-center justify-center">4</span>
                </button>
                <div class="h-8 pl-space-sm pr-space-md rounded bg-surface-container-low flex items-center gap-space-md">
                    <img alt="Profile" class="w-6 h-6 rounded-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBlneUnI17T1gV5rU0vuMjHUR34FKiYYznU842r7XSbAKnhw8y8DImOipyAnpa0or-SLGkntxruYPAEjJeWEr9Uf0M-qHJTXQhqSE7k6H0EhmE3tQQJJIHyvyFY49iitJOx4wJpzX_9TFPXyif5n1X1zpSWZt1wvRipr8KfhQgGWBOM3DCEUQx9NRDu1-4hEy6Jw_Lh3LsRgorzdBdgXZVAdS7c7UFC5x4WkprXR6nk"/>
                    <div class="flex flex-col text-left">
                        <span class="font-label-md text-label-md text-on-surface leading-none">Hendrawan Pratama</span>
                        <span class="font-label-sm text-label-sm text-outline leading-none mt-0.5">Supply Chain Lead / Head Admin</span>
                    </div>
                    <span class="material-symbols-outlined text-[16px] text-outline">unfold_more</span>
                </div>
            </div>
        </header>

        <main class="w-full pt-14 px-margin-desktop py-margin-desktop bg-surface min-h-screen">
            <div class="flex flex-col w-full gap-space-lg">
                <!-- Operational Context Header Bar -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-space-md bg-surface-container p-space-md rounded shadow-sm">
                    <div class="flex items-center gap-space-md">
                        <div class="flex items-center justify-center w-8 h-8 rounded bg-primary-container text-on-primary-container shadow-inner">
                            <span class="material-symbols-outlined text-[20px]">precision_manufacturing</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-space-sm">
                                <span class="font-headline-sm text-headline-sm text-on-surface">Pusat Kendali Operasional & Logistik</span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-surface-container-highest text-secondary font-code-tabular text-label-sm">LIVE FEED</span>
                            </div>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Monitoring pergerakan material, valuasi aset, siklus pengadaan, dan antrean persetujuan logistik terpusat.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-space-sm self-start md:self-auto">
                        <div class="flex items-center gap-1.5 px-space-md py-1 rounded bg-surface-container-low text-on-surface font-code-tabular text-body-sm">
                            <span class="inline-block w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                            <span class="text-outline">Latency:</span>
                            <span class="text-primary font-semibold">18ms</span>
                        </div>
                        <button class="flex items-center gap-1 px-space-md py-1 rounded bg-surface-container-highest hover:bg-surface-bright text-on-surface transition-colors font-label-md text-label-md">
                            <span class="material-symbols-outlined text-[16px]">file_download</span>
                            <span>Unduh Rekap (CSV)</span>
                        </button>
                        <button class="flex items-center gap-1 px-space-md py-1 rounded bg-primary-container hover:bg-primary text-on-primary-container hover:text-on-primary transition-colors font-label-md text-label-md shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">refresh</span>
                            <span>Sinkronisasi</span>
                        </button>
                    </div>
                </div>

                <!-- Section 1: Top Metrics Bar (4 High-Density KPI Cards) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-space-md">
                    <!-- KPI 1: Valuasi Inventory -->
                    <div class="bg-surface-container p-space-md rounded flex flex-col justify-between shadow-sm relative overflow-hidden group">
                        <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-primary/5 pointer-events-none group-hover:scale-125 transition-transform duration-500"></div>
                        <div class="flex items-center justify-between mb-space-xs">
                            <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Total Valuasi Inventory</span>
                            <span class="material-symbols-outlined text-[18px] text-tertiary">account_balance_wallet</span>
                        </div>
                        <div class="flex flex-col my-1">
                            <span class="font-headline-lg text-headline-lg font-code-tabular text-on-surface tracking-tight">Rp 14.825.400.000</span>
                            <div class="flex items-center gap-space-sm mt-1">
                                <span class="inline-flex items-center gap-0.5 px-1.5 py-0.2 rounded bg-surface-container-highest text-secondary font-code-tabular text-label-sm">
                                    <span class="material-symbols-outlined text-[12px]">trending_up</span>+4.2% MoM
                                </span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant">1.420 SKU Aktif</span>
                            </div>
                        </div>
                        <div class="w-full bg-surface-container-highest h-1 rounded-full overflow-hidden mt-space-xs">
                            <div class="bg-primary h-full rounded-full" style="width: 78%"></div>
                        </div>
                    </div>

                    <!-- KPI 2: PO Aktif -->
                    <div class="bg-surface-container p-space-md rounded flex flex-col justify-between shadow-sm relative overflow-hidden group">
                        <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-secondary/5 pointer-events-none group-hover:scale-125 transition-transform duration-500"></div>
                        <div class="flex items-center justify-between mb-space-xs">
                            <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Purchase Order Aktif</span>
                            <span class="material-symbols-outlined text-[18px] text-tertiary">local_shipping</span>
                        </div>
                        <div class="flex flex-col my-1">
                            <div class="flex items-baseline gap-space-xs">
                                <span class="font-headline-lg text-headline-lg font-code-tabular text-on-surface">28</span>
                                <span class="font-title-md text-title-md text-on-surface-variant font-normal">PO Berjalan</span>
                            </div>
                            <div class="flex items-center gap-space-sm mt-1 font-body-sm text-body-sm">
                                <span class="px-1.5 py-0.2 rounded bg-surface-container-highest text-on-surface font-code-tabular text-label-sm">12 Antre Approval</span>
                                <span class="text-on-surface-variant">•</span>
                                <span class="text-on-surface-variant font-code-tabular">16 On-Delivery</span>
                            </div>
                        </div>
                        <div class="flex gap-1 mt-space-xs">
                            <div class="h-1 flex-1 bg-primary rounded-full"></div>
                            <div class="h-1 flex-1 bg-secondary rounded-full"></div>
                            <div class="h-1 flex-1 bg-surface-container-highest rounded-full"></div>
                        </div>
                    </div>

                    <!-- KPI 3: Mutasi Hari Ini -->
                    <div class="bg-surface-container p-space-md rounded flex flex-col justify-between shadow-sm relative overflow-hidden group">
                        <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-tertiary/5 pointer-events-none group-hover:scale-125 transition-transform duration-500"></div>
                        <div class="flex items-center justify-between mb-space-xs">
                            <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Mutasi Hari Ini</span>
                            <span class="material-symbols-outlined text-[18px] text-tertiary">swap_horiz</span>
                        </div>
                        <div class="flex flex-col my-1">
                            <div class="flex items-baseline gap-space-xs">
                                <span class="font-headline-lg text-headline-lg font-code-tabular text-on-surface">1.840</span>
                                <span class="font-title-md text-title-md text-on-surface-variant font-normal">Unit Terproses</span>
                            </div>
                            <div class="flex items-center justify-between gap-1 mt-1 font-code-tabular text-label-sm text-on-surface-variant">
                                <span class="text-secondary font-medium">In: 1.200</span>
                                <span>|</span>
                                <span class="text-on-surface">Out: 580</span>
                                <span>|</span>
                                <span class="text-error">Ret: 60</span>
                            </div>
                        </div>
                        <div class="w-full bg-surface-container-highest h-1 rounded-full overflow-hidden mt-space-xs flex">
                            <div class="bg-secondary h-full" style="width: 65%"></div>
                            <div class="bg-tertiary h-full" style="width: 31%"></div>
                            <div class="bg-error h-full" style="width: 4%"></div>
                        </div>
                    </div>

                    <!-- KPI 4: Stok Minimum Alert -->
                    <div class="bg-surface-container p-space-md rounded flex flex-col justify-between shadow-sm relative overflow-hidden group">
                        <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-error-container/20 pointer-events-none group-hover:scale-125 transition-transform duration-500"></div>
                        <div class="flex items-center justify-between mb-space-xs">
                            <span class="font-label-sm text-label-sm text-error uppercase tracking-wider flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">warning</span>
                                Peringatan Stok Minimum
                            </span>
                            <span class="font-code-tabular text-label-sm px-1.5 py-0.5 rounded bg-error-container text-on-error-container font-semibold">URGENT</span>
                        </div>
                        <div class="flex flex-col my-1">
                            <div class="flex items-baseline gap-space-xs">
                                <span class="font-headline-lg text-headline-lg font-code-tabular text-error">14</span>
                                <span class="font-title-md text-title-md text-on-surface font-normal">Item Kritis (Safety Stock)</span>
                            </div>
                            <div class="flex items-center justify-between mt-1">
                                <span class="font-body-sm text-body-sm text-on-surface-variant">Defisit rata-rata 32% buffer</span>
                                <button class="font-label-sm text-label-sm text-secondary hover:text-primary transition-colors flex items-center gap-0.5 underline">
                                    Reorder Prompt <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                                </button>
                            </div>
                        </div>
                        <div class="w-full bg-surface-container-highest h-1 rounded-full overflow-hidden mt-space-xs">
                            <div class="bg-error h-full rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Middle Section (12-Column: 8 Col Left, 4 Col Right) -->
                <div class="grid grid-cols-12 gap-space-md">
                    <!-- Left Column (Span 8): Mutasi Stream & Throughput Visualization -->
                    <div class="col-span-12 lg:col-span-8 flex flex-col gap-space-md">
                        <div class="bg-surface-container p-space-md rounded shadow-sm flex flex-col gap-space-md">
                            <!-- Header & Filters -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-space-sm">
                                <div>
                                    <div class="flex items-center gap-space-sm">
                                        <span class="font-title-md text-title-md text-on-surface">Throughput Logistik & Volume Mutasi</span>
                                        <span class="text-label-sm font-code-tabular px-1.5 py-0.5 rounded bg-surface-container-low text-secondary">Mingguan (W-34)</span>
                                    </div>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant">Distribusi pergerakan barang lintas 5 klasifikasi inventori material.</p>
                                </div>
                                <!-- Warehouse Switcher Pill -->
                                <div class="flex items-center p-0.5 rounded bg-surface-container-low self-start sm:self-auto text-body-sm font-label-md">
                                    <button class="px-space-md py-1 rounded bg-primary-container text-on-primary-container transition-all" id="wh-btn-01">WH-01 Jakarta</button>
                                    <button class="px-space-md py-1 rounded text-on-surface-variant hover:text-on-surface transition-all" id="wh-btn-02">WH-02 Cikarang</button>
                                    <button class="px-space-md py-1 rounded text-on-surface-variant hover:text-on-surface transition-all" id="wh-btn-03">WH-03 Surabaya</button>
                                </div>
                            </div>
                            <!-- Inventory Classification Legend Chips -->
                            <div class="flex flex-wrap items-center gap-space-xs">
                                <span class="font-label-sm text-label-sm text-outline mr-1">Klasifikasi:</span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-surface-container-highest text-on-surface text-label-sm">
                                    <span class="w-2 h-2 rounded-full bg-[#8292E6]"></span> BB (Bahan Baku)
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-surface-container-highest text-on-surface text-label-sm">
                                    <span class="w-2 h-2 rounded-full bg-[#BCC2FF]"></span> BSJ (Barang Setengah Jadi)
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-surface-container-highest text-on-surface text-label-sm">
                                    <span class="w-2 h-2 rounded-full bg-[#62B890]"></span> BJ (Barang Jadi)
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-surface-container-highest text-on-surface text-label-sm">
                                    <span class="w-2 h-2 rounded-full bg-[#E8A358]"></span> BO (Bahan Operasional)
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-surface-container-highest text-on-surface text-label-sm">
                                    <span class="w-2 h-2 rounded-full bg-[#D46E7E]"></span> BK (Barang Konsinyasi)
                                </span>
                            </div>

                            <!-- SVG Chart Area -->
                            <div class="relative bg-surface-container-lowest p-space-md rounded flex flex-col gap-space-sm">
                                <div class="flex items-center justify-between text-label-sm font-code-tabular text-outline">
                                    <span>VOLUME SATUAN (UNIT/KG)</span>
                                    <span class="flex items-center gap-2">
                                        <span class="inline-block w-2.5 h-0.5 bg-primary"></span> Rata-rata 7 Hari: 1.480/hari
                                    </span>
                                </div>
                                <div class="w-full h-44 relative">
                                    <svg class="w-full h-full overflow-visible" preserveaspectratio="none" viewbox="0 0 700 160">
                                        <defs>
                                            <lineargradient id="areaGrad" x1="0" x2="0" y1="0" y2="1">
                                                <stop offset="0%" stop-color="#454c83" stop-opacity="0.55"></stop>
                                                <stop offset="100%" stop-color="#454c83" stop-opacity="0.0"></stop>
                                            </lineargradient>
                                            <lineargradient id="areaGradSecondary" x1="0" x2="0" y1="0" y2="1">
                                                <stop offset="0%" stop-color="#bac4f7" stop-opacity="0.25"></stop>
                                                <stop offset="100%" stop-color="#bac4f7" stop-opacity="0.0"></stop>
                                            </lineargradient>
                                        </defs>
                                        <!-- Grid reference lines -->
                                        <line stroke="#323349" stroke-dasharray="3,3" stroke-width="1" x1="0" x2="700" y1="20" y2="20"></line>
                                        <line stroke="#323349" stroke-dasharray="3,3" stroke-width="1" x1="0" x2="700" y1="60" y2="60"></line>
                                        <line stroke="#323349" stroke-dasharray="3,3" stroke-width="1" x1="0" x2="700" y1="100" y2="100"></line>
                                        <line stroke="#323349" stroke-width="1" x1="0" x2="700" y1="140" y2="140"></line>
                                        <!-- Value Labels Y Axis -->
                                        <text fill="#90909a" font-family="Inter" font-size="9" x="6" y="24">2.400</text>
                                        <text fill="#90909a" font-family="Inter" font-size="9" x="6" y="64">1.600</text>
                                        <text fill="#90909a" font-family="Inter" font-size="9" x="6" y="104">800</text>
                                        <!-- Area Shading -->
                                        <polygon fill="url(#areaGrad)" points="40,140 40,95 140,70 240,110 340,40 440,65 540,30 660,45 660,140"></polygon>
                                        <polygon fill="url(#areaGradSecondary)" points="40,140 40,120 140,105 240,130 340,85 440,95 540,65 660,70 660,140"></polygon>
                                        <!-- Polyline Paths -->
                                        <polyline fill="none" points="40,95 140,70 240,110 340,40 440,65 540,30 660,45" stroke="#bcc2ff" stroke-width="2"></polyline>
                                        <polyline fill="none" points="40,120 140,105 240,130 340,85 440,95 540,65 660,70" stroke="#bac4f7" stroke-dasharray="4,2" stroke-width="1.5"></polyline>
                                        <!-- Data Node Markers -->
                                        <circle cx="40" cy="95" fill="#bcc2ff" r="3"></circle>
                                        <circle cx="140" cy="70" fill="#bcc2ff" r="3"></circle>
                                        <circle cx="240" cy="110" fill="#bcc2ff" r="3"></circle>
                                        <circle cx="340" cy="40" fill="#ffffff" r="4" stroke="#454c83" stroke-width="2"></circle>
                                        <circle cx="440" cy="65" fill="#bcc2ff" r="3"></circle>
                                        <circle cx="540" cy="30" fill="#ffffff" r="4" stroke="#454c83" stroke-width="2"></circle>
                                        <circle cx="660" cy="45" fill="#bcc2ff" r="3"></circle>
                                        <!-- Day X Axis Labels -->
                                        <text fill="#90909a" font-family="Inter" font-size="10" x="35" y="155">Sen</text>
                                        <text fill="#90909a" font-family="Inter" font-size="10" x="135" y="155">Sel</text>
                                        <text fill="#90909a" font-family="Inter" font-size="10" x="235" y="155">Rab</text>
                                        <text fill="#90909a" font-family="Inter" font-size="10" x="335" y="155">Kam</text>
                                        <text fill="#90909a" font-family="Inter" font-size="10" x="435" y="155">Jum</text>
                                        <text fill="#90909a" font-family="Inter" font-size="10" x="535" y="155">Sab</text>
                                        <text fill="#bcc2ff" font-family="Inter" font-size="10" font-weight="600" x="650" y="155">Min (Hari Ini)</text>
                                    </svg>
                                </div>
                                <!-- Bottom Micro Metrics Ribbon -->
                                <div class="grid grid-cols-5 gap-2 pt-2 bg-surface-container-low p-2 rounded">
                                    <div class="flex flex-col">
                                        <span class="font-label-sm text-label-sm text-outline">Bahan Baku (BB)</span>
                                        <span class="font-code-tabular text-body-md text-on-surface font-semibold">6.420 Kg</span>
                                        <span class="font-label-sm text-secondary">+12.4%</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-label-sm text-label-sm text-outline">Setengah Jadi (BSJ)</span>
                                        <span class="font-code-tabular text-body-md text-on-surface font-semibold">3.850 Pcs</span>
                                        <span class="font-label-sm text-secondary">+5.1%</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-label-sm text-label-sm text-outline">Barang Jadi (BJ)</span>
                                        <span class="font-code-tabular text-body-md text-on-surface font-semibold">2.180 Box</span>
                                        <span class="font-label-sm text-secondary">+18.0%</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-label-sm text-label-sm text-outline">Operasional (BO)</span>
                                        <span class="font-code-tabular text-body-md text-on-surface font-semibold">410 Roll</span>
                                        <span class="font-label-sm text-outline">-2.2%</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-label-sm text-label-sm text-outline">Konsinyasi (BK)</span>
                                        <span class="font-code-tabular text-body-md text-on-surface font-semibold">190 Pcs</span>
                                        <span class="font-label-sm text-secondary">+0.8%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column (Span 4): Quick Actions & Critical Reorder Approvals -->
                    <div class="col-span-12 lg:col-span-4 flex flex-col gap-space-md">
                        <div class="bg-surface-container p-space-md rounded shadow-sm flex flex-col h-full justify-between">
                            <div>
                                <div class="flex items-center justify-between pb-space-xs">
                                    <div class="flex items-center gap-space-xs">
                                        <span class="material-symbols-outlined text-[18px] text-secondary">approval</span>
                                        <span class="font-title-md text-title-md text-on-surface">Persetujuan PO Kritis</span>
                                    </div>
                                    <span class="font-code-tabular text-label-sm px-2 py-0.5 rounded bg-error-container text-on-error-container font-semibold">
                                        3 Tertunda
                                    </span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant mb-space-sm">Perlu verifikasi pengadaan untuk mencegah stockout lini manufaktur.</p>

                                <div class="flex flex-col gap-space-sm" id="approval-list">
                                    <!-- Approval Item 1 -->
                                    <div class="p-space-sm rounded bg-surface-container-low flex flex-col gap-1.5 transition-all hover:bg-surface-container-high" id="po-card-1">
                                        <div class="flex items-center justify-between">
                                            <span class="font-code-tabular text-label-sm font-semibold text-primary">PO-2025-0441</span>
                                            <span class="font-label-sm text-label-sm text-error font-code-tabular">Lead Time: 2 Hari</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-label-md text-label-md text-on-surface font-medium truncate">Masterbatch Pigment Hitam Industri</span>
                                            <div class="flex items-center justify-between text-body-sm text-on-surface-variant mt-0.5">
                                                <span class="truncate text-secondary">PT Kimia Sentosa Nusantara</span>
                                                <span class="font-code-tabular text-on-surface font-semibold">Rp 48.500.000</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-space-xs pt-1">
                                            <button class="flex-1 py-1 px-2 rounded bg-primary-container hover:bg-primary text-on-primary-container hover:text-on-primary font-label-sm text-label-sm font-medium flex items-center justify-center gap-1 transition-colors" onclick="approvePO('po-card-1')">
                                                <span class="material-symbols-outlined text-[14px]">check</span> Approve
                                            </button>
                                            <button class="py-1 px-2 rounded bg-surface-container-highest hover:bg-error-container text-on-surface-variant hover:text-on-error-container font-label-sm text-label-sm transition-colors flex items-center justify-center" onclick="rejectPO('po-card-1')">
                                                <span class="material-symbols-outlined text-[14px]">close</span>
                                            </button>
                                            <button class="py-1 px-2 rounded bg-surface-container-highest text-on-surface font-label-sm text-label-sm hover:bg-surface-bright transition-colors">
                                                <span class="material-symbols-outlined text-[14px]">visibility</span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Approval Item 2 -->
                                    <div class="p-space-sm rounded bg-surface-container-low flex flex-col gap-1.5 transition-all hover:bg-surface-container-high" id="po-card-2">
                                        <div class="flex items-center justify-between">
                                            <span class="font-code-tabular text-label-sm font-semibold text-primary">PO-2025-0442</span>
                                            <span class="font-label-sm text-label-sm text-secondary font-code-tabular">Lead Time: 4 Hari</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-label-md text-label-md text-on-surface font-medium truncate">Resin HDPE Injection Moulding #77</span>
                                            <div class="flex items-center justify-between text-body-sm text-on-surface-variant mt-0.5">
                                                <span class="truncate text-secondary">PT Logam Presisi Nusantara</span>
                                                <span class="font-code-tabular text-on-surface font-semibold">Rp 122.400.000</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-space-xs pt-1">
                                            <button class="flex-1 py-1 px-2 rounded bg-primary-container hover:bg-primary text-on-primary-container hover:text-on-primary font-label-sm text-label-sm font-medium flex items-center justify-center gap-1 transition-colors" onclick="approvePO('po-card-2')">
                                                <span class="material-symbols-outlined text-[14px]">check</span> Approve
                                            </button>
                                            <button class="py-1 px-2 rounded bg-surface-container-highest hover:bg-error-container text-on-surface-variant hover:text-on-error-container font-label-sm text-label-sm transition-colors flex items-center justify-center" onclick="rejectPO('po-card-2')">
                                                <span class="material-symbols-outlined text-[14px]">close</span>
                                            </button>
                                            <button class="py-1 px-2 rounded bg-surface-container-highest text-on-surface font-label-sm text-label-sm hover:bg-surface-bright transition-colors">
                                                <span class="material-symbols-outlined text-[14px]">visibility</span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Approval Item 3 -->
                                    <div class="p-space-sm rounded bg-surface-container-low flex flex-col gap-1.5 transition-all hover:bg-surface-container-high" id="po-card-3">
                                        <div class="flex items-center justify-between">
                                            <span class="font-code-tabular text-label-sm font-semibold text-primary">PO-2025-0445</span>
                                            <span class="font-label-sm text-label-sm text-error font-code-tabular">Lead Time: 1 Hari</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-label-md text-label-md text-on-surface font-medium truncate">Nozzle Spray Valve 24/410 Silver</span>
                                            <div class="flex items-center justify-between text-body-sm text-on-surface-variant mt-0.5">
                                                <span class="truncate text-secondary">CV Polyplast Indo Perkasa</span>
                                                <span class="font-code-tabular text-on-surface font-semibold">Rp 31.250.000</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-space-xs pt-1">
                                            <button class="flex-1 py-1 px-2 rounded bg-primary-container hover:bg-primary text-on-primary-container hover:text-on-primary font-label-sm text-label-sm font-medium flex items-center justify-center gap-1 transition-colors" onclick="approvePO('po-card-3')">
                                                <span class="material-symbols-outlined text-[14px]">check</span> Approve
                                            </button>
                                            <button class="py-1 px-2 rounded bg-surface-container-highest hover:bg-error-container text-on-surface-variant hover:text-on-error-container font-label-sm text-label-sm transition-colors flex items-center justify-center" onclick="rejectPO('po-card-3')">
                                                <span class="material-symbols-outlined text-[14px]">close</span>
                                            </button>
                                            <button class="py-1 px-2 rounded bg-surface-container-highest text-on-surface font-label-sm text-label-sm hover:bg-surface-bright transition-colors">
                                                <span class="material-symbols-outlined text-[14px]">visibility</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-space-md pt-space-xs bg-surface-container-lowest p-space-sm rounded">
                                <div class="flex items-center justify-between">
                                    <span class="font-label-sm text-label-sm text-outline">Batas Otorisasi Manager</span>
                                    <span class="font-code-tabular text-label-sm text-on-surface">Rp 250.000.000</span>
                                </div>
                                <div class="w-full bg-surface-container h-1 rounded-full overflow-hidden my-1">
                                    <div class="bg-secondary h-full" style="width: 80%"></div>
                                </div>
                                <div class="flex items-center justify-between font-label-sm text-label-sm text-on-surface-variant">
                                    <span>Terpakai: Rp 202.150.000</span>
                                    <span class="text-secondary font-medium">Sisa: 19%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Lower Section - High-Density Data Table 'Aktivitas Log Mutasi Terkini' -->
                <div class="bg-surface-container rounded p-space-md shadow-sm flex flex-col gap-space-sm">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-space-sm pb-1">
                        <div class="flex items-center gap-space-md">
                            <div class="flex items-center gap-space-xs">
                                <span class="material-symbols-outlined text-[20px] text-primary">receipt_long</span>
                                <span class="font-title-md text-title-md text-on-surface">Aktivitas Log Mutasi Terkini</span>
                            </div>
                            <span class="font-code-tabular text-label-sm px-2 py-0.5 rounded bg-surface-container-low text-on-surface-variant">
                                5 Catatan Terbaru
                            </span>
                        </div>
                        <div class="flex items-center gap-space-sm flex-wrap">
                            <div class="flex items-center h-7 px-space-sm rounded bg-surface-container-low text-on-surface-variant text-body-sm">
                                <span class="material-symbols-outlined text-[16px] text-outline mr-1">filter_list</span>
                                <select class="bg-transparent text-on-surface text-label-sm outline-none cursor-pointer">
                                    <option class="bg-surface-container text-on-surface" value="all">Semua Jenis Material (BB, BSJ, BJ, BO, BK)</option>
                                    <option class="bg-surface-container text-on-surface" value="bb">Bahan Baku (BB)</option>
                                    <option class="bg-surface-container text-on-surface" value="bsj">Barang Setengah Jadi (BSJ)</option>
                                    <option class="bg-surface-container text-on-surface" value="bj">Barang Jadi (BJ)</option>
                                </select>
                            </div>
                            <div class="flex items-center h-7 px-space-sm rounded bg-surface-container-low text-on-surface-variant text-body-sm">
                                <select class="bg-transparent text-on-surface text-label-sm outline-none cursor-pointer">
                                    <option class="bg-surface-container text-on-surface" value="all">Semua Tipe Mutasi (M, K, R)</option>
                                    <option class="bg-surface-container text-on-surface" value="in">Masuk [M]</option>
                                    <option class="bg-surface-container text-on-surface" value="out">Keluar [K]</option>
                                    <option class="bg-surface-container text-on-surface" value="ret">Retur [R]</option>
                                </select>
                            </div>
                            <button class="h-7 px-space-sm rounded bg-surface-container-highest hover:bg-surface-bright text-on-surface font-label-sm text-label-sm flex items-center gap-1 transition-colors">
                                <span class="material-symbols-outlined text-[16px]">tune</span>
                                <span>Kolom</span>
                            </button>
                        </div>
                    </div>

                    <div class="w-full overflow-x-auto rounded bg-surface-container-low shadow-inner">
                        <table class="w-full text-left border-collapse select-text">
                            <thead>
                                <tr class="bg-surface-container-high h-8 text-label-sm font-label-sm text-tertiary uppercase tracking-wider">
                                    <th class="px-space-md py-1 whitespace-nowrap">Waktu</th>
                                    <th class="px-space-md py-1 whitespace-nowrap">Kode Transaksi</th>
                                    <th class="px-space-md py-1 whitespace-nowrap">ID Item</th>
                                    <th class="px-space-md py-1 min-w-[200px]">Nama Barang & Spesifikasi</th>
                                    <th class="px-space-md py-1 text-center whitespace-nowrap">Jenis</th>
                                    <th class="px-space-md py-1 whitespace-nowrap">Tipe Mutasi</th>
                                    <th class="px-space-md py-1 text-right whitespace-nowrap">Qty Satuan</th>
                                    <th class="px-space-md py-1 whitespace-nowrap">Operator / PIC</th>
                                    <th class="px-space-md py-1 text-center whitespace-nowrap">Status</th>
                                    <th class="px-space-md py-1 text-center whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-0 font-body-sm text-body-sm">
                                <!-- Row 1 -->
                                <tr class="h-9 hover:bg-surface-container-high transition-colors text-on-surface group">
                                    <td class="px-space-md py-1 font-code-tabular text-on-surface-variant whitespace-nowrap">14:28:10</td>
                                    <td class="px-space-md py-1 font-code-tabular font-semibold text-primary whitespace-nowrap">TR-2025-0891</td>
                                    <td class="px-space-md py-1 font-code-tabular text-on-surface-variant whitespace-nowrap">BB-001</td>
                                    <td class="px-space-md py-1">
                                        <span class="font-medium text-on-surface">Biji Plastik HDPE Grade A</span>
                                        <span class="text-outline text-label-sm block">Batch #HD-9081 • Silo 4</span>
                                    </td>
                                    <td class="px-space-md py-1 text-center">
                                        <span class="inline-block px-1.5 py-0.5 rounded font-code-tabular text-label-sm bg-[#8292E6]/20 text-[#bac4f7] font-semibold">BB</span>
                                    </td>
                                    <td class="px-space-md py-1 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-surface-container text-secondary text-label-sm font-medium">
                                            <span class="material-symbols-outlined text-[12px]">arrow_downward</span> Masuk [M]
                                        </span>
                                    </td>
                                    <td class="px-space-md py-1 text-right font-code-tabular font-semibold text-secondary whitespace-nowrap">+500 Kg</td>
                                    <td class="px-space-md py-1 text-on-surface whitespace-nowrap">Budi Santoso</td>
                                    <td class="px-space-md py-1 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-surface-container-highest text-secondary text-label-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span> Verified
                                        </span>
                                    </td>
                                    <td class="px-space-md py-1 text-center whitespace-nowrap">
                                        <button class="h-6 w-6 rounded bg-surface-container hover:bg-surface-bright text-on-surface-variant hover:text-on-surface inline-flex items-center justify-center transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">more_vert</span>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Row 2 -->
                                <tr class="h-9 bg-surface-container/30 hover:bg-surface-container-high transition-colors text-on-surface group">
                                    <td class="px-space-md py-1 font-code-tabular text-on-surface-variant whitespace-nowrap">14:15:02</td>
                                    <td class="px-space-md py-1 font-code-tabular font-semibold text-primary whitespace-nowrap">TR-2025-0892</td>
                                    <td class="px-space-md py-1 font-code-tabular text-on-surface-variant whitespace-nowrap">BJ-004</td>
                                    <td class="px-space-md py-1">
                                        <span class="font-medium text-on-surface">Botol Spray 500ml Ergonomis</span>
                                        <span class="text-outline text-label-sm block">Pallet 09 • Dispenser Retail</span>
                                    </td>
                                    <td class="px-space-md py-1 text-center">
                                        <span class="inline-block px-1.5 py-0.5 rounded font-code-tabular text-label-sm bg-[#62B890]/20 text-[#62B890] font-semibold">BJ</span>
                                    </td>
                                    <td class="px-space-md py-1 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-surface-container text-on-surface text-label-sm font-medium">
                                            <span class="material-symbols-outlined text-[12px]">arrow_upward</span> Keluar POS [K]
                                        </span>
                                    </td>
                                    <td class="px-space-md py-1 text-right font-code-tabular font-semibold text-on-surface whitespace-nowrap">-120 Pcs</td>
                                    <td class="px-space-md py-1 text-on-surface whitespace-nowrap">Kasir 02</td>
                                    <td class="px-space-md py-1 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-surface-container-highest text-secondary text-label-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span> Completed
                                        </span>
                                    </td>
                                    <td class="px-space-md py-1 text-center whitespace-nowrap">
                                        <button class="h-6 w-6 rounded bg-surface-container hover:bg-surface-bright text-on-surface-variant hover:text-on-surface inline-flex items-center justify-center transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">more_vert</span>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Row 3 -->
                                <tr class="h-9 hover:bg-surface-container-high transition-colors text-on-surface group">
                                    <td class="px-space-md py-1 font-code-tabular text-on-surface-variant whitespace-nowrap">13:58:44</td>
                                    <td class="px-space-md py-1 font-code-tabular font-semibold text-primary whitespace-nowrap">TR-2025-0893</td>
                                    <td class="px-space-md py-1 font-code-tabular text-on-surface-variant whitespace-nowrap">BSJ-002</td>
                                    <td class="px-space-md py-1">
                                        <span class="font-medium text-on-surface">Preform Botol 28mm Clear Standard</span>
                                        <span class="text-outline text-label-sm block">Bin C-12 • Molding Unit 2</span>
                                    </td>
                                    <td class="px-space-md py-1 text-center">
                                        <span class="inline-block px-1.5 py-0.5 rounded font-code-tabular text-label-sm bg-[#BCC2FF]/20 text-primary font-semibold">BSJ</span>
                                    </td>
                                    <td class="px-space-md py-1 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-surface-container text-secondary text-label-sm font-medium">
                                            <span class="material-symbols-outlined text-[12px]">arrow_downward</span> Masuk [M]
                                        </span>
                                    </td>
                                    <td class="px-space-md py-1 text-right font-code-tabular font-semibold text-secondary whitespace-nowrap">+1.000 Pcs</td>
                                    <td class="px-space-md py-1 text-on-surface whitespace-nowrap">QC Shift 1</td>
                                    <td class="px-space-md py-1 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-surface-container-highest text-secondary text-label-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span> Verified
                                        </span>
                                    </td>
                                    <td class="px-space-md py-1 text-center whitespace-nowrap">
                                        <button class="h-6 w-6 rounded bg-surface-container hover:bg-surface-bright text-on-surface-variant hover:text-on-surface inline-flex items-center justify-center transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">more_vert</span>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Row 4 -->
                                <tr class="h-9 bg-surface-container/30 hover:bg-surface-container-high transition-colors text-on-surface group">
                                    <td class="px-space-md py-1 font-code-tabular text-on-surface-variant whitespace-nowrap">13:42:19</td>
                                    <td class="px-space-md py-1 font-code-tabular font-semibold text-primary whitespace-nowrap">TR-2025-0894</td>
                                    <td class="px-space-md py-1 font-code-tabular text-on-surface-variant whitespace-nowrap">BO-011</td>
                                    <td class="px-space-md py-1">
                                        <span class="font-medium text-on-surface">Stretch Film Palletizing 50cm Heavy</span>
                                        <span class="text-outline text-label-sm block">Rak Logistik Ekspedisi WH-01</span>
                                    </td>
                                    <td class="px-space-md py-1 text-center">
                                        <span class="inline-block px-1.5 py-0.5 rounded font-code-tabular text-label-sm bg-[#E8A358]/20 text-[#E8A358] font-semibold">BO</span>
                                    </td>
                                    <td class="px-space-md py-1 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-surface-container text-on-surface text-label-sm font-medium">
                                            <span class="material-symbols-outlined text-[12px]">arrow_upward</span> Keluar [K]
                                        </span>
                                    </td>
                                    <td class="px-space-md py-1 text-right font-code-tabular font-semibold text-on-surface whitespace-nowrap">-15 Roll</td>
                                    <td class="px-space-md py-1 text-on-surface whitespace-nowrap">Tim Logistik WH</td>
                                    <td class="px-space-md py-1 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-surface-container-highest text-tertiary text-label-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span> Issued
                                        </span>
                                    </td>
                                    <td class="px-space-md py-1 text-center whitespace-nowrap">
                                        <button class="h-6 w-6 rounded bg-surface-container hover:bg-surface-bright text-on-surface-variant hover:text-on-surface inline-flex items-center justify-center transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">more_vert</span>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Row 5 -->
                                <tr class="h-9 hover:bg-surface-container-high transition-colors text-on-surface group">
                                    <td class="px-space-md py-1 font-code-tabular text-on-surface-variant whitespace-nowrap">13:10:05</td>
                                    <td class="px-space-md py-1 font-code-tabular font-semibold text-primary whitespace-nowrap">TR-2025-0895</td>
                                    <td class="px-space-md py-1 font-code-tabular text-on-surface-variant whitespace-nowrap">BB-008</td>
                                    <td class="px-space-md py-1">
                                        <span class="font-medium text-on-surface">Masterbatch Pewarna Hitam Granule</span>
                                        <span class="text-error text-label-sm block">Karantina QC: Kadar Kelembaban Tinggi</span>
                                    </td>
                                    <td class="px-space-md py-1 text-center">
                                        <span class="inline-block px-1.5 py-0.5 rounded font-code-tabular text-label-sm bg-[#8292E6]/20 text-[#bac4f7] font-semibold">BB</span>
                                    </td>
                                    <td class="px-space-md py-1 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-error-container/30 text-error text-label-sm font-medium">
                                            <span class="material-symbols-outlined text-[12px]">keyboard_return</span> Retur [R]
                                        </span>
                                    </td>
                                    <td class="px-space-md py-1 text-right font-code-tabular font-semibold text-error whitespace-nowrap">-50 Kg</td>
                                    <td class="px-space-md py-1 text-on-surface whitespace-nowrap">QC Reject Lead</td>
                                    <td class="px-space-md py-1 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-error-container text-on-error-container text-label-sm font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-error"></span> Return Issued
                                        </span>
                                    </td>
                                    <td class="px-space-md py-1 text-center whitespace-nowrap">
                                        <button class="h-6 w-6 rounded bg-surface-container hover:bg-surface-bright text-on-surface-variant hover:text-on-surface inline-flex items-center justify-center transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">more_vert</span>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Controls -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-space-sm pt-space-xs text-on-surface-variant font-body-sm text-body-sm">
                        <div class="flex items-center gap-space-sm font-code-tabular">
                            <span>Menampilkan 1-5 dari 482 entri mutasi per 24 jam</span>
                        </div>
                        <div class="flex items-center gap-1 self-end sm:self-auto font-label-md text-label-md">
                            <button class="h-7 px-2 rounded bg-surface-container-low hover:bg-surface-container text-outline hover:text-on-surface transition-colors flex items-center justify-center disabled:opacity-40" disabled="">
                                <span class="material-symbols-outlined text-[16px]">chevron_left</span>
                            </button>
                            <button class="h-7 w-7 rounded bg-primary-container text-on-primary-container flex items-center justify-center font-code-tabular">1</button>
                            <button class="h-7 w-7 rounded bg-surface-container-low hover:bg-surface-container text-on-surface flex items-center justify-center font-code-tabular transition-colors">2</button>
                            <button class="h-7 w-7 rounded bg-surface-container-low hover:bg-surface-container text-on-surface flex items-center justify-center font-code-tabular transition-colors">3</button>
                            <span class="px-1 text-outline">...</span>
                            <button class="h-7 w-7 rounded bg-surface-container-low hover:bg-surface-container text-on-surface flex items-center justify-center font-code-tabular transition-colors">97</button>
                            <button class="h-7 px-2 rounded bg-surface-container-low hover:bg-surface-container text-on-surface transition-colors flex items-center justify-center">
                                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- JS External -->
    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>
