<!DOCTYPE html>

<html class="dark" lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        body {
            background-color: #111125;
            color: #e2e0fc;
            -webkit-font-smoothing: antialiased;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-primary-fixed": "#3b0900",
                        "error-container": "#93000a",
                        "secondary-fixed-dim": "#f1c014",
                        "surface-container": "#1e1e32",
                        "on-secondary": "#3d2f00",
                        "inverse-primary": "#b12d00",
                        "primary-container": "#ff5625",
                        "on-error": "#690005",
                        "surface-card": "#16213E",
                        "secondary-fixed": "#ffe08e",
                        "on-primary": "#601400",
                        "danger-red": "#E53E3E",
                        "text-secondary": "#A0AEC0",
                        "primary-fixed-dim": "#ffb5a0",
                        "on-tertiary-fixed": "#002110",
                        "surface-accent": "#0F3460",
                        "on-error-container": "#ffdad6",
                        "text-primary": "#FFFFFF",
                        "surface-container-high": "#28283d",
                        "secondary": "#ffd660",
                        "surface-container-low": "#1a1a2e",
                        "on-primary-container": "#541100",
                        "on-tertiary-fixed-variant": "#00522f",
                        "primary-fixed": "#ffdbd1",
                        "on-background": "#e2e0fc",
                        "tertiary-container": "#3aa36b",
                        "on-surface-variant": "#e7bdb2",
                        "on-secondary-fixed-variant": "#584400",
                        "on-secondary-fixed": "#241a00",
                        "surface-container-lowest": "#0c0c1f",
                        "surface-dim": "#111125",
                        "secondary-container": "#e8b800",
                        "surface-bright": "#37374d",
                        "tertiary-fixed": "#91f8b8",
                        "inverse-on-surface": "#2f2e43",
                        "on-tertiary-container": "#00311a",
                        "inverse-surface": "#e2e0fc",
                        "on-tertiary": "#00391f",
                        "surface-tint": "#ffb5a0",
                        "surface-container-highest": "#333348",
                        "tertiary-fixed-dim": "#74db9d",
                        "on-surface": "#e2e0fc",
                        "on-secondary-container": "#5f4a00",
                        "primary": "#ffb5a0",
                        "on-primary-fixed-variant": "#872000",
                        "background": "#111125",
                        "tertiary": "#74db9d",
                        "error": "#ffb4ab",
                        "outline-variant": "#5d4038",
                        "outline": "#ad887e",
                        "surface": "#111125"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "stack-md": "12px",
                        "base": "8px",
                        "gutter": "24px",
                        "stack-sm": "4px",
                        "margin-mobile": "16px",
                        "stack-lg": "24px",
                        "container-max": "1440px"
                    },
                    "fontFamily": {
                        "title-lg": ["DM Sans"],
                        "headline-md": ["Bebas Neue"],
                        "display-hero": ["Bebas Neue"],
                        "label-md": ["DM Sans"],
                        "data-mono": ["JetBrains Mono"],
                        "headline-lg": ["Bebas Neue"],
                        "body-md": ["DM Sans"],
                        "body-lg": ["DM Sans"],
                        "display-hero-mobile": ["Bebas Neue"]
                    },
                    "fontSize": {
                        "title-lg": ["20px", {"lineHeight": "140%", "fontWeight": "700"}],
                        "headline-md": ["32px", {"lineHeight": "120%", "letterSpacing": "0.05em", "fontWeight": "400"}],
                        "display-hero": ["96px", {"lineHeight": "100%", "letterSpacing": "0.02em", "fontWeight": "700"}],
                        "label-md": ["14px", {"lineHeight": "100%", "letterSpacing": "0.01em", "fontWeight": "600"}],
                        "data-mono": ["14px", {"lineHeight": "140%", "fontWeight": "500"}],
                        "headline-lg": ["48px", {"lineHeight": "120%", "letterSpacing": "0.05em", "fontWeight": "400"}],
                        "body-md": ["16px", {"lineHeight": "160%", "fontWeight": "400"}],
                        "body-lg": ["18px", {"lineHeight": "160%", "fontWeight": "400"}],
                        "display-hero-mobile": ["48px", {"lineHeight": "110%", "letterSpacing": "0.02em", "fontWeight": "700"}]
                    }
                },
            },
        }
    </script>
</head>
<body class="font-body-md text-body-md overflow-x-hidden">
<!-- TopNavBar (Shared Component) -->
<header class="fixed top-0 w-full z-50 bg-background/80 backdrop-blur-xl border-b border-white/10 shadow-lg">
<nav class="flex justify-between items-center w-full px-8 h-20 max-w-[1440px] mx-auto">
<div class="font-headline-md text-headline-md text-primary tracking-wider">FITS MOTOR</div>
<div class="hidden md:flex gap-gutter items-center">
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Beranda</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Produk</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Booking</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Tentang</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Kontak</a>
</div>
<div class="flex items-center gap-stack-lg">
<span class="material-symbols-outlined text-on-surface-variant cursor-pointer hover:text-primary" data-icon="shopping_cart">shopping_cart</span>
<button class="hidden lg:block border-2 border-outline px-6 py-2 font-headline-md text-primary hover:bg-primary/10 transition-colors">Masuk</button>
<button class="bg-[#38A169] text-white px-6 py-2 font-headline-md shadow-[0_0_15px_rgba(56,161,105,0.3)] animate-pulse">Konsultasi WA</button>
</div>
</nav>
</header>
<main class="mt-24 pb-32">
<!-- FRAME 1: ORDER TRACKING PAGE (Desktop Focused) -->
<section class="max-w-[1440px] mx-auto px-8 mb-24">
<div class="mb-stack-lg">
<h1 class="font-headline-lg text-headline-lg text-white mb-stack-md">LACAK PESANAN</h1>
<div class="flex max-w-xl">
<input class="flex-grow bg-surface-container border-none focus:ring-2 focus:ring-primary-container text-white px-4 py-3 font-body-md rounded-l-lg" placeholder="No. Pesanan atau No. HP" type="text"/>
<button class="bg-primary-container text-on-primary-fixed px-8 py-3 font-headline-md rounded-r-lg hover:bg-primary transition-colors">Cari</button>
</div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">
<!-- Order Detail Content -->
<div class="lg:col-span-8 flex flex-col gap-gutter">
<!-- Order Header Card -->
<div class="bg-surface-card border border-white/10 p-stack-lg rounded-xl flex justify-between items-center">
<div>
<p class="text-on-surface-variant font-label-md uppercase mb-1">ID PESANAN</p>
<h3 class="font-data-mono text-title-lg text-primary">#FM-20250120-4521</h3>
<p class="text-on-surface-variant text-body-md">Pesanan dibuat pada 20 Jan 2025</p>
</div>
<div class="bg-primary-container/20 text-primary-container px-4 py-2 font-headline-md border border-primary-container/30">
                            DALAM PENGIRIMAN
                        </div>
</div>
<!-- Vertical Timeline -->
<div class="bg-surface-card border border-white/10 p-stack-lg rounded-xl">
<h4 class="font-headline-md text-white mb-stack-lg">STATUS PENGIRIMAN</h4>
<div class="flex flex-col gap-0 relative">
<!-- ✅ Node 1 -->
<div class="flex gap-stack-lg relative pb-10">
<div class="absolute left-[15px] top-[32px] bottom-0 w-[2px] bg-tertiary-container"></div>
<div class="relative z-10 w-8 h-8 rounded-full bg-tertiary-container flex items-center justify-center text-on-tertiary-container">
<span class="material-symbols-outlined text-[20px]" data-icon="check" data-weight="fill">check</span>
</div>
<div>
<h5 class="text-white font-title-lg">Pesanan Dikonfirmasi</h5>
<p class="text-on-surface-variant text-body-md">Pembayaran berhasil diverifikasi oleh sistem.</p>
<span class="text-on-surface-variant font-data-mono text-sm">20 Jan, 10:30 WIB</span>
</div>
</div>
<!-- ✅ Node 2 -->
<div class="flex gap-stack-lg relative pb-10">
<div class="absolute left-[15px] top-[32px] bottom-0 w-[2px] bg-tertiary-container"></div>
<div class="relative z-10 w-8 h-8 rounded-full bg-tertiary-container flex items-center justify-center text-on-tertiary-container">
<span class="material-symbols-outlined text-[20px]" data-icon="check" data-weight="fill">check</span>
</div>
<div>
<h5 class="text-white font-title-lg">Sedang Dikemas</h5>
<p class="text-on-surface-variant text-body-md">Pesanan sedang disiapkan di gudang FITS MOTOR.</p>
<span class="text-on-surface-variant font-data-mono text-sm">20 Jan, 14:00 WIB</span>
</div>
</div>
<!-- 🔄 Node 3 Active -->
<div class="flex gap-stack-lg relative pb-10">
<div class="absolute left-[15px] top-[32px] bottom-0 w-[2px] bg-white/10"></div>
<div class="relative z-10 w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container shadow-[0_0_15px_rgba(255,86,37,0.5)]">
<div class="w-2 h-2 bg-white rounded-full animate-ping"></div>
</div>
<div>
<h5 class="text-primary-container font-title-lg">Dalam Pengiriman</h5>
<p class="text-white text-body-md">Paket sedang dalam perjalanan menuju lokasi Anda.</p>
<span class="text-on-surface-variant font-data-mono text-sm">21 Jan, 09:15 WIB</span>
</div>
</div>
<!-- ⬜ Node 4 Upcoming -->
<div class="flex gap-stack-lg relative">
<div class="relative z-10 w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant">
<span class="material-symbols-outlined text-[20px]" data-icon="inventory_2">inventory_2</span>
</div>
<div>
<h5 class="text-on-surface-variant font-title-lg">Pesanan Tiba</h5>
<p class="text-on-surface-variant text-body-md">Paket akan segera sampai di tangan Anda.</p>
<span class="text-on-surface-variant font-data-mono text-sm">Estimasi: 23-24 Jan</span>
</div>
</div>
</div>
</div>
<!-- Collapsible Details -->
<div class="bg-surface-container border border-white/5 rounded-xl overflow-hidden">
<button class="w-full flex justify-between items-center p-stack-lg hover:bg-white/5 transition-colors group">
<span class="font-headline-md text-white flex items-center gap-2">
<span class="material-symbols-outlined" data-icon="list_alt">list_alt</span>
                                LIHAT DETAIL PRODUK
                            </span>
<span class="material-symbols-outlined text-primary group-hover:translate-y-1 transition-transform" data-icon="keyboard_arrow_down">keyboard_arrow_down</span>
</button>
<div class="p-stack-lg border-t border-white/5 bg-surface-card">
<div class="flex gap-stack-lg items-center py-4 border-b border-white/5 last:border-0">
<div class="w-16 h-16 bg-surface-container rounded-lg overflow-hidden flex-shrink-0">
<img alt="Product" class="w-full h-full object-cover" data-alt="A high-performance motorcycle engine oil bottle and a metallic carbon-fiber helmet resting on a professional mechanic's workbench. The scene is lit with dramatic orange side-lighting against a dark, industrial garage background with precision tools. The overall atmosphere is premium, focused, and highlights mechanical excellence." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAgqGQt0XLx8zqYeKROOuJyL14lToeJylSo9OnaggQRrDtOIvMFrj4a3as84u6HO_KhMyjj1nPYs8wjC-V2s7z2tur2DsXoTnb5jv2aOXejKfMn4LAT8TRilUrCJN0KJvQZI4eYVDMsEDjNCrR0aSrRbkEzkDEiVrLkUnunrttDO7hCTApewvrkFd63ZhieBbhh4TyPC_LqjuL23zLcwrNEHg7u5nr-Wenv96CG66sWZ8Ek7T-Jx3AvkntK5EQw3UCuxJglV6K27sM"/>
</div>
<div class="flex-grow">
<h6 class="text-white font-title-lg">Motul 7100 4T 10W-40 Synthetic</h6>
<p class="text-on-surface-variant text-sm">1 Liter x 2</p>
</div>
<div class="text-right">
<p class="text-primary font-data-mono">Rp 450.000</p>
</div>
</div>
</div>
</div>
</div>
<!-- Shipping Info Sidebar -->
<div class="lg:col-span-4 flex flex-col gap-gutter">
<div class="bg-surface-card border-t-4 border-primary-container p-stack-lg rounded-xl shadow-xl">
<h4 class="font-headline-md text-white mb-stack-lg">INFORMASI PENGIRIMAN</h4>
<div class="space-y-stack-lg">
<div class="flex items-center gap-4">
<div class="w-12 h-12 bg-white/5 flex items-center justify-center rounded-lg">
<img alt="JNE Logo" class="h-6" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA_Pd_btyfJdplzZSIkf6rskozxLQ3cyMzPTPEDNf_jDqZe_MULu0rl25gZHLF_QhtK8WtWsoM81i_NUYklKl4CSRs6BfQVSovIElWZbn9A2oHvrlOMczKjifymrkVIHyKhF2_0okXT0KIlKLJz-l1xTxFgqshGjDx7VnsX52Q-SpdYST25aF89bpd4BB0vgKq4S32NlUekg3tQUWuJYvJxKV7m_7WTIqr56dv544MdoFDBzdfliOalsUkZJ1UNtOknrJFqHs26uyU"/>
</div>
<div>
<p class="text-on-surface-variant text-sm">Kurir Pengiriman</p>
<p class="text-white font-title-lg">JNE REGULAR</p>
</div>
</div>
<div class="bg-surface-container p-4 rounded-lg">
<p class="text-on-surface-variant text-sm mb-1">Nomor Resi</p>
<div class="flex justify-between items-center">
<span class="font-data-mono text-primary text-lg tracking-wider">JX1234567890</span>
<button class="material-symbols-outlined text-primary-container hover:scale-110 transition-transform" data-icon="content_copy">content_copy</button>
</div>
</div>
<a class="block w-full border border-outline px-4 py-3 text-center font-headline-md text-white hover:bg-white/5 transition-colors" href="#">
                                CEK RESI DI WEBSITE JNE →
                            </a>
<div class="border-t border-white/5 pt-4">
<p class="text-on-surface-variant text-sm">Estimasi Paket Tiba</p>
<p class="text-white font-headline-md text-2xl">23 - 24 JANUARI 2025</p>
</div>
</div>
</div>
<button class="bg-[#38A169] text-white w-full py-4 rounded-lg flex items-center justify-center gap-2 font-headline-md text-xl shadow-lg hover:brightness-110 transition-all">
<span class="material-symbols-outlined" data-icon="chat" data-weight="fill">chat</span>
                        ADA MASALAH? CHAT ADMIN
                    </button>
</div>
</div>
</section>
<!-- FRAME 3: CONTACT PAGE -->
<section class="max-w-[1440px] mx-auto px-8 mb-24 grid grid-cols-1 lg:grid-cols-2 gap-24">
<div>
<h1 class="font-headline-lg text-headline-lg text-white mb-stack-lg">HUBUNGI KAMI</h1>
<form class="space-y-gutter bg-surface-card p-stack-lg rounded-xl border border-white/5">
<div class="grid grid-cols-1 md:grid-cols-2 gap-stack-lg">
<div class="space-y-2">
<label class="text-on-surface-variant font-label-md">NAMA LENGKAP</label>
<input class="w-full bg-background border border-outline/30 focus:border-primary px-4 py-3 text-white rounded-lg transition-colors" type="text"/>
</div>
<div class="space-y-2">
<label class="text-on-surface-variant font-label-md">ALAMAT EMAIL</label>
<input class="w-full bg-background border border-outline/30 focus:border-primary px-4 py-3 text-white rounded-lg transition-colors" type="email"/>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-stack-lg">
<div class="space-y-2">
<label class="text-on-surface-variant font-label-md">NOMOR HP (WHATSAPP)</label>
<input class="w-full bg-background border border-outline/30 focus:border-primary px-4 py-3 text-white rounded-lg transition-colors" type="tel"/>
</div>
<div class="space-y-2">
<label class="text-on-surface-variant font-label-md">TOPIK PERTANYAAN</label>
<select class="w-full bg-background border border-outline/30 focus:border-primary px-4 py-3 text-white rounded-lg transition-colors">
<option>Layanan Servis</option>
<option>Pembelian Part</option>
<option>Status Pesanan</option>
<option>Keluhan Pelanggan</option>
</select>
</div>
</div>
<div class="space-y-2">
<label class="text-on-surface-variant font-label-md">PESAN ANDA</label>
<textarea class="w-full bg-background border border-outline/30 focus:border-primary px-4 py-3 text-white rounded-lg transition-colors" rows="4"></textarea>
</div>
<button class="w-full bg-primary text-on-primary font-headline-md text-xl py-4 hover:brightness-110 transition-all rounded-lg">KIRIM PESAN SEKARANG</button>
</form>
</div>
<div class="flex flex-col gap-gutter">
<button class="bg-[#38A169] text-white w-full py-5 rounded-xl flex items-center justify-center gap-3 font-headline-md text-2xl shadow-xl hover:scale-[1.02] transition-all">
<span class="material-symbols-outlined text-3xl" data-icon="chat" data-weight="fill">chat</span>
                    WHATSAPP LANGSUNG
                </button>
<div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
<div class="bg-surface-accent p-stack-lg rounded-xl border-l-4 border-primary">
<span class="material-symbols-outlined text-primary text-3xl mb-4" data-icon="location_on">location_on</span>
<h5 class="text-white font-title-lg mb-2">Lokasi Workshop</h5>
<p class="text-on-surface-variant text-body-md">Jl. Industri Racing No. 42, Jakarta Selatan, 12345</p>
</div>
<div class="bg-surface-accent p-stack-lg rounded-xl border-l-4 border-primary">
<span class="material-symbols-outlined text-primary text-3xl mb-4" data-icon="schedule">schedule</span>
<h5 class="text-white font-title-lg mb-2">Jam Operasional</h5>
<p class="text-on-surface-variant text-body-md">Senin - Sabtu: 08:00 - 18:00<br/>Minggu: Tutup</p>
</div>
</div>
<div class="bg-surface-card border border-white/10 p-2 rounded-xl h-[300px] overflow-hidden">
<div class="w-full h-full bg-surface-container rounded-lg relative flex items-center justify-center group cursor-pointer">
<img alt="Map Location" class="w-full h-full object-cover opacity-60 group-hover:opacity-80 transition-opacity" data-alt="A stylized, top-down dark-mode digital map of a city intersection in Jakarta, South Jakarta district. The map features deep navy streets with glowing orange routes and a custom pin drop icon in vibrant red at the center. The visual style is high-tech, industrial, and clean, mimicking a premium GPS or automotive navigation interface." data-location="Jakarta" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCvkyPllKrZ_S4YMGXwLpJrdocQ7KBW_gNG6cCXMid0wghiB88dKHHYliPFTLnWoy6VVAR6orFZ8mKLcZzhzuwyYZV261x4LAUUZvJMjFci3nsmYkInZWrbSCaN8vH-6Hiwk82mVCu_SZa1-Ly8i0i_cwarDrIIDwzxhrlouXjLGUQQydwMcLBqUSTqegv6a1Z-S4pZ4DLgiyqS7nmtkhbnnRbu-ja-y-HiWZF5tZt2Mby__AIgm7RSQX4YYLMIh8-h9c8mMr0UjjY"/>
<div class="absolute inset-0 flex items-center justify-center pointer-events-none">
<div class="bg-primary-container text-on-primary-container px-6 py-3 font-headline-md rounded shadow-2xl scale-110">BUKA DI GOOGLE MAPS</div>
</div>
</div>
</div>
</div>
</section>
<!-- FRAME 4: 404 ERROR PAGE -->
<section class="min-h-[614px] flex flex-col items-center justify-center text-center px-8">
<h1 class="font-display-hero text-[180px] lg:text-[240px] text-primary leading-none opacity-20 absolute select-none">404</h1>
<div class="relative z-10 flex flex-col items-center">
<span class="font-headline-lg text-headline-lg text-primary mb-4">WADUH!</span>
<p class="font-title-lg text-white text-2xl mb-stack-lg">Halaman Tidak Ditemukan</p>
<p class="text-on-surface-variant max-w-md mb-stack-lg">Sepertinya Anda tersesat di sirkuit yang salah. Silakan kembali ke trek utama untuk melanjutkan perjalanan Anda.</p>
<a class="bg-primary-container text-on-primary-fixed px-10 py-4 font-headline-md text-xl rounded-lg shadow-xl flex items-center gap-2 hover:scale-105 transition-transform" href="#">
<span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
                    KEMBALI KE BERANDA
                </a>
</div>
</section>
</main>
<!-- Footer (Shared Component) -->
<footer class="bg-surface-container-lowest border-t border-white/5">
<div class="grid grid-cols-1 md:grid-cols-4 gap-gutter max-w-[1440px] mx-auto px-8 py-16">
<div class="flex flex-col gap-stack-md">
<div class="font-headline-md text-headline-md text-primary">FITS MOTOR</div>
<p class="text-on-surface-variant text-body-md">Bengkel spesialis performa tinggi dan suku cadang premium untuk enthusiast roda dua yang mengedepankan kualitas tanpa kompromi.</p>
</div>
<div class="flex flex-col gap-4">
<h6 class="text-white font-headline-md">LAYANAN</h6>
<ul class="flex flex-col gap-2">
<li><a class="text-on-surface-variant hover:text-secondary transition-colors" href="#">Layanan Servis</a></li>
<li><a class="text-on-surface-variant hover:text-secondary transition-colors" href="#">Suku Cadang</a></li>
<li><a class="text-on-surface-variant hover:text-secondary transition-colors" href="#">Garansi</a></li>
</ul>
</div>
<div class="flex flex-col gap-4">
<h6 class="text-white font-headline-md">PERUSAHAAN</h6>
<ul class="flex flex-col gap-2">
<li><a class="text-on-surface-variant hover:text-secondary transition-colors" href="#">Karir</a></li>
<li><a class="text-on-surface-variant hover:text-secondary transition-colors" href="#">Kebijakan Privasi</a></li>
<li><a class="text-on-surface-variant hover:text-secondary transition-colors" href="#">Syarat &amp; Ketentuan</a></li>
</ul>
</div>
<div class="flex flex-col gap-4">
<h6 class="text-white font-headline-md">IKUTI KAMI</h6>
<div class="flex gap-4">
<div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-primary transition-colors cursor-pointer"><span class="material-symbols-outlined" data-icon="public">public</span></div>
<div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-primary transition-colors cursor-pointer"><span class="material-symbols-outlined" data-icon="share">share</span></div>
</div>
</div>
</div>
<div class="max-w-[1440px] mx-auto px-8 py-8 border-t border-white/5 text-center text-on-surface-variant text-label-md">
            © 2024 FITS MOTOR. Premium Motorcycle Workshop.
        </div>
</footer>
<!-- FRAME 2: MOBILE BOTTOM NAVIGATION (Active: Pesanan) -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-background/95 backdrop-blur-xl border-t border-white/10 z-[60] flex justify-around items-center px-4 py-3">
<a class="flex flex-col items-center gap-1 group" href="#">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary" data-icon="home">home</span>
<span class="text-[10px] font-label-md text-on-surface-variant">Beranda</span>
</a>
<a class="flex flex-col items-center gap-1 group" href="#">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary" data-icon="shopping_cart">shopping_cart</span>
<span class="text-[10px] font-label-md text-on-surface-variant">Produk</span>
</a>
<a class="flex flex-col items-center gap-1 group" href="#">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary" data-icon="calendar_month">calendar_month</span>
<span class="text-[10px] font-label-md text-on-surface-variant">Booking</span>
</a>
<!-- Active Tab -->
<a class="flex flex-col items-center gap-1 relative" href="#">
<div class="absolute -top-1 w-1 h-1 bg-primary-container rounded-full"></div>
<span class="material-symbols-outlined text-primary-container" data-icon="package_2" data-weight="fill">package_2</span>
<span class="text-[10px] font-label-md text-primary-container">Pesanan</span>
</a>
<a class="flex flex-col items-center gap-1 group" href="#">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary" data-icon="person">person</span>
<span class="text-[10px] font-label-md text-on-surface-variant">Akun</span>
</a>
</nav>
</body></html>