<!DOCTYPE html>

<html class="dark" lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>FITS MOTOR - Admin Panel</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        body { font-family: 'DM Sans', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
<script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              "colors": {
                "secondary": "#ffd660",
                "surface-dim": "#111125",
                "background": "#111125",
                "surface-container": "#1e1e32",
                "surface-container-high": "#28283d",
                "surface-tint": "#ffb5a0",
                "tertiary-fixed-dim": "#74db9d",
                "error": "#ffb4ab",
                "outline": "#ad887e",
                "on-surface": "#e2e0fc",
                "on-secondary-fixed": "#241a00",
                "secondary-fixed": "#ffe08e",
                "on-secondary-fixed-variant": "#584400",
                "outline-variant": "#5d4038",
                "on-error-container": "#ffdad6",
                "secondary-fixed-dim": "#f1c014",
                "primary": "#ffb5a0",
                "tertiary": "#74db9d",
                "secondary-container": "#e8b800",
                "surface-variant": "#333348",
                "on-secondary-container": "#5f4a00",
                "tertiary-container": "#3aa36b",
                "on-secondary": "#3d2f00",
                "on-tertiary-fixed-variant": "#00522f",
                "on-tertiary-container": "#00311a",
                "admin-sidebar": "#0F1724",
                "surface-container-low": "#1a1a2e",
                "on-surface-variant": "#e7bdb2",
                "on-primary-fixed": "#3b0900",
                "on-primary-fixed-variant": "#872000",
                "danger-red": "#E53E3E",
                "on-error": "#690005",
                "on-background": "#e2e0fc",
                "primary-fixed": "#ffdbd1",
                "surface-container-highest": "#333348",
                "on-primary": "#601400",
                "surface-card": "#16213E",
                "surface-accent": "#0F3460",
                "on-tertiary": "#00391f",
                "surface-container-lowest": "#0c0c1f",
                "primary-fixed-dim": "#ffb5a0",
                "on-tertiary-fixed": "#002110",
                "surface": "#111125",
                "on-primary-container": "#541100",
                "text-secondary": "#A0AEC0",
                "primary-container": "#ff5625",
                "inverse-primary": "#b12d00",
                "surface-bright": "#37374d",
                "text-primary": "#FFFFFF",
                "inverse-on-surface": "#2f2e43",
                "error-container": "#93000a",
                "tertiary-fixed": "#91f8b8",
                "inverse-surface": "#e2e0fc"
              },
              "borderRadius": {
                "DEFAULT": "0.125rem",
                "lg": "0.25rem",
                "xl": "0.5rem",
                "full": "0.75rem"
              },
              "spacing": {
                "margin-mobile": "16px",
                "container-max": "1440px",
                "stack-lg": "24px",
                "gutter": "24px",
                "base": "8px",
                "stack-md": "12px",
                "stack-sm": "4px"
              },
              "fontFamily": {
                "title-lg": ["DM Sans"],
                "display-hero": ["Bebas Neue"],
                "data-mono": ["JetBrains Mono"],
                "headline-md": ["Bebas Neue"],
                "body-md": ["DM Sans"],
                "headline-lg": ["Bebas Neue"],
                "body-lg": ["DM Sans"],
                "label-md": ["DM Sans"]
              },
              "fontSize": {
                "title-lg": ["20px", {"lineHeight": "140%", "fontWeight": "700"}],
                "display-hero": ["96px", {"lineHeight": "100%", "letterSpacing": "0.02em", "fontWeight": "700"}],
                "data-mono": ["14px", {"lineHeight": "140%", "fontWeight": "500"}],
                "headline-md": ["32px", {"lineHeight": "120%", "letterSpacing": "0.05em", "fontWeight": "400"}],
                "body-md": ["16px", {"lineHeight": "160%", "fontWeight": "400"}],
                "headline-lg": ["48px", {"lineHeight": "120%", "letterSpacing": "0.05em", "fontWeight": "400"}],
                "body-lg": ["18px", {"lineHeight": "160%", "fontWeight": "400"}],
                "label-md": ["14px", {"lineHeight": "100%", "letterSpacing": "0.01em", "fontWeight": "600"}]
              }
            }
          }
        }
    </script>
</head>
<body class="bg-background text-on-background min-h-screen">
<!-- FRAME 1: ADMIN LOGIN PAGE (Hidden by default or shown separately) -->
<!-- Note: To see other frames, we would normally use routes, here we stack them for the demo -->
<section class="hidden flex items-center justify-center min-h-screen bg-surface-dim">
<div class="w-full max-w-[400px] bg-surface-container-low border border-white/5 p-8 rounded-xl shadow-2xl">
<div class="text-center mb-8">
<h1 class="font-headline-lg text-headline-lg text-primary tracking-wider">FITS MOTOR</h1>
<p class="font-label-md text-label-md text-on-surface-variant/60 uppercase tracking-[0.2em]">Admin Panel</p>
</div>
<form class="space-y-6">
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-2">Email Admin</label>
<input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none transition-all" placeholder="admin@fitsmotor.com" type="email"/>
</div>
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-2">Password</label>
<input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none transition-all" placeholder="••••••••" type="password"/>
</div>
<button class="w-full bg-primary-container hover:bg-inverse-primary text-white font-headline-md text-headline-md py-3 rounded-lg transition-all active:scale-95 uppercase" type="submit">
                    Masuk Sebagai Admin
                </button>
