<!DOCTYPE html>

<html class="dark" lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "tertiary-container": "#3aa36b",
                        "primary-fixed": "#ffdbd1",
                        "primary-fixed-dim": "#ffb5a0",
                        "outline": "#ad887e",
                        "error-container": "#93000a",
                        "on-error-container": "#ffdad6",
                        "secondary": "#ffd660",
                        "secondary-fixed": "#ffe08e",
                        "on-primary-fixed-variant": "#872000",
                        "background": "#111125",
                        "text-secondary": "#A0AEC0",
                        "surface-container-high": "#28283d",
                        "secondary-fixed-dim": "#f1c014",
                        "on-secondary-container": "#5f4a00",
                        "surface-tint": "#ffb5a0",
                        "danger-red": "#E53E3E",
                        "surface-container-low": "#1a1a2e",
                        "surface-variant": "#333348",
                        "inverse-on-surface": "#2f2e43",
                        "surface-bright": "#37374d",
                        "surface": "#111125",
                        "primary-container": "#ff5625",
                        "on-secondary": "#3d2f00",
                        "on-primary": "#601400",
                        "tertiary-fixed": "#91f8b8",
                        "tertiary": "#74db9d",
                        "on-tertiary-fixed-variant": "#00522f",
                        "on-surface-variant": "#e7bdb2",
                        "surface-card": "#16213E",
                        "on-surface": "#e2e0fc",
                        "error": "#ffb4ab",
                        "surface-accent": "#0F3460",
                        "secondary-container": "#e8b800",
                        "on-primary-container": "#541100",
                        "surface-dim": "#111125",
                        "primary": "#ffb5a0",
                        "on-primary-fixed": "#3b0900",
                        "on-background": "#e2e0fc",
                        "surface-container-lowest": "#0c0c1f",
                        "admin-sidebar": "#0F1724",
                        "outline-variant": "#5d4038",
                        "on-secondary-fixed-variant": "#584400",
                        "tertiary-fixed-dim": "#74db9d",
                        "on-tertiary-fixed": "#002110",
                        "on-secondary-fixed": "#241a00",
                        "on-tertiary": "#00391f",
                        "on-error": "#690005",
                        "surface-container-highest": "#333348",
                        "inverse-surface": "#e2e0fc",
                        "inverse-primary": "#b12d00",
                        "surface-container": "#1e1e32",
                        "on-tertiary-container": "#00311a",
                        "text-primary": "#FFFFFF"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "stack-md": "12px",
                        "stack-sm": "4px",
                        "margin-mobile": "16px",
                        "container-max": "1440px",
                        "base": "8px",
                        "gutter": "24px",
                        "stack-lg": "24px"
                    },
                    "fontFamily": {
                        "display-hero": ["Bebas Neue"],
                        "body-md": ["DM Sans"],
                        "headline-lg": ["Bebas Neue"],
                        "headline-md": ["Bebas Neue"],
                        "body-lg": ["DM Sans"],
                        "display-hero-mobile": ["Bebas Neue"],
                        "title-lg": ["DM Sans"],
                        "data-mono": ["JetBrains Mono"],
                        "label-md": ["DM Sans"]
                    },
                    "fontSize": {
                        "display-hero": ["96px", {"lineHeight": "100%", "letterSpacing": "0.02em", "fontWeight": "700"}],
                        "body-md": ["16px", {"lineHeight": "160%", "fontWeight": "400"}],
                        "headline-lg": ["48px", {"lineHeight": "120%", "letterSpacing": "0.05em", "fontWeight": "400"}],
                        "headline-md": ["32px", {"lineHeight": "120%", "letterSpacing": "0.05em", "fontWeight": "400"}],
                        "body-lg": ["18px", {"lineHeight": "160%", "fontWeight": "400"}],
                        "display-hero-mobile": ["48px", {"lineHeight": "110%", "letterSpacing": "0.02em", "fontWeight": "700"}],
                        "title-lg": ["20px", {"lineHeight": "140%", "fontWeight": "700"}],
                        "data-mono": ["14px", {"lineHeight": "140%", "fontWeight": "500"}],
                        "label-md": ["14px", {"lineHeight": "100%", "letterSpacing": "0.01em", "fontWeight": "600"}]
                    }
                },
            },
        }
    </script>
<style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #333348; border-radius: 10px; }
        .glass-panel { backdrop-filter: blur(12px); background: rgba(22, 33, 62, 0.8); }
        .active-nav { background: rgba(255, 86, 37, 0.1); border-left: 4px solid #ff5625; color: #FFFFFF; }
        .inactive-nav { border-left: 4px solid transparent; color: #A0AEC0; }
        .inactive-nav:hover { background: rgba(255, 255, 255, 0.05); color: #FFFFFF; }
    </style>
</head>
<body class="bg-background text-on-background font-body-md selection:bg-primary-container selection:text-white">
<!-- TOP NAVIGATION (As per Shared Components) -->
<header class="fixed top-0 w-full z-50 bg-background/80 backdrop-blur-xl border-b border-white/10 shadow-lg h-20 flex items-center">
<div class="flex justify-between items-center w-full px-8 max-w-[1440px] mx-auto">
<div class="font-headline-md text-headline-md text-primary tracking-wider">FITS MOTOR</div>
<nav class="hidden md:flex gap-8">
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Beranda</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Produk</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Booking</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Tentang</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Kontak</a>
</nav>
<div class="flex items-center gap-6">
<span class="material-symbols-outlined text-on-surface-variant cursor-pointer hover:text-primary transition-colors" data-icon="shopping_cart">shopping_cart</span>
<button class="bg-tertiary-container text-white px-6 py-2 font-label-md text-label-md rounded hover:bg-opacity-90 active:scale-95 transition-all">Konsultasi WA</button>
</div>
</div>
</header>
<div class="flex min-h-screen pt-20">
<!-- FRAME 1: LEFT SIDEBAR NAVIGATION -->
<aside class="w-64 bg-admin-sidebar border-r border-white/5 hidden md:flex flex-col fixed h-[calc(100vh-80px)]">
<div class="p-8">
<div class="flex items-center gap-4 mb-8">
<img alt="Budi Avatar" class="w-12 h-12 rounded-full border-2 border-primary object-cover" data-alt="A professional close-up studio portrait of a friendly Indonesian man in his 30s with a warm smile, set against a dark, moody industrial background with soft dramatic lighting. The image captures a clean, premium corporate headshot aesthetic, emphasizing trust and professionalism with high technical detail and balanced shadows." src="https://lh3.googleusercontent.com/aida-public/AB6AXuC3gISX1jncXb8WSRO8roiPI_80LpSvsrZ893O-rOGLpA4MxMUxFxQHoGCsOOyxqPzquM4dBubgqHsRmGZqvu_0KbESVnmrf7p5_MwUVUdcHaaixpwe2dxie8kc82q3YXj6E1guDeHdnU5IjOjabE0fclLUj6v1y1cy_g9hVvGOkTAmrFsa9xrov7eTwfcQCJ6f-uiIz-5KpHWIz-eWeMbfvwLf3RybYZwfZIU1Gr8U8DnPVlIcFvLXaYIep5hrfdt8vQlHc6eNgT4"/>
<div>
<p class="text-on-surface-variant text-sm">Selamat Datang,</p>
<h3 class="font-title-lg text-title-lg text-white">Budi! 👋</h3>
</div>
</div>
<nav class="space-y-2">
<a class="flex items-center gap-3 px-4 py-3 active-nav transition-all" href="#dashboard">
<span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
<span class="font-label-md text-label-md">Dashboard</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 inactive-nav transition-all" href="#pesanan">
<span class="material-symbols-outlined" data-icon="shopping_bag">shopping_bag</span>
<span class="font-label-md text-label-md">Pesanan Saya</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 inactive-nav transition-all" href="#booking">
<span class="material-symbols-outlined" data-icon="calendar_today">calendar_today</span>
<span class="font-label-md text-label-md">Booking Saya</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 inactive-nav transition-all" href="#alamat">
<span class="material-symbols-outlined" data-icon="location_on">location_on</span>
<span class="font-label-md text-label-md">Alamat</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 inactive-nav transition-all" href="#profil">
<span class="material-symbols-outlined" data-icon="person">person</span>
<span class="font-label-md text-label-md">Profil</span>
</a>
<div class="pt-8 mt-8 border-t border-white/5">
<a class="flex items-center gap-3 px-4 py-3 text-danger-red hover:bg-danger-red/10 transition-all rounded" href="#keluar">
<span class="material-symbols-outlined" data-icon="logout">logout</span>
<span class="font-label-md text-label-md">Keluar</span>
</a>
</div>
</nav>
</div>
</aside>
<!-- MAIN CONTENT AREA -->
<main class="flex-1 md:ml-64 p-8 bg-background max-w-[1440px]">
<!-- FRAME 1: CUSTOMER DASHBOARD CONTENT -->
<section class="mb-16" id="dashboard">
<h1 class="font-headline-md text-headline-md text-white mb-8">DASHBOARD CUSTOMER</h1>
<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-12">
<div class="bg-surface-card p-6 border border-white/5 rounded-lg">
<div class="flex justify-between items-start mb-4">
<span class="material-symbols-outlined text-primary p-3 bg-primary/10 rounded" data-icon="receipt_long">receipt_long</span>
<span class="text-xs text-on-surface-variant font-label-md">Total Pesanan</span>
</div>
<h2 class="font-headline-md text-headline-md text-white">8</h2>
<p class="text-sm text-tertiary mt-2">Semua transaksi sukses</p>
</div>
<div class="bg-surface-card p-6 border border-white/5 rounded-lg border-t-2 border-t-primary">
<div class="flex justify-between items-start mb-4">
<span class="material-symbols-outlined text-primary p-3 bg-primary/10 rounded" data-icon="event_available">event_available</span>
<span class="text-xs text-on-surface-variant font-label-md">Booking Aktif</span>
</div>
<h2 class="font-headline-md text-headline-md text-white">1</h2>
<p class="text-sm text-on-surface-variant mt-2">Jadwal servis terdekat</p>
</div>
<div class="bg-surface-card p-6 border border-white/5 rounded-lg">
<div class="flex justify-between items-start mb-4">
<span class="material-symbols-outlined text-primary p-3 bg-primary/10 rounded" data-icon="payments">payments</span>
<span class="text-xs text-on-surface-variant font-label-md">Belanja Total</span>
</div>
<h2 class="font-headline-md text-headline-md text-white">Rp 1.250.000</h2>
<p class="text-sm text-on-surface-variant mt-2">Pengeluaran terakumulasi</p>
</div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
<!-- Recent Orders Table -->
<div class="lg:col-span-2 bg-surface-card border border-white/5 rounded-lg p-6">
<div class="flex justify-between items-center mb-6">
<h3 class="font-title-lg text-title-lg text-white">Pesanan Terakhir</h3>
<a class="text-primary text-sm hover:underline" href="#pesanan">Lihat Semua</a>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left">
<thead class="border-b border-white/5 text-on-surface-variant text-xs uppercase tracking-wider">
<tr>
<th class="pb-4 font-label-md">Order No</th>
<th class="pb-4 font-label-md">Tanggal</th>
<th class="pb-4 font-label-md">Total</th>
<th class="pb-4 font-label-md">Status</th>
<th class="pb-4 font-label-md">Aksi</th>
</tr>
</thead>
<tbody class="text-sm">
<tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
<td class="py-4 font-data-mono">#FM-10293</td>
<td class="py-4 text-on-surface-variant">24 Okt 2023</td>
<td class="py-4 font-bold">Rp 450.000</td>
<td class="py-4">
<span class="px-2 py-1 bg-secondary/20 text-secondary text-[10px] font-bold rounded">MENUNGGU BAYAR</span>
</td>
<td class="py-4">
<button class="text-primary hover:underline">Lihat Detail</button>
</td>
</tr>
<tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
<td class="py-4 font-data-mono">#FM-10288</td>
<td class="py-4 text-on-surface-variant">20 Okt 2023</td>
<td class="py-4 font-bold">Rp 125.000</td>
<td class="py-4">
<span class="px-2 py-1 bg-tertiary/20 text-tertiary text-[10px] font-bold rounded">DIKIRIM</span>
</td>
<td class="py-4">
<button class="text-primary hover:underline">Lihat Detail</button>
</td>
</tr>
<tr class="hover:bg-white/5 transition-colors">
<td class="py-4 font-data-mono">#FM-10250</td>
<td class="py-4 text-on-surface-variant">15 Okt 2023</td>
<td class="py-4 font-bold">Rp 675.000</td>
<td class="py-4">
<span class="px-2 py-1 bg-surface-variant text-on-surface-variant text-[10px] font-bold rounded">SELESAI</span>
</td>
<td class="py-4">
<button class="text-primary hover:underline">Lihat Detail</button>
</td>
</tr>
</tbody>
</table>
</div>
</div>
<!-- Recent Booking Card -->
<div class="bg-surface-accent/30 border border-primary/20 rounded-lg p-6 relative overflow-hidden">
<div class="absolute top-0 right-0 p-4 opacity-10">
<span class="material-symbols-outlined text-6xl" data-icon="motorcycle">motorcycle</span>
</div>
<h3 class="font-title-lg text-title-lg text-white mb-6">Booking Aktif</h3>
<div class="space-y-4 relative z-10">
<div>
<p class="text-xs text-on-surface-variant uppercase font-label-md">No. Booking</p>
<p class="font-data-mono text-primary text-lg">B-20231025-01</p>
</div>
<div class="grid grid-cols-2 gap-4">
<div>
<p class="text-xs text-on-surface-variant uppercase font-label-md">Tanggal</p>
<p class="text-sm font-bold">25 Okt, 09:00</p>
</div>
<div>
<p class="text-xs text-on-surface-variant uppercase font-label-md">Status</p>
<span class="inline-block px-2 py-1 bg-primary text-white text-[10px] font-bold rounded">DIKONFIRMASI</span>
</div>
</div>
<div>
<p class="text-xs text-on-surface-variant uppercase font-label-md">Kendaraan</p>
<p class="text-sm font-bold">Yamaha R15 - B 1234 ABC</p>
</div>
<button class="w-full bg-primary text-white py-3 font-headline-md text-headline-md tracking-wider mt-4 hover:bg-opacity-90 active:scale-[0.98] transition-all">RESCHEDULE</button>
</div>
</div>
</div>
</section>
<!-- FRAME 2: PESANAN SAYA (LIST VIEW) -->
<section class="mb-16" id="pesanan">
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
<h2 class="font-headline-md text-headline-md text-white">PESANAN SAYA</h2>
<!-- Tab Filters -->
<div class="flex gap-2 overflow-x-auto pb-2 w-full md:w-auto">
<button class="px-4 py-2 bg-primary text-white text-xs font-bold rounded whitespace-nowrap">Semua</button>
<button class="px-4 py-2 bg-surface-card border border-white/10 text-on-surface-variant text-xs font-bold rounded whitespace-nowrap hover:text-white transition-colors">Menunggu Bayar</button>
<button class="px-4 py-2 bg-surface-card border border-white/10 text-on-surface-variant text-xs font-bold rounded whitespace-nowrap hover:text-white transition-colors">Diproses</button>
<button class="px-4 py-2 bg-surface-card border border-white/10 text-on-surface-variant text-xs font-bold rounded whitespace-nowrap hover:text-white transition-colors">Dikirim</button>
<button class="px-4 py-2 bg-surface-card border border-white/10 text-on-surface-variant text-xs font-bold rounded whitespace-nowrap hover:text-white transition-colors">Selesai</button>
</div>
</div>
<div class="space-y-4">
<!-- Order Item 1 -->
<div class="bg-surface-card border border-white/5 rounded-lg p-6 group hover:border-primary/30 transition-all">
<div class="flex justify-between items-center mb-4 pb-4 border-b border-white/5">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-on-surface-variant" data-icon="shopping_bag">shopping_bag</span>
<div>
<p class="font-data-mono text-sm">#FM-10293</p>
<p class="text-xs text-on-surface-variant">24 Oktober 2023</p>
</div>
</div>
<span class="px-3 py-1 bg-secondary/10 text-secondary text-xs font-bold rounded border border-secondary/20">MENUNGGU PEMBAYARAN</span>
</div>
<div class="flex gap-6 items-center">
<img alt="Oil Filter" class="w-20 h-20 rounded object-cover bg-background" data-alt="A macro studio shot of a premium performance oil filter for sports motorcycles, featuring a high-quality metallic finish and crisp branding. The lighting is high-contrast and industrial, set against a dark textured background that highlights the precision engineering of the component. The color palette is dominated by deep blues and greys with sharp white highlights." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDkAFYY_MT6N6VDl7GcsapNrVjpTjAaMQcLReJmTQT9Xofou8xuLqPdw2hIwe5ZncbsC_OR8q9B__2xHlRljq_YvWMTtU2yZq8sITOJYJiQfqA_SPAS4_yEmOlCTYlwGA64QVpwYCxWXyntlN428PDJPIMhloG2dl4J_UlzXdO7PNpXO9woqTH7gK5ARXW98vXq023UGQ48MSJvp0XNstoDN1tM5QnfxI97pIFY7oAZe1VICSOzha2oEBkwktVr_EGy2-avYqVKgDE"/>
<div class="flex-1">
<h4 class="font-title-lg text-title-lg text-white">Castrol Power1 Ultimate 10W-40 4T</h4>
<p class="text-sm text-on-surface-variant">1 Unit x Rp 125.000</p>
<p class="text-xs text-on-surface-variant mt-1">+ 2 Produk Lainnya</p>
</div>
<div class="text-right">
<p class="text-xs text-on-surface-variant">Total Belanja</p>
<p class="font-headline-md text-headline-md text-white">Rp 450.000</p>
</div>
</div>
<div class="flex justify-end gap-3 mt-6">
<button class="px-6 py-2 border border-white/20 text-white font-label-md text-label-md rounded hover:bg-white/5 transition-all">Lihat Detail</button>
<button class="px-6 py-2 bg-primary text-white font-label-md text-label-md rounded hover:bg-opacity-90 active:scale-95 transition-all">BAYAR SEKARANG</button>
</div>
</div>
<!-- Order Item 2 -->
<div class="bg-surface-card border border-white/5 rounded-lg p-6 group">
<div class="flex justify-between items-center mb-4 pb-4 border-b border-white/5">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-on-surface-variant" data-icon="shopping_bag">shopping_bag</span>
<div>
<p class="font-data-mono text-sm">#FM-10250</p>
<p class="text-xs text-on-surface-variant">15 Oktober 2023</p>
</div>
</div>
<span class="px-3 py-1 bg-tertiary/10 text-tertiary text-xs font-bold rounded border border-tertiary/20">SELESAI</span>
</div>
<div class="flex gap-6 items-center">
<img alt="Brake Pad" class="w-20 h-20 rounded object-cover bg-background" data-alt="A professional product photograph of high-performance ceramic brake pads for motorcycles, displayed on a clean industrial metal surface. The image is captured with low-angle dramatic lighting, creating sharp reflections on the metallic parts. The atmosphere is technical and premium, using a dark color scheme with vibrant orange accents from the product packaging in the blurred background." src="https://lh3.googleusercontent.com/aida-public/AB6AXuA-bIj84agzwONNTwHOhpyDEy5FYUsoTWMO7GTpXCeU-FZLCFlbHVZwqqEE94glE1WTu8Oo-94EV7y34XRb3cBcZdl4TFPmWmoD8YT7CkPnx9b8IE6w7e_Ty0f5yk_p7VZ_hZRTJQwuj6-9LSCHlgia1gb3cchdyhBididjsYlUDwOsrQV_E-4lC8_KX3idpl5SJs-6pBJTnFGbjJgKOhNSxBmnePN_rmHXpVfVi8_Tuj4Fkxo9aH-0JmW3-T80bRvKNs08TOvZnMc"/>
<div class="flex-1">
<h4 class="font-title-lg text-title-lg text-white">Brembo Sintered Brake Pads (Front)</h4>
<p class="text-sm text-on-surface-variant">1 Unit x Rp 675.000</p>
</div>
<div class="text-right">
<p class="text-xs text-on-surface-variant">Total Belanja</p>
<p class="font-headline-md text-headline-md text-white">Rp 675.000</p>
</div>
</div>
<div class="flex justify-end gap-3 mt-6">
<button class="px-6 py-2 border border-white/20 text-white font-label-md text-label-md rounded hover:bg-white/5 transition-all">Lihat Detail</button>
<button class="px-6 py-2 bg-primary text-white font-label-md text-label-md rounded hover:bg-opacity-90 active:scale-95 transition-all">BELI LAGI</button>
</div>
</div>
</div>
<!-- Pagination -->
<div class="flex justify-center mt-12 gap-2">
<button class="w-10 h-10 flex items-center justify-center rounded border border-white/10 text-on-surface-variant hover:text-white transition-colors">
<span class="material-symbols-outlined" data-icon="chevron_left">chevron_left</span>
</button>
<button class="w-10 h-10 flex items-center justify-center rounded bg-primary text-white font-bold">1</button>
<button class="w-10 h-10 flex items-center justify-center rounded border border-white/10 text-on-surface-variant hover:text-white transition-colors">2</button>
<button class="w-10 h-10 flex items-center justify-center rounded border border-white/10 text-on-surface-variant hover:text-white transition-colors">3</button>
<button class="w-10 h-10 flex items-center justify-center rounded border border-white/10 text-on-surface-variant hover:text-white transition-colors">
<span class="material-symbols-outlined" data-icon="chevron_right">chevron_right</span>
</button>
</div>
</section>
<!-- FRAME 3: DETAIL PESANAN -->
<section class="mb-16" id="detail-pesanan">
<div class="flex items-center gap-4 mb-8">
<button class="p-2 hover:bg-white/5 rounded transition-colors">
<span class="material-symbols-outlined text-white" data-icon="arrow_back">arrow_back</span>
</button>
<h2 class="font-headline-md text-headline-md text-white uppercase">Detail Pesanan #FM-10250</h2>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
<div class="lg:col-span-2 space-y-gutter">
<!-- Progress Tracker -->
<div class="bg-surface-card border border-white/5 rounded-lg p-8">
<h3 class="font-title-lg text-title-lg text-white mb-8">Status Pengiriman</h3>
<div class="relative flex justify-between">
<!-- Track Line -->
<div class="absolute top-4 left-0 w-full h-[2px] bg-white/10 z-0"></div>
<div class="absolute top-4 left-0 w-full h-[2px] bg-primary z-0" style="width: 100%"></div>
<!-- Nodes -->
<div class="relative z-10 flex flex-col items-center gap-2">
<div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white shadow-[0_0_15px_rgba(255,86,37,0.5)]">
<span class="material-symbols-outlined text-sm" data-icon="description">description</span>
</div>
<span class="text-[10px] font-bold text-white uppercase text-center">Dibuat</span>
</div>
<div class="relative z-10 flex flex-col items-center gap-2">
<div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white shadow-[0_0_15px_rgba(255,86,37,0.5)]">
<span class="material-symbols-outlined text-sm" data-icon="payments">payments</span>
</div>
<span class="text-[10px] font-bold text-white uppercase text-center">Bayar</span>
</div>
<div class="relative z-10 flex flex-col items-center gap-2">
<div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white shadow-[0_0_15px_rgba(255,86,37,0.5)]">
<span class="material-symbols-outlined text-sm" data-icon="inventory_2">inventory_2</span>
</div>
<span class="text-[10px] font-bold text-white uppercase text-center">Dikemas</span>
</div>
<div class="relative z-10 flex flex-col items-center gap-2">
<div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white shadow-[0_0_15px_rgba(255,86,37,0.5)]">
<span class="material-symbols-outlined text-sm" data-icon="local_shipping">local_shipping</span>
</div>
<span class="text-[10px] font-bold text-white uppercase text-center">Dikirim</span>
</div>
<div class="relative z-10 flex flex-col items-center gap-2">
<div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white shadow-[0_0_15px_rgba(255,86,37,0.5)]">
<span class="material-symbols-outlined text-sm" data-icon="task_alt">task_alt</span>
</div>
<span class="text-[10px] font-bold text-white uppercase text-center">Selesai</span>
</div>
</div>
</div>
<!-- Item List -->
<div class="bg-surface-card border border-white/5 rounded-lg p-6">
<h3 class="font-title-lg text-title-lg text-white mb-6">Daftar Produk</h3>
<div class="space-y-6">
<div class="flex gap-4">
<img alt="Product" class="w-24 h-24 rounded object-cover bg-background border border-white/5" data-alt="A detailed product view of Brembo motorcycle brake pads, showcasing the ceramic texture and precision-cut metal backing. The lighting is focused and direct, creating deep shadows and bright metallic glints. The background is a dark, tech-inspired surface with subtle geometric patterns, reinforcing the premium industrial aesthetic of high-end automotive parts." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAmfrcoiYoyY8j46tjjDlvTnNkyDBLjlYiEyy8pmGeU4DjG80TEr8ilSlr3igSubDMyDYnTKaUufp16l15EeryGuHKBusf-XZebYY9dLLScINdumJ5KRAyp8MtXo0fBhjfp39QZ9NV6BxS2eg9Bi8Xh64eVe4pxxuQGjihnoT_Z4jCs278bwJ2ljj35cSCha9_3QFz12bGWMoQ9oftuAEZQTO8MCvQDSFyXmIGVZVCXuvH4MypdwiiZ4CYvbX-mTYic6J1rmuciqkw"/>
<div class="flex-1">
<h4 class="font-title-lg text-title-lg text-white">Brembo Sintered Brake Pads (Front)</h4>
<p class="text-sm text-on-surface-variant">Qty: 1 x Rp 675.000</p>
</div>
<div class="text-right">
<p class="font-bold text-white">Rp 675.000</p>
<button class="text-primary text-xs mt-2 border border-primary/30 px-3 py-1 rounded hover:bg-primary/10 transition-colors uppercase font-bold">Tulis Ulasan</button>
</div>
</div>
</div>
</div>
</div>
<div class="space-y-gutter">
<!-- Shipping Info -->
<div class="bg-surface-card border border-white/5 rounded-lg p-6">
<h3 class="font-title-lg text-title-lg text-white mb-4">Informasi Pengiriman</h3>
<div class="space-y-4 text-sm">
<div>
<p class="text-on-surface-variant uppercase text-[10px] font-bold tracking-widest">Alamat Penerima</p>
<p class="text-white font-bold mt-1">Budi Setiawan (Rumah)</p>
<p class="text-on-surface-variant leading-relaxed">Jl. Industri No. 42, RT 05 RW 03, Kel. Kebon Jeruk, Kec. Kebon Jeruk, Jakarta Barat, 11530</p>
<p class="text-on-surface-variant">0812-3456-7890</p>
</div>
<div class="pt-4 border-t border-white/5">
<p class="text-on-surface-variant uppercase text-[10px] font-bold tracking-widest">Kurir</p>
<p class="text-white font-bold mt-1">JNE Express - REGULAR</p>
<p class="text-on-surface-variant">Resi: <span class="font-data-mono">JX01928374655</span></p>
</div>
</div>
</div>
<!-- Summary -->
<div class="bg-surface-card border border-white/5 rounded-lg p-6">
<h3 class="font-title-lg text-title-lg text-white mb-4">Ringkasan Pembayaran</h3>
<div class="space-y-3 text-sm">
<div class="flex justify-between">
<span class="text-on-surface-variant">Total Harga (1 Barang)</span>
<span class="text-white">Rp 675.000</span>
</div>
<div class="flex justify-between">
<span class="text-on-surface-variant">Total Ongkos Kirim</span>
<span class="text-white">Rp 20.000</span>
</div>
<div class="flex justify-between">
<span class="text-on-surface-variant">Asuransi</span>
<span class="text-white">Rp 5.000</span>
</div>
<div class="flex justify-between pt-4 border-t border-white/5">
<span class="text-white font-bold">Total Belanja</span>
<span class="text-primary font-headline-md text-headline-md">Rp 700.000</span>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- FRAME 4: KELOLA ALAMAT -->
<section class="mb-16" id="alamat">
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
<h2 class="font-headline-md text-headline-md text-white uppercase">Kelola Alamat</h2>
<button class="bg-primary text-white px-6 py-2 font-headline-md text-headline-md tracking-wider flex items-center gap-2 hover:bg-opacity-90 transition-all">
<span class="material-symbols-outlined text-sm" data-icon="add">add</span> TAMBAH ALAMAT
                    </button>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
<!-- Address Card 1 (Default) -->
<div class="bg-surface-card border-2 border-primary rounded-lg p-6 relative">
<div class="flex justify-between items-start mb-4">
<div>
<h4 class="font-title-lg text-title-lg text-white">Rumah</h4>
<span class="inline-block px-2 py-0.5 bg-primary text-white text-[10px] font-bold rounded mt-2">UTAMA</span>
</div>
<div class="flex gap-2">
<button class="p-2 hover:bg-white/10 rounded transition-colors text-on-surface-variant"><span class="material-symbols-outlined text-sm" data-icon="edit">edit</span></button>
<button class="p-2 hover:bg-danger-red/10 rounded transition-colors text-danger-red"><span class="material-symbols-outlined text-sm" data-icon="delete">delete</span></button>
</div>
</div>
<div class="space-y-1 text-sm">
<p class="text-white font-bold">Budi Setiawan</p>
<p class="text-on-surface-variant">0812-3456-7890</p>
<p class="text-on-surface-variant leading-relaxed mt-2">Jl. Industri No. 42, RT 05 RW 03, Kel. Kebon Jeruk, Kec. Kebon Jeruk, Jakarta Barat, DKI Jakarta, 11530</p>
</div>
</div>
<!-- Address Card 2 -->
<div class="bg-surface-card border border-white/10 rounded-lg p-6">
<div class="flex justify-between items-start mb-4">
<div>
<h4 class="font-title-lg text-title-lg text-white">Kantor</h4>
</div>
<div class="flex gap-2">
<button class="p-2 hover:bg-white/10 rounded transition-colors text-on-surface-variant"><span class="material-symbols-outlined text-sm" data-icon="edit">edit</span></button>
<button class="p-2 hover:bg-danger-red/10 rounded transition-colors text-danger-red"><span class="material-symbols-outlined text-sm" data-icon="delete">delete</span></button>
</div>
</div>
<div class="space-y-1 text-sm">
<p class="text-white font-bold">Budi Setiawan</p>
<p class="text-on-surface-variant">0812-3456-7890</p>
<p class="text-on-surface-variant leading-relaxed mt-2">Menara Kadin Lt. 15, Jl. H.R. Rasuna Said Blok X-5 Kav. 2-3, Jakarta Selatan, 12950</p>
</div>
<button class="mt-4 text-primary text-xs font-bold hover:underline">JADIKAN UTAMA</button>
</div>
</div>
</section>
<!-- FRAME 5: EDIT PROFIL -->
<section class="mb-16" id="profil">
<h2 class="font-headline-md text-headline-md text-white uppercase mb-8">Edit Profil</h2>
<div class="bg-surface-card border border-white/5 rounded-lg p-8 max-w-2xl">
<form class="space-y-8">
<!-- Avatar Upload -->
<div class="flex flex-col items-center md:flex-row gap-8 pb-8 border-b border-white/5">
<div class="relative group">
<img alt="Profile" class="w-32 h-32 rounded-full object-cover border-4 border-surface-accent" data-alt="A clean, minimalist close-up shot of a professional studio headshot of a person, emphasizing a modern dark-mode aesthetic with soft rim lighting. The style is sleek and corporate, with deep shadows and focused brightness on the subject's features, suggesting high-quality digital photography for a premium tech platform profile." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDmMYfPbzqYSM0pebNU4t2DJPXpOBSW-ZS-KI1RyEOL4VgAfqiGn87rXwDEb0k7cwitNB2-kneSJekHVNVYOBujfxMqlrCa8TDzcCT1CjqBMUbdWRBpTVcrK1VhOFHI1HbaA65npjpylpwnbSsxagtzFdCiGKFRPlNpo6_jKyWDTJaSLTT-4TFeKLH6x-7iXTmRPnuFt6szQ8vNS11qVFE8JfCQMgXQ5G5hWc6X_k5OhrD-ZGxjCgC5yE6jiGVeRWWy0XtIZ6b5yr4"/>
<button class="absolute bottom-0 right-0 p-2 bg-primary text-white rounded-full shadow-lg hover:scale-110 transition-transform" type="button">
<span class="material-symbols-outlined text-sm" data-icon="photo_camera">photo_camera</span>
</button>
</div>
<div class="flex-1 text-center md:text-left">
<h4 class="font-title-lg text-title-lg text-white mb-2">Foto Profil</h4>
<p class="text-sm text-on-surface-variant mb-4">Format gambar .jpg, .png, .webp (Maksimal 2MB)</p>
<button class="border border-primary text-primary px-4 py-2 text-xs font-bold rounded hover:bg-primary/10 transition-colors" type="button">UNGGAH FOTO BARU</button>
</div>
</div>
<!-- Form Fields -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div class="space-y-2">
<label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Nama Lengkap</label>
<input class="w-full bg-background border border-white/10 rounded px-4 py-3 text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" type="text" value="Budi Setiawan"/>
</div>
<div class="space-y-2">
<label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Email (Read-only)</label>
<input class="w-full bg-surface-container-low border border-white/5 rounded px-4 py-3 text-on-surface-variant cursor-not-allowed outline-none" readonly="" type="email" value="budi.setiawan@email.com"/>
</div>
<div class="space-y-2">
<label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">No Handphone</label>
<input class="w-full bg-background border border-white/10 rounded px-4 py-3 text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" type="tel" value="081234567890"/>
</div>
<div class="space-y-2">
<label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Tanggal Lahir</label>
<input class="w-full bg-background border border-white/10 rounded px-4 py-3 text-white focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" type="date" value="1990-05-15"/>
</div>
</div>
<div class="flex justify-end gap-4 pt-4">
<button class="px-8 py-3 border border-white/10 text-white font-headline-md text-headline-md tracking-widest hover:bg-white/5 transition-all" type="button">BATAL</button>
<button class="px-8 py-3 bg-primary text-white font-headline-md text-headline-md tracking-widest hover:bg-opacity-90 active:scale-95 transition-all" type="submit">SIMPAN PERUBAHAN</button>
</div>
</form>
</div>
</section>
</main>
</div>
<!-- FOOTER (As per Shared Components) -->
<footer class="bg-surface-container-lowest dark:bg-surface-container-lowest border-t border-white/5 w-full">
<div class="grid grid-cols-1 md:grid-cols-4 gap-gutter max-w-[1440px] mx-auto px-8 py-16">
<div class="space-y-4">
<div class="font-headline-md text-headline-md text-primary">FITS MOTOR</div>
<p class="text-on-surface-variant text-body-md font-body-md">Premium Motorcycle Workshop &amp; Sparepart Specialist. Precision performance for your riding passion.</p>
</div>
<div class="space-y-4">
<h4 class="text-white font-title-lg">Layanan</h4>
<ul class="space-y-2">
<li><a class="text-on-surface-variant hover:text-secondary transition-colors text-label-md font-label-md" href="#">Layanan Servis</a></li>
<li><a class="text-on-surface-variant hover:text-secondary transition-colors text-label-md font-label-md" href="#">Suku Cadang</a></li>
<li><a class="text-on-surface-variant hover:text-secondary transition-colors text-label-md font-label-md" href="#">Garansi</a></li>
</ul>
</div>
<div class="space-y-4">
<h4 class="text-white font-title-lg">Perusahaan</h4>
<ul class="space-y-2">
<li><a class="text-on-surface-variant hover:text-secondary transition-colors text-label-md font-label-md" href="#">Karir</a></li>
<li><a class="text-on-surface-variant hover:text-secondary transition-colors text-label-md font-label-md" href="#">Kebijakan Privasi</a></li>
<li><a class="text-on-surface-variant hover:text-secondary transition-colors text-label-md font-label-md" href="#">Syarat &amp; Ketentuan</a></li>
</ul>
</div>
<div class="space-y-4">
<h4 class="text-white font-title-lg">Hubungi Kami</h4>
<p class="text-on-surface-variant text-sm">Jl. Raya Otomotif No. 123, Jakarta Selatan</p>
<p class="text-on-surface-variant text-sm">021-555-0192</p>
<div class="flex gap-4">
<span class="material-symbols-outlined text-primary cursor-pointer hover:text-secondary transition-colors" data-icon="public">public</span>
<span class="material-symbols-outlined text-primary cursor-pointer hover:text-secondary transition-colors" data-icon="mail">mail</span>
</div>
</div>
</div>
<div class="max-w-[1440px] mx-auto px-8 py-8 border-t border-white/5">
<p class="text-on-surface-variant text-body-md font-body-md">© 2024 FITS MOTOR. Premium Motorcycle Workshop.</p>
</div>
</footer>
</body></html>