</form>
</div>
</section>
<!-- ADMIN LAYOUT SHELL (Frames 2-9) -->
<div class="flex min-h-screen">
<!-- Sidebar -->
<aside class="w-[240px] bg-admin-sidebar border-r border-white/5 flex flex-col fixed inset-y-0 left-0 z-50">
<div class="p-6">
<div class="flex flex-col gap-1 mb-6">
<span class="font-headline-md text-headline-md text-primary tracking-widest leading-none">FITS MOTOR</span>
<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-primary-container/20 text-primary-container w-fit uppercase">Admin Panel</span>
</div>
<div class="flex items-center gap-3 py-4 border-t border-primary/20">
<div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center border border-white/10">
<span class="material-symbols-outlined text-primary">account_circle</span>
</div>
<div>
<p class="font-label-md text-label-md text-on-surface">Budi Pratama</p>
<p class="text-[10px] text-on-surface-variant uppercase tracking-tighter">Administrator</p>
</div>
</div>
</div>
<nav class="flex-1 px-4 space-y-8 overflow-y-auto pb-8 custom-scrollbar">
<!-- Group -->
<div>
<p class="px-2 mb-2 text-[11px] font-bold text-on-surface-variant/40 tracking-widest">📊 OVERVIEW</p>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary-container/10 text-primary group transition-all" href="#">
<span class="material-symbols-outlined text-[20px]">dashboard</span>
<span class="font-label-md text-label-md">Dashboard</span>
</a>
</div>
<div>
<p class="px-2 mb-2 text-[11px] font-bold text-on-surface-variant/40 tracking-widest">📦 PRODUK</p>
<div class="space-y-1">
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-on-surface-variant hover:bg-white/5 hover:text-white transition-all" href="#">
<span class="material-symbols-outlined text-[20px]">inventory_2</span>
<span class="font-label-md text-label-md">Semua Produk</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-on-surface-variant hover:bg-white/5 hover:text-white transition-all" href="#">
<span class="material-symbols-outlined text-[20px]">add_box</span>
<span class="font-label-md text-label-md">Tambah Produk</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-on-surface-variant hover:bg-white/5 hover:text-white transition-all" href="#">
<span class="material-symbols-outlined text-[20px]">category</span>
<span class="font-label-md text-label-md">Kategori</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-on-surface-variant hover:bg-white/5 hover:text-white transition-all" href="#">
<span class="material-symbols-outlined text-[20px]">label</span>
<span class="font-label-md text-label-md">Brand</span>
</a>
</div>
</div>
<div>
<p class="px-2 mb-2 text-[11px] font-bold text-on-surface-variant/40 tracking-widest">💳 TRANSAKSI</p>
<div class="space-y-1">
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-on-surface-variant hover:bg-white/5 hover:text-white transition-all" href="#">
<span class="material-symbols-outlined text-[20px]">shopping_cart</span>
<span class="font-label-md text-label-md">Semua Pesanan</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-on-surface-variant hover:bg-white/5 hover:text-white transition-all" href="#">
<span class="material-symbols-outlined text-[20px]">calendar_today</span>
<span class="font-label-md text-label-md">Booking Servis</span>
</a>
</div>
</div>
<div>
<p class="px-2 mb-2 text-[11px] font-bold text-on-surface-variant/40 tracking-widest">👥 PELANGGAN</p>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-on-surface-variant hover:bg-white/5 hover:text-white transition-all" href="#">
<span class="material-symbols-outlined text-[20px]">group</span>
<span class="font-label-md text-label-md">Data Pelanggan</span>
</a>
</div>
<div>
<p class="px-2 mb-2 text-[11px] font-bold text-on-surface-variant/40 tracking-widest">📊 LAPORAN</p>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-on-surface-variant hover:bg-white/5 hover:text-white transition-all" href="#">
<span class="material-symbols-outlined text-[20px]">trending_up</span>
<span class="font-label-md text-label-md">Laporan Penjualan</span>
</a>
</div>
<div>
<p class="px-2 mb-2 text-[11px] font-bold text-on-surface-variant/40 tracking-widest">⚙️ PENGATURAN</p>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-on-surface-variant hover:bg-white/5 hover:text-white transition-all" href="#">
<span class="material-symbols-outlined text-[20px]">settings</span>
<span class="font-label-md text-label-md">Setting Toko</span>
</a>
</div>
</nav>
</aside>
<!-- Main Content Area -->
<main class="flex-1 ml-[240px] p-8">
<!-- Topbar -->
<header class="flex justify-between items-center mb-8 bg-surface-container-low/50 p-4 rounded-xl backdrop-blur-md border border-white/5">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-on-surface-variant">search</span>
<input class="bg-transparent border-none outline-none text-on-surface font-body-md w-96" placeholder="Cari pesanan, produk, atau pelanggan..." type="text"/>
</div>
<div class="flex items-center gap-6">
<button class="relative text-on-surface-variant hover:text-primary transition-colors">
<span class="material-symbols-outlined">notifications</span>
<span class="absolute -top-1 -right-1 w-4 h-4 bg-primary-container text-white text-[10px] flex items-center justify-center rounded-full">3</span>
</button>
<div class="h-8 w-px bg-white/10"></div>
<button class="flex items-center gap-2 text-danger-red hover:bg-danger-red/10 px-3 py-1.5 rounded-lg transition-all font-label-md">
<span class="material-symbols-outlined text-[20px]">logout</span>
                        Keluar
                    </button>
</div>
</header>
<!-- DASHBOARD CONTENT (FRAME 2) -->
<section id="frame-dashboard">
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-gutter mb-8">
<!-- Stat Card 1 -->
<div class="bg-surface-card border border-white/5 p-6 rounded-xl relative overflow-hidden group hover:border-primary-container/30 transition-all">
<div class="absolute -right-4 -bottom-4 opacity-10 group-hover:opacity-20 transition-all">
<span class="material-symbols-outlined text-[80px] text-primary-container">shopping_cart</span>
</div>
<p class="text-on-surface-variant text-[12px] uppercase font-bold tracking-wider mb-2">Pesanan Hari Ini</p>
<h3 class="font-headline-lg text-headline-lg text-primary-container">12</h3>
<p class="text-[10px] text-tertiary mt-2">↑ 8% dari kemarin</p>
</div>
<!-- Stat Card 2 -->
<div class="bg-surface-card border border-white/5 p-6 rounded-xl relative overflow-hidden group hover:border-tertiary/30 transition-all">
<div class="absolute -right-4 -bottom-4 opacity-10 group-hover:opacity-20 transition-all">
<span class="material-symbols-outlined text-[80px] text-tertiary">payments</span>
</div>
<p class="text-on-surface-variant text-[12px] uppercase font-bold tracking-wider mb-2">Revenue Bulan Ini</p>
<h3 class="font-headline-lg text-headline-lg text-tertiary">Rp 12.5M</h3>
<p class="text-[10px] text-on-surface-variant mt-2">Target: Rp 20M</p>
</div>
<!-- Stat Card 3 -->
<div class="bg-surface-card border border-white/5 p-6 rounded-xl relative overflow-hidden group hover:border-primary-container/30 transition-all">
<div class="absolute -right-4 -bottom-4 opacity-10 group-hover:opacity-20 transition-all">
<span class="material-symbols-outlined text-[80px] text-primary-container">new_releases</span>
</div>
<p class="text-on-surface-variant text-[12px] uppercase font-bold tracking-wider mb-2">Pesanan Baru</p>
<div class="flex items-center gap-3">
<h3 class="font-headline-lg text-headline-lg text-on-surface">5</h3>
<span class="bg-primary-container text-white text-[10px] px-2 py-0.5 rounded-full font-bold">URGENT</span>
</div>
</div>
<!-- Stat Card 4 -->
<div class="bg-surface-card border border-white/5 p-6 rounded-xl relative overflow-hidden group hover:border-danger-red/30 transition-all">
<div class="absolute -right-4 -bottom-4 opacity-10 group-hover:opacity-20 transition-all">
<span class="material-symbols-outlined text-[80px] text-danger-red">event_busy</span>
</div>
<p class="text-on-surface-variant text-[12px] uppercase font-bold tracking-wider mb-2">Booking Pending</p>
<div class="flex items-center gap-3">
<h3 class="font-headline-lg text-headline-lg text-danger-red">3</h3>
<span class="bg-danger-red/20 text-danger-red text-[10px] px-2 py-0.5 rounded-full font-bold">ACTION</span>
</div>
</div>
<!-- Stat Card 5 -->
<div class="bg-surface-card border border-white/5 p-6 rounded-xl relative overflow-hidden group hover:border-secondary/30 transition-all">
<div class="absolute -right-4 -bottom-4 opacity-10 group-hover:opacity-20 transition-all">
<span class="material-symbols-outlined text-[80px] text-secondary">groups</span>
</div>
<p class="text-on-surface-variant text-[12px] uppercase font-bold tracking-wider mb-2">Total Customer</p>
<h3 class="font-headline-lg text-headline-lg text-secondary">248</h3>
<p class="text-[10px] text-tertiary mt-2">+12 Minggu ini</p>
</div>
<!-- Stat Card 6 -->
<div class="bg-surface-card border border-white/5 p-6 rounded-xl relative overflow-hidden group hover:border-secondary-container/30 transition-all">
<div class="absolute -right-4 -bottom-4 opacity-10 group-hover:opacity-20 transition-all">
<span class="material-symbols-outlined text-[80px] text-secondary-container">inventory</span>
</div>
<p class="text-on-surface-variant text-[12px] uppercase font-bold tracking-wider mb-2">Stok Hampir Habis</p>
<h3 class="font-headline-lg text-headline-lg text-secondary-container">7</h3>
<p class="text-[10px] text-secondary-container mt-2">Restock suggested</p>
</div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
<!-- Recent Orders Table -->
<div class="lg:col-span-2 bg-surface-card border border-white/5 rounded-xl p-6">
<div class="flex justify-between items-center mb-6">
<h2 class="font-headline-md text-headline-md text-on-surface">Pesanan Terbaru</h2>
<a class="text-primary font-label-md text-label-md hover:underline transition-all" href="#">Lihat Semua</a>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left">
<thead class="border-b border-white/5 text-on-surface-variant font-label-md text-label-md">
<tr>
<th class="py-4 px-2">No</th>
<th class="py-4 px-2">Customer</th>
<th class="py-4 px-2">Total</th>
<th class="py-4 px-2">Status</th>
<th class="py-4 px-2 text-right">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-white/5 font-body-md">
<tr class="hover:bg-white/[0.02] transition-colors">
<td class="py-4 px-2 font-data-mono">#ORD-9021</td>
<td class="py-4 px-2">Rizky Febian</td>
<td class="py-4 px-2">Rp 1.450.000</td>
<td class="py-4 px-2">
<span class="px-2 py-0.5 rounded-full text-[10px] bg-secondary-container/10 text-secondary-container border border-secondary-container/20 uppercase font-bold">Dikirim</span>
</td>
<td class="py-4 px-2 text-right">
<button class="bg-surface-accent px-3 py-1 rounded text-primary text-[12px] font-bold hover:bg-primary-container hover:text-white transition-all">DETAIL</button>
</td>
</tr>
<tr class="hover:bg-white/[0.02] transition-colors">
<td class="py-4 px-2 font-data-mono">#ORD-9020</td>
<td class="py-4 px-2">Siska Kohl</td>
<td class="py-4 px-2">Rp 8.900.000</td>
<td class="py-4 px-2">
<span class="px-2 py-0.5 rounded-full text-[10px] bg-tertiary/10 text-tertiary border border-tertiary/20 uppercase font-bold">Selesai</span>
</td>
<td class="py-4 px-2 text-right">
<button class="bg-surface-accent px-3 py-1 rounded text-primary text-[12px] font-bold hover:bg-primary-container hover:text-white transition-all">DETAIL</button>
</td>
</tr>
<tr class="hover:bg-white/[0.02] transition-colors">
<td class="py-4 px-2 font-data-mono">#ORD-9019</td>
<td class="py-4 px-2">Andre Taulany</td>
<td class="py-4 px-2">Rp 250.000</td>
<td class="py-4 px-2">
<span class="px-2 py-0.5 rounded-full text-[10px] bg-primary-container/10 text-primary-container border border-primary-container/20 uppercase font-bold">Baru</span>
</td>
<td class="py-4 px-2 text-right">
<button class="bg-surface-accent px-3 py-1 rounded text-primary text-[12px] font-bold hover:bg-primary-container hover:text-white transition-all">DETAIL</button>
</td>
</tr>
<tr class="hover:bg-white/[0.02] transition-colors">
<td class="py-4 px-2 font-data-mono">#ORD-9018</td>
<td class="py-4 px-2">Raffi Ahmad</td>
<td class="py-4 px-2">Rp 15.200.000</td>
<td class="py-4 px-2">
<span class="px-2 py-0.5 rounded-full text-[10px] bg-danger-red/10 text-danger-red border border-danger-red/20 uppercase font-bold">Pending</span>
</td>
<td class="py-4 px-2 text-right">
<button class="bg-surface-accent px-3 py-1 rounded text-primary text-[12px] font-bold hover:bg-primary-container hover:text-white transition-all">DETAIL</button>
</td>
</tr>
<tr class="hover:bg-white/[0.02] transition-colors">
<td class="py-4 px-2 font-data-mono">#ORD-9017</td>
<td class="py-4 px-2">Denny Cagur</td>
<td class="py-4 px-2">Rp 1.100.000</td>
<td class="py-4 px-2">
<span class="px-2 py-0.5 rounded-full text-[10px] bg-tertiary/10 text-tertiary border border-tertiary/20 uppercase font-bold">Selesai</span>
</td>
<td class="py-4 px-2 text-right">
<button class="bg-surface-accent px-3 py-1 rounded text-primary text-[12px] font-bold hover:bg-primary-container hover:text-white transition-all">DETAIL</button>
</td>
</tr>
</tbody>
</table>
</div>
</div>
<!-- Upcoming Bookings -->
<div class="bg-surface-card border border-white/5 rounded-xl p-6">
<h2 class="font-headline-md text-headline-md text-on-surface mb-6">Booking Hari Ini</h2>
<div class="space-y-4">
<div class="bg-surface-container-low border-l-4 border-primary-container p-4 rounded-r-lg group hover:bg-surface-container-high transition-all cursor-pointer">
<div class="flex justify-between items-start mb-2">
<p class="font-data-mono text-[12px] text-primary-container">09:00 WIB</p>
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-all">arrow_forward</span>
</div>
<p class="font-bold text-on-surface">Agus Ringgo</p>
<p class="text-[12px] text-on-surface-variant">Honda CBR 1000RR - Servis Rutin</p>
</div>
<div class="bg-surface-container-low border-l-4 border-tertiary p-4 rounded-r-lg group hover:bg-surface-container-high transition-all cursor-pointer">
<div class="flex justify-between items-start mb-2">
<p class="font-data-mono text-[12px] text-tertiary">13:30 WIB</p>
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-all">arrow_forward</span>
</div>
<p class="font-bold text-on-surface">Dimas Seto</p>
<p class="text-[12px] text-on-surface-variant">Kawasaki ZX-25R - Upgrade Rem</p>
</div>
<div class="bg-surface-container-low border-l-4 border-secondary p-4 rounded-r-lg group hover:bg-surface-container-high transition-all cursor-pointer">
<div class="flex justify-between items-start mb-2">
<p class="font-data-mono text-[12px] text-secondary">15:00 WIB</p>
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-all">arrow_forward</span>
</div>
<p class="font-bold text-on-surface">Ibnu Jamil</p>
<p class="text-[12px] text-on-surface-variant">Yamaha R1 - Ganti Ban</p>
</div>
</div>
</div>
</div>
</section>
<!-- FRAME 3: KELOLA PRODUK (List) (Hidden example layout) -->
<section class="mt-16 pt-16 border-t border-white/5" id="frame-products">
<div class="flex justify-between items-center mb-8">
<h2 class="font-headline-lg text-headline-lg text-on-surface">Semua Produk</h2>
<button class="bg-primary-container hover:bg-inverse-primary text-white flex items-center gap-2 px-6 py-2.5 rounded-lg transition-all font-headline-md">
<span class="material-symbols-outlined">add</span>
                        Tambah Produk
                    </button>
</div>
<div class="bg-surface-card border border-white/5 rounded-xl overflow-hidden">
<table class="w-full text-left">
<thead class="bg-surface-container text-on-surface-variant font-label-md text-label-md">
<tr>
<th class="py-4 px-6 w-10 text-center"><input class="rounded bg-surface-container-lowest border-white/10 text-primary-container focus:ring-primary-container" type="checkbox"/></th>
<th class="py-4 px-4">Foto</th>
<th class="py-4 px-4">Nama Produk</th>
<th class="py-4 px-4">Kategori</th>
<th class="py-4 px-4">Harga</th>
<th class="py-4 px-4 text-center">Stok</th>
<th class="py-4 px-4 text-center">Status</th>
<th class="py-4 px-4 text-right">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-white/5 font-body-md">
<tr class="hover:bg-white/[0.02] transition-colors">
<td class="py-4 px-6 text-center"><input class="rounded bg-surface-container-lowest border-white/10 text-primary-container focus:ring-primary-container" type="checkbox"/></td>
<td class="py-4 px-4">
<div class="w-12 h-12 bg-surface-container-high rounded-lg overflow-hidden border border-white/5">
<img class="w-full h-full object-cover" data-alt="A premium high-performance motorcycle engine part, showcasing precision engineering with a metallic finish and industrial aesthetic. The image features dramatic lighting against a dark navy background, reflecting professional workshop quality." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDll12X3uyPa98rZ1pZTkdlQNVdrZ8N9vA4vZvkjmxyQiv6_D5h_ULh-tfKGfLanWz2oRQenVB8hMtIFx5TG84OA9dllmCj7QFVmKpXGbJilHR-14moojwwF6bwJbdq2ZVbZstQbhJVhCPy0aCh79dVnd-7F4NxGNzhBWdCfXPJrkGWsKjUwLrd9CH3Kd6_3-LJaJddow-8vjVJXZ_eUjuK0y95Ha2uE7QiuiUJJJ5vq7Ao5v86oHUrzXoXLrKY7a66N8cnVhjfMU0"/>
</div>
</td>
<td class="py-4 px-4">
<p class="font-bold text-on-surface">Oli Motul 7100 4T 10W-40</p>
<p class="text-[11px] text-on-surface-variant font-data-mono">SKU: LUB-7100-M4</p>
</td>
<td class="py-4 px-4"><span class="bg-surface-accent px-2 py-1 rounded text-[12px]">Pelumas</span></td>
<td class="py-4 px-4 font-data-mono">Rp 185.000</td>
<td class="py-4 px-4 text-center">42</td>
<td class="py-4 px-4 text-center">
<span class="text-tertiary text-[12px] font-bold flex items-center justify-center gap-1">
<span class="w-2 h-2 rounded-full bg-tertiary"></span> Aktif
                                    </span>
</td>
<td class="py-4 px-4 text-right">
<div class="flex justify-end gap-2">
<button class="p-2 bg-surface-container-high text-on-surface hover:text-primary transition-all rounded"><span class="material-symbols-outlined text-[18px]">edit</span></button>
<button class="p-2 bg-surface-container-high text-on-surface hover:text-danger-red transition-all rounded"><span class="material-symbols-outlined text-[18px]">delete</span></button>
</div>
</td>
</tr>
<!-- Repeat Row 2 -->
<tr class="hover:bg-white/[0.02] transition-colors">
<td class="py-4 px-6 text-center"><input class="rounded bg-surface-container-lowest border-white/10 text-primary-container focus:ring-primary-container" type="checkbox"/></td>
<td class="py-4 px-4">
<div class="w-12 h-12 bg-surface-container-high rounded-lg overflow-hidden border border-white/5">
<img class="w-full h-full object-cover" data-alt="Close-up of a high-end motorcycle brake disc with carbon ceramic details and red calipers. The scene is shot in a professional racing garage with cool-toned studio lighting, highlighting technical precision and expensive performance materials." src="https://lh3.googleusercontent.com/aida-public/AB6AXuD0I4wt5SOEpnCm7YfzAgHG2iFybe9npPZrVBw7w-HDoRvJk-UL-Uywu82hGGiwEDplOfO86Orgk0f2JEcI5WnvkYMYaYO0Ag4vzyZIajGzWjbebZJgf-Qq2t0pUByq4ueq2uoWEoQ-8hX6o0qeOXFNosY5ncXGesMmjT_IPS7VtraAfKSWQ2jh3TMvGwk0sm-yLEXeKdOMFQxSOlfEOcnqFcOxU7JX95z8LOlx4Dhsfw2Fa0Yki4LtPywGXVbNJD72x-poNLUpv8M"/>
</div>
</td>
<td class="py-4 px-4">
<p class="font-bold text-on-surface">Brembo RC Carbon Brake Pad</p>
<p class="text-[11px] text-on-surface-variant font-data-mono">SKU: BRK-BMB-RC</p>
</td>
<td class="py-4 px-4"><span class="bg-surface-accent px-2 py-1 rounded text-[12px]">Pengereman</span></td>
<td class="py-4 px-4 font-data-mono">Rp 1.250.000</td>
<td class="py-4 px-4 text-center text-secondary-container font-bold">5</td>
<td class="py-4 px-4 text-center">
<span class="text-tertiary text-[12px] font-bold flex items-center justify-center gap-1">
<span class="w-2 h-2 rounded-full bg-tertiary"></span> Aktif
                                    </span>
</td>
<td class="py-4 px-4 text-right">
<div class="flex justify-end gap-2">
<button class="p-2 bg-surface-container-high text-on-surface hover:text-primary transition-all rounded"><span class="material-symbols-outlined text-[18px]">edit</span></button>
<button class="p-2 bg-surface-container-high text-on-surface hover:text-danger-red transition-all rounded"><span class="material-symbols-outlined text-[18px]">delete</span></button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
</section>
<!-- FRAME 4: FORM TAMBAH/EDIT PRODUK -->
<section class="mt-16 pt-16 border-t border-white/5" id="frame-product-form">
<div class="flex items-center gap-4 mb-8">
<button class="w-10 h-10 rounded-lg bg-surface-container-high flex items-center justify-center border border-white/5 hover:text-primary transition-all">
<span class="material-symbols-outlined">arrow_back</span>
</button>
<h2 class="font-headline-lg text-headline-lg text-on-surface">Tambah Produk Baru</h2>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
<!-- Left Column -->
<div class="lg:col-span-2 space-y-gutter">
<div class="bg-surface-card border border-white/5 rounded-xl p-6 space-y-6">
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-2">Nama Produk</label>
<input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none" placeholder="Contoh: Knalpot Akrapovic Titanium Slip-On" type="text"/>
</div>
<div class="grid grid-cols-2 gap-4">
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-2">Slug</label>
<input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface-variant/50 focus:border-primary-container outline-none font-data-mono text-[12px]" readonly="" type="text" value="knalpot-akrapovic-titanium-slip-on"/>
</div>
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-2">SKU</label>
<input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none font-data-mono" placeholder="SKU-XXX" type="text"/>
</div>
</div>
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-2">Deskripsi</label>
<textarea class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none" placeholder="Jelaskan detail produk secara lengkap..." rows="6"></textarea>
</div>
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-2">Spesifikasi &amp; Kompatibilitas</label>
<textarea class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none" placeholder="Daftar kompatibilitas motor dan spesifikasi teknis..." rows="4"></textarea>
</div>
</div>
</div>
<!-- Right Column -->
<div class="space-y-gutter">
<div class="bg-surface-card border border-white/5 rounded-xl p-6 space-y-6">
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-2">Foto Produk</label>
<div class="border-2 border-dashed border-white/10 rounded-xl p-8 flex flex-col items-center justify-center text-center group hover:border-primary-container/50 transition-all cursor-pointer">
<span class="material-symbols-outlined text-[48px] text-on-surface-variant mb-4 group-hover:text-primary">upload_file</span>
<p class="font-label-md text-on-surface mb-1">Klik untuk upload</p>
<p class="text-[10px] text-on-surface-variant uppercase">PNG, JPG up to 5MB</p>
</div>
</div>
<div class="grid grid-cols-2 gap-4">
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-2">Kategori</label>
<select class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none">
<option>Pilih Kategori</option>
<option>Suku Cadang</option>
<option>Aksesoris</option>
<option>Pelumas</option>
</select>
</div>
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-2">Brand</label>
<select class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none">
<option>Pilih Brand</option>
<option>Akrapovic</option>
<option>Brembo</option>
<option>Ohlins</option>
</select>
</div>
</div>
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-2">Harga Jual (IDR)</label>
<input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none font-data-mono" placeholder="0" type="number"/>
</div>
<div class="grid grid-cols-2 gap-4">
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-2">Stok</label>
<input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none" placeholder="0" type="number"/>
</div>
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-2">Berat (gr)</label>
<input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none" placeholder="0" type="number"/>
</div>
</div>
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-2">Status Produk</label>
<div class="flex gap-4">
<label class="flex-1 cursor-pointer">
<input checked="" class="hidden peer" name="status" type="radio"/>
<div class="p-3 text-center border border-white/10 rounded-lg peer-checked:bg-primary-container/20 peer-checked:border-primary-container peer-checked:text-primary transition-all">Aktif</div>
</label>
<label class="flex-1 cursor-pointer">
<input class="hidden peer" name="status" type="radio"/>
<div class="p-3 text-center border border-white/10 rounded-lg peer-checked:bg-danger-red/20 peer-checked:border-danger-red peer-checked:text-danger-red transition-all">Draft</div>
</label>
</div>
</div>
<button class="w-full bg-primary-container text-white font-headline-md py-4 rounded-lg uppercase shadow-xl hover:scale-[1.02] active:scale-95 transition-all">Simpan Produk</button>
</div>
</div>
</div>
</section>
<!-- FRAME 6: DETAIL PESANAN -->
<section class="mt-16 pt-16 border-t border-white/5" id="frame-order-detail">
<div class="flex justify-between items-end mb-8">
<div>
<div class="flex items-center gap-3 mb-2">
<h2 class="font-headline-lg text-headline-lg text-on-surface">Pesanan #ORD-9021</h2>
<span class="bg-secondary-container/10 text-secondary-container border border-secondary-container/20 px-3 py-1 rounded text-[12px] font-bold uppercase">Dalam Pengiriman</span>
</div>
<p class="text-on-surface-variant font-body-md">Dipesan pada 24 Okt 2024, 14:20 WIB</p>
</div>
<div class="flex gap-4">
<button class="bg-surface-container-high px-4 py-2 rounded-lg font-label-md flex items-center gap-2 hover:text-primary transition-all">
<span class="material-symbols-outlined text-[18px]">print</span> Cetak Invoice
                        </button>
<button class="bg-primary-container text-white px-6 py-2 rounded-lg font-headline-md uppercase">Update Status</button>
</div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
<div class="lg:col-span-2 space-y-gutter">
<!-- Items List -->
<div class="bg-surface-card border border-white/5 rounded-xl p-6">
<h3 class="font-headline-md text-headline-md text-on-surface mb-6">Daftar Item</h3>
<div class="space-y-4">
<div class="flex items-center gap-4 py-4 border-b border-white/5">
<div class="w-16 h-16 bg-surface-container rounded-lg overflow-hidden border border-white/5">
<img class="w-full h-full object-cover" data-alt="Close-up of premium synthetic motor oil being poured into a high-performance motorcycle engine, illuminated by golden studio lighting that emphasizes fluid texture and engineering precision." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDEek9471-yBOQYiXblxOFFSM-RTQ6LG3xFjlloxJlm18_EXHv8ot1_FOCvDCwRrswWT4kZWi486qKIf5dADvOQmLPuoTHmth55_SHCaBCK7dMsyiNiiZxwM7BkCsMiQSah0NWb5pxU1YF8qjB7o9Vvmd6n1qWW_eAyYpJnFDQ69c61p42YfG7jVDUdOjYYH0X-n5p1-JGNJpg6pKEREOqJ49YOnYIVxK5QasbhnVwETaI09fZf7q3rsYkQLVnAZy7x9D1v7fZ-Lnk"/>
</div>
<div class="flex-1">
<p class="font-bold text-on-surface">Oli Motul 7100 4T 10W-40</p>
<p class="text-[12px] text-on-surface-variant">Qty: 2 x Rp 185.000</p>
</div>
<p class="font-data-mono text-on-surface">Rp 370.000</p>
</div>
<div class="flex items-center gap-4 py-4">
<div class="w-16 h-16 bg-surface-container rounded-lg overflow-hidden border border-white/5">
<img class="w-full h-full object-cover" data-alt="High-performance motorcycle brake system component, featuring ceramic discs and specialized pads in a moody, industrial garage setting with cyan and orange lighting accents." src="https://lh3.googleusercontent.com/aida-public/AB6AXuADU83TH1tuTT5FPW6hJglHeTzzW7kbCIsE7d8oCQQGRXQUuAoMr7XVT_qmqDIePJkyC42mNcxF48Trpx6WT1UkVu3pRtVXLKw9_DiSk6JaBW1a4j0evi6p52h8J9W4NW342aQJ5SYNlQikUBoxwwAJpWMejNmbBgUed0SXuG9tHXXbrVHBEax1BCrVthl5tPOzwNh6fzmIBHZsVdXIw8XdMuZ_c9lym2o9K9EX1DJjFdTqixt_uuDTqf4DkEkW0Aqdv4n9vNfAqVQ"/>
</div>
<div class="flex-1">
<p class="font-bold text-on-surface">Brembo RC Carbon Brake Pad</p>
<p class="text-[12px] text-on-surface-variant">Qty: 1 x Rp 1.250.000</p>
</div>
<p class="font-data-mono text-on-surface">Rp 1.250.000</p>
</div>
</div>
<div class="mt-8 pt-8 border-t border-white/5 space-y-2">
<div class="flex justify-between text-on-surface-variant">
<span>Subtotal</span>
<span class="font-data-mono">Rp 1.620.000</span>
</div>
<div class="flex justify-between text-on-surface-variant">
<span>Ongkos Kirim (JNE YES)</span>
<span class="font-data-mono">Rp 45.000</span>
</div>
<div class="flex justify-between text-headline-md font-headline-md text-primary pt-2">
<span>TOTAL</span>
<span>Rp 1.665.000</span>
</div>
</div>
</div>
</div>
<!-- Right Column Info -->
<div class="space-y-gutter">
<div class="bg-surface-card border border-white/5 rounded-xl p-6">
<h3 class="font-label-md text-label-md text-on-surface-variant uppercase mb-4 tracking-widest">Informasi Customer</h3>
<div class="space-y-4">
<div>
<p class="font-bold text-on-surface">Rizky Febian</p>
<p class="text-[14px] text-on-surface-variant">rizky.f@gmail.com</p>
<p class="text-[14px] text-on-surface-variant">+62 812-3456-7890</p>
</div>
<div class="pt-4 border-t border-white/5">
<p class="text-[12px] text-on-surface-variant uppercase mb-1">Alamat Pengiriman</p>
<p class="text-[14px] text-on-surface">Jl. Melati No. 12, Tebet, Jakarta Selatan, DKI Jakarta 12810</p>
</div>
</div>
</div>
<div class="bg-surface-accent border border-primary-container/20 rounded-xl p-6">
<h3 class="font-label-md text-label-md text-primary uppercase mb-4 tracking-widest">Action Required</h3>
<div class="space-y-4">
<div>
<label class="block text-[12px] text-on-surface-variant mb-2">Input Nomor Resi</label>
<input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none font-data-mono" placeholder="JNE123456789" type="text"/>
</div>
<button class="w-full bg-primary-container text-white py-3 rounded-lg font-bold uppercase transition-all hover:bg-inverse-primary">Simpan &amp; Proses</button>
</div>
</div>
</div>
</div>
</section>
<!-- FRAME 9: SETTING TOKO -->
<section class="mt-16 pt-16 border-t border-white/5 pb-32" id="frame-settings">
<h2 class="font-headline-lg text-headline-lg text-on-surface mb-8">Setting Toko</h2>
<div class="grid grid-cols-1 md:grid-cols-4 gap-gutter">
<!-- Nav Tabs -->
<div class="space-y-2">
<button class="w-full text-left px-4 py-3 rounded-lg bg-primary-container/10 text-primary font-label-md border-r-2 border-primary">Informasi Toko</button>
<button class="w-full text-left px-4 py-3 rounded-lg text-on-surface-variant hover:bg-white/5 font-label-md">WhatsApp Integrasi</button>
<button class="w-full text-left px-4 py-3 rounded-lg text-on-surface-variant hover:bg-white/5 font-label-md">Metode Pengiriman</button>
<button class="w-full text-left px-4 py-3 rounded-lg text-on-surface-variant hover:bg-white/5 font-label-md">Payment Gateway</button>
<button class="w-full text-left px-4 py-3 rounded-lg text-on-surface-variant hover:bg-white/5 font-label-md">Sosial Media</button>
</div>
<!-- Content -->
<div class="md:col-span-3">
<div class="bg-surface-card border border-white/5 rounded-xl p-8 space-y-8">
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-2">Nama Toko</label>
<input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface" type="text" value="FITS MOTOR PREMIUM WORKSHOP"/>
</div>
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-2">Slogan</label>
<input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface" type="text" value="Precision in Every Gear"/>
</div>
<div class="md:col-span-2">
<label class="block font-label-md text-label-md text-on-surface-variant mb-2">Alamat Fisik Workshop</label>
<textarea class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface" rows="3">Kawasan Industri Otomotif Blok A2, Gading Serpong, Tangerang, Banten</textarea>
</div>
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-2">Email Bisnis</label>
<input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface" type="email" value="hello@fitsmotor.com"/>
</div>
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-2">Jam Operasional</label>
<input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface" type="text" value="Senin - Sabtu (09:00 - 18:00)"/>
</div>
</div>
<div class="pt-6 border-t border-white/5 flex justify-end">
<button class="bg-primary-container text-white px-8 py-3 rounded-lg font-headline-md uppercase tracking-wider">Simpan Perubahan</button>
</div>
</div>
</div>
</div>
</section>
</main>
</div>
<!-- Footer for Admin (Simple copyright) -->
<footer class="ml-[240px] bg-surface-container-lowest py-6 px-8 border-t border-white/5 text-center text-on-surface-variant/40 text-[12px] font-label-md">
        © 2024 FITS MOTOR. Premium Motorcycle Workshop Admin Panel v2.4.1.
    </footer>
</body></html>