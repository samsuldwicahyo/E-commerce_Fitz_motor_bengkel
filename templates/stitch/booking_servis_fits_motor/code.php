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
                    "surface": "#111125",
                    "on-tertiary-fixed": "#002110",
                    "surface-container-lowest": "#0c0c1f",
                    "primary-fixed-dim": "#ffb5a0",
                    "on-tertiary": "#00391f",
                    "surface-accent": "#0F3460",
                    "surface-card": "#16213E",
                    "surface-container-highest": "#333348",
                    "on-primary": "#601400",
                    "on-background": "#e2e0fc",
                    "primary-fixed": "#ffdbd1",
                    "on-error": "#690005",
                    "danger-red": "#E53E3E",
                    "inverse-surface": "#e2e0fc",
                    "tertiary-fixed": "#91f8b8",
                    "error-container": "#93000a",
                    "text-primary": "#FFFFFF",
                    "inverse-on-surface": "#2f2e43",
                    "surface-bright": "#37374d",
                    "primary-container": "#ff5625",
                    "inverse-primary": "#b12d00",
                    "text-secondary": "#A0AEC0",
                    "on-primary-container": "#541100",
                    "outline-variant": "#5d4038",
                    "on-secondary-fixed-variant": "#584400",
                    "on-secondary-fixed": "#241a00",
                    "secondary-fixed": "#ffe08e",
                    "on-surface": "#e2e0fc",
                    "outline": "#ad887e",
                    "tertiary-fixed-dim": "#74db9d",
                    "error": "#ffb4ab",
                    "surface-container-high": "#28283d",
                    "surface-tint": "#ffb5a0",
                    "background": "#111125",
                    "surface-container": "#1e1e32",
                    "surface-dim": "#111125",
                    "secondary": "#ffd660",
                    "on-primary-fixed": "#3b0900",
                    "on-primary-fixed-variant": "#872000",
                    "surface-container-low": "#1a1a2e",
                    "on-surface-variant": "#e7bdb2",
                    "admin-sidebar": "#0F1724",
                    "on-tertiary-container": "#00311a",
                    "on-tertiary-fixed-variant": "#00522f",
                    "on-secondary": "#3d2f00",
                    "tertiary-container": "#3aa36b",
                    "on-secondary-container": "#5f4a00",
                    "surface-variant": "#333348",
                    "tertiary": "#74db9d",
                    "secondary-container": "#e8b800",
                    "primary": "#ffb5a0",
                    "secondary-fixed-dim": "#f1c014",
                    "on-error-container": "#ffdad6"
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
                    "stack-lg": "24px",
                    "gutter": "24px",
                    "base": "8px"
            },
            "fontFamily": {
                    "label-md": ["DM Sans"],
                    "title-lg": ["DM Sans"],
                    "display-hero": ["Bebas Neue"],
                    "data-mono": ["JetBrains Mono"],
                    "headline-md": ["Bebas Neue"],
                    "display-hero-mobile": ["Bebas Neue"],
                    "body-md": ["DM Sans"],
                    "headline-lg": ["Bebas Neue"],
                    "body-lg": ["DM Sans"]
            },
            "fontSize": {
                    "label-md": ["14px", {"lineHeight": "100%", "letterSpacing": "0.01em", "fontWeight": "600"}],
                    "title-lg": ["20px", {"lineHeight": "140%", "fontWeight": "700"}],
                    "display-hero": ["96px", {"lineHeight": "100%", "letterSpacing": "0.02em", "fontWeight": "700"}],
                    "data-mono": ["14px", {"lineHeight": "140%", "fontWeight": "500"}],
                    "headline-md": ["32px", {"lineHeight": "120%", "letterSpacing": "0.05em", "fontWeight": "400"}],
                    "display-hero-mobile": ["48px", {"lineHeight": "110%", "letterSpacing": "0.02em", "fontWeight": "700"}],
                    "body-md": ["16px", {"lineHeight": "160%", "fontWeight": "400"}],
                    "headline-lg": ["48px", {"lineHeight": "120%", "letterSpacing": "0.05em", "fontWeight": "400"}],
                    "body-lg": ["18px", {"lineHeight": "160%", "fontWeight": "400"}]
            }
          },
        },
      }
    </script>
<style>.material-symbols-outlined {
    font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
    display: inline-block;
    line-height: 1;
    text-transform: none;
    letter-spacing: normal;
    word-wrap: normal;
    white-space: nowrap;
    direction: ltr
    }
.bg-noise {
    background-image: url(https://lh3.googleusercontent.com/aida-public/AB6AXuAX_8jwOeculsGVfx92suaCTvM7jw2Rn0ipY-XwBbrFV3inrtgRPfNqYl0L3fO3U_bkaIsNO5S89rCPPZuU1OkLkTzahQOi5sIJUoI2Vakper6UYXXpDavlcShc0EUP16Oid1IKzKPe43Jscx-gPFua0a7gt8xpk1uuT73jpAbB-SvYGu3pIfX_x0o-88dXsPScy7Ftw807bJ9QSCRDC2pYCW8uWlADCRKHXL3lMgfUZcIM6_WjH8OsiI51WM6YUPnj0MC-_v63bq8);
    opacity: 0.03
    }</style>
</head>
<body class="bg-background text-on-background font-body-md selection:bg-primary-container selection:text-white">
<!-- TopNavBar Shell -->
<nav class="fixed top-0 w-full z-50 bg-background/80 backdrop-blur-xl border-b border-white/10 shadow-lg">
<div class="flex justify-between items-center w-full px-8 h-20 max-w-[1440px] mx-auto">
<div class="font-headline-md text-headline-md text-primary tracking-wider">FITS MOTOR</div>
<div class="hidden md:flex items-center space-x-8">
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Beranda</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Produk</a>
<a class="text-primary border-b-2 border-primary pb-1 font-label-md text-label-md" href="#">Booking</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Tentang</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Kontak</a>
</div>
<div class="flex items-center space-x-6">
<span class="material-symbols-outlined text-on-surface-variant cursor-pointer hover:text-primary transition-colors">shopping_cart</span>
<button class="px-6 py-2 bg-surface-accent text-white font-label-md text-label-md border border-white/10 hover:bg-surface-container-highest transition-all active:scale-95">Masuk</button>
<button class="px-6 py-2 bg-[#38A169] text-white font-label-md text-label-md hover:opacity-90 transition-all flex items-center gap-2 active:scale-95 shadow-[0_0_15px_rgba(56,161,105,0.3)]">
<span class="material-symbols-outlined text-[18px]">chat</span>
                    Konsultasi WA
                </button>
</div>
</div>
</nav>
<main class="pt-20">
<!-- Hero Section -->
<section class="relative h-[400px] flex items-center justify-center overflow-hidden">
<div class="absolute inset-0 z-0">
<img class="w-full h-full object-cover opacity-40 grayscale group-hover:grayscale-0 transition-all duration-700" data-alt="A cinematic, low-angle shot of a high-performance custom motorcycle inside a dimly lit, professional racing garage. The setting features deep navy shadows, metallic textures, and warm amber spotlighting hitting the chrome components. The atmosphere is industrial and premium, with technical tools neatly organized in the background of this high-tech workshop space." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDi4YhkLcZShfuxgDaJ9U3ebUdROMnFVkQY7ZJQVF5PdoF5sx9AXB2YE2wYBhcABln6xouAioIWx_Z8GzxCxwXAhSEnu2IkY9G0eTEM1Fp2r3AM6lXbf8lWOtSQSWGvl3V0EblwFkjGK-TTR0Htyz8iUHmjWXLJflfgHaba49NVgqp820EJtGp6TrjRIo687EQ_BP6A6vZv8cwr5oLwqRTnbvGQeq_AhNlLM0rYLD1-cZvBZWdOwaxb1x48Js3SYgy8fsnxkJfRsSk"/>
<div class="absolute inset-0 bg-gradient-to-t from-background via-background/60 to-transparent"></div>
<div class="absolute inset-0 bg-noise"></div>
</div>
<div class="relative z-10 text-center space-y-4">
<h1 class="font-display-hero text-display-hero text-white tracking-wider">BOOKING SERVIS</h1>
<p class="text-primary font-label-md text-label-md tracking-[0.3em] uppercase">Precision Engineering for Your Machine</p>
</div>
</section>
<!-- Progress Indicator -->
<section class="max-w-[1440px] mx-auto px-8 -mt-16 relative z-20">
<div class="bg-surface-card border border-white/5 p-8 shadow-2xl">
<div class="flex items-center justify-between max-w-4xl mx-auto">
<!-- Step 1 -->
<div class="flex flex-col items-center gap-3 group">
<div class="w-12 h-12 rounded-full border-2 border-primary bg-primary text-on-primary flex items-center justify-center font-bold text-lg shadow-[0_0_15px_rgba(255,181,160,0.4)]">1</div>
<span class="text-primary font-label-md text-label-md">Data Kamu</span>
</div>
<div class="flex-1 h-[2px] bg-primary/30 mx-4 mb-8"></div>
<!-- Step 2 -->
<div class="flex flex-col items-center gap-3">
<div class="w-12 h-12 rounded-full border-2 border-white/10 bg-surface-container flex items-center justify-center font-bold text-lg text-on-surface-variant">2</div>
<span class="text-on-surface-variant font-label-md text-label-md">Data Motor</span>
</div>
<div class="flex-1 h-[2px] bg-white/10 mx-4 mb-8"></div>
<!-- Step 3 -->
<div class="flex flex-col items-center gap-3">
<div class="w-12 h-12 rounded-full border-2 border-white/10 bg-surface-container flex items-center justify-center font-bold text-lg text-on-surface-variant">3</div>
<span class="text-on-surface-variant font-label-md text-label-md">Pilih Layanan</span>
</div>
<div class="flex-1 h-[2px] bg-white/10 mx-4 mb-8"></div>
<!-- Step 4 -->
<div class="flex flex-col items-center gap-3">
<div class="w-12 h-12 rounded-full border-2 border-white/10 bg-surface-container flex items-center justify-center font-bold text-lg text-on-surface-variant">4</div>
<span class="text-on-surface-variant font-label-md text-label-md">Pilih Jadwal</span>
</div>
</div>
</div>
</section>
<!-- Booking Content Grid -->
<section class="max-w-[1440px] mx-auto px-8 py-16 grid grid-cols-1 lg:grid-cols-12 gap-gutter">
<!-- Left Side: Forms (Main Canvas) -->
<div class="lg:col-span-8 space-y-8">
<!-- Section Header -->
<div class="border-l-4 border-primary pl-6">
<h2 class="font-headline-md text-headline-md text-white uppercase italic">Informasi Kendaraan &amp; Layanan</h2>
<p class="text-on-surface-variant font-body-md text-body-md">Silakan lengkapi detail servis Anda di bawah ini.</p>
</div>
<!-- Form Step 1: Pre-filled User Data -->
<div class="bg-surface-card p-8 border border-white/5 relative group">
<div class="flex justify-between items-center mb-6">
<h3 class="font-title-lg text-title-lg text-white flex items-center gap-2">
<span class="material-symbols-outlined text-primary">person</span>
                            Data Pemilik
                        </h3>
<button class="text-primary font-label-md text-label-md hover:underline italic">Ubah Profil</button>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div class="space-y-2">
<label class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">Nama Lengkap</label>
<div class="bg-surface-container p-4 border border-white/10 text-white font-medium">Budi Pratama</div>
</div>
<div class="space-y-2">
<label class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">Nomor Telepon</label>
<div class="bg-surface-container p-4 border border-white/10 text-white font-medium">+62 812 3456 7890</div>
</div>
</div>
</div>
<!-- Form Step 2: Motor Data -->
<div class="bg-surface-card p-8 border border-white/5">
<h3 class="font-title-lg text-title-lg text-white mb-8 flex items-center gap-2">
<span class="material-symbols-outlined text-primary">motorcycle</span>
                        Spesifikasi Motor
                    </h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
<div class="space-y-2">
<label class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">Merk &amp; Tipe</label>
<select class="w-full bg-surface-container border border-white/10 p-4 text-white focus:border-primary-container outline-none transition-colors appearance-none">
<option>Pilih Motor Anda</option>
<option>Honda CBR250RR</option>
<option>Kawasaki ZX-25R</option>
<option>Yamaha R25</option>
</select>
</div>
<div class="space-y-2">
<label class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">Tahun Produksi</label>
<input class="w-full bg-surface-container border border-white/10 p-4 text-white focus:border-primary-container outline-none transition-colors" placeholder="Contoh: 2022" type="text"/>
</div>
<div class="space-y-2">
<label class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">Nomor Plat (Nopol)</label>
<input class="w-full bg-surface-container border border-white/10 p-4 text-white font-data-mono focus:border-primary-container outline-none transition-colors" placeholder="B 1234 ABC" type="text"/>
</div>
<div class="space-y-2">
<label class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">Keluhan / Catatan Tambahan</label>
<textarea class="w-full bg-surface-container border border-white/10 p-4 text-white focus:border-primary-container outline-none transition-colors resize-none" placeholder="Ceritakan masalah motor Anda..." rows="1"></textarea>
</div>
</div>
</div>
<!-- Step 3: Service Selection Grid -->
<div class="space-y-6">
<h3 class="font-title-lg text-title-lg text-white flex items-center gap-2">
<span class="material-symbols-outlined text-primary">build</span>
                        Pilih Jenis Layanan
                    </h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<!-- Service Card 1 -->
<div class="bg-surface-card border-2 border-primary-container p-6 relative group cursor-pointer overflow-hidden">
<div class="absolute top-0 right-0 bg-primary-container text-on-primary-container px-3 py-1 font-label-md text-[10px] uppercase tracking-tighter">Terpilih</div>
<div class="flex items-start gap-4 mb-4">
<div class="w-12 h-12 bg-surface-accent flex items-center justify-center">
<span class="material-symbols-outlined text-primary text-3xl">settings_suggest</span>
</div>
<div>
<h4 class="text-white font-title-lg text-title-lg">Servis Ringan</h4>
<p class="text-on-surface-variant text-sm">Pengecekan rutin &amp; tune up mesin.</p>
</div>
</div>
<div class="flex items-center justify-between mt-6">
<span class="font-data-mono text-secondary text-lg">Rp 150.000</span>
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">check_circle</span>
</div>
</div>
<!-- Service Card 2 -->
<div class="bg-surface-card border border-white/5 p-6 hover:border-primary transition-all cursor-pointer">
<div class="flex items-start gap-4 mb-4">
<div class="w-12 h-12 bg-surface-container flex items-center justify-center">
<span class="material-symbols-outlined text-on-surface-variant text-3xl">oil_barrel</span>
</div>
<div>
<h4 class="text-white font-title-lg text-title-lg">Ganti Oli</h4>
<p class="text-on-surface-variant text-sm">Oli mesin premium &amp; filter oli.</p>
</div>
</div>
<div class="flex items-center justify-between mt-6">
<span class="font-data-mono text-on-surface-variant text-lg">Mulai Rp 85.000</span>
<span class="material-symbols-outlined text-white/10">circle</span>
</div>
</div>
<!-- Service Card 3 -->
<div class="bg-surface-card border border-white/5 p-6 hover:border-primary transition-all cursor-pointer">
<div class="flex items-start gap-4 mb-4">
<div class="w-12 h-12 bg-surface-container flex items-center justify-center">
<span class="material-symbols-outlined text-on-surface-variant text-3xl">tire_repair</span>
</div>
<div>
<h4 class="text-white font-title-lg text-title-lg">Ganti Ban</h4>
<p class="text-on-surface-variant text-sm">Pemasangan ban presisi.</p>
</div>
</div>
<div class="flex items-center justify-between mt-6">
<span class="font-data-mono text-on-surface-variant text-lg">Rp 50.000</span>
<span class="material-symbols-outlined text-white/10">circle</span>
</div>
</div>
<!-- Service Card 4 -->
<div class="bg-surface-card border border-white/5 p-6 hover:border-primary transition-all cursor-pointer">
<div class="flex items-start gap-4 mb-4">
<div class="w-12 h-12 bg-surface-container flex items-center justify-center">
<span class="material-symbols-outlined text-on-surface-variant text-3xl">bolt</span>
</div>
<div>
<h4 class="text-white font-title-lg text-title-lg">Servis Kelistrikan</h4>
<p class="text-on-surface-variant text-sm">Cek aki, kabel, &amp; sistem lampu.</p>
</div>
</div>
<div class="flex items-center justify-between mt-6">
<span class="font-data-mono text-on-surface-variant text-lg">Estimasi</span>
<span class="material-symbols-outlined text-white/10">circle</span>
</div>
</div>
</div>
</div>
<!-- Step 4: Schedule Selection -->
<div class="bg-surface-card border border-white/5 overflow-hidden">
<div class="p-8 border-b border-white/5">
<h3 class="font-title-lg text-title-lg text-white flex items-center gap-2">
<span class="material-symbols-outlined text-primary">calendar_month</span>
                            Tentukan Jadwal
                        </h3>
</div>
<div class="grid grid-cols-1 md:grid-cols-2">
<!-- Calendar Placeholder -->
<div class="p-8 border-r border-white/5">
<div class="flex justify-between items-center mb-6">
<span class="font-bold text-white uppercase tracking-widest">Oktober 2024</span>
<div class="flex gap-2">
<button class="w-8 h-8 flex items-center justify-center border border-white/10 hover:bg-surface-accent"><span class="material-symbols-outlined text-sm">chevron_left</span></button>
<button class="w-8 h-8 flex items-center justify-center border border-white/10 hover:bg-surface-accent"><span class="material-symbols-outlined text-sm">chevron_right</span></button>
</div>
</div>
<div class="grid grid-cols-7 gap-2 text-center text-[10px] text-on-surface-variant font-bold mb-4">
<span>MIN</span><span>SEN</span><span>SEL</span><span>RAB</span><span>KAM</span><span>JUM</span><span>SAB</span>
</div>
<div class="grid grid-cols-7 gap-2">
<div class="aspect-square flex items-center justify-center text-white/20 text-sm">29</div>
<div class="aspect-square flex items-center justify-center text-white/20 text-sm">30</div>
<div class="aspect-square flex items-center justify-center text-white text-sm hover:bg-surface-accent cursor-pointer">1</div>
<div class="aspect-square flex items-center justify-center text-white text-sm hover:bg-surface-accent cursor-pointer">2</div>
<div class="aspect-square flex items-center justify-center text-white text-sm hover:bg-surface-accent cursor-pointer">3</div>
<div class="aspect-square flex items-center justify-center text-white text-sm hover:bg-surface-accent cursor-pointer">4</div>
<div class="aspect-square flex items-center justify-center text-white text-sm hover:bg-surface-accent cursor-pointer">5</div>
<div class="aspect-square flex items-center justify-center text-white text-sm bg-primary text-on-primary font-bold shadow-lg">6</div>
<div class="aspect-square flex items-center justify-center text-white text-sm hover:bg-surface-accent cursor-pointer">7</div>
<div class="aspect-square flex items-center justify-center text-white text-sm hover:bg-surface-accent cursor-pointer">8</div>
<div class="aspect-square flex items-center justify-center text-white text-sm hover:bg-surface-accent cursor-pointer">9</div>
<div class="aspect-square flex items-center justify-center text-white text-sm hover:bg-surface-accent cursor-pointer">10</div>
<div class="aspect-square flex items-center justify-center text-white text-sm hover:bg-surface-accent cursor-pointer">11</div>
<div class="aspect-square flex items-center justify-center text-white text-sm hover:bg-surface-accent cursor-pointer">12</div>
</div>
</div>
<!-- Time Slots -->
<div class="p-8 bg-surface-container-low">
<h4 class="text-white font-label-md text-label-md uppercase mb-6 tracking-wider">Jam Kedatangan</h4>
<div class="grid grid-cols-2 gap-3">
<button class="py-3 border border-primary bg-surface-accent text-white font-data-mono text-sm">09:00</button>
<button class="py-3 border border-white/5 bg-surface-container text-on-surface-variant font-data-mono text-sm hover:border-primary">10:00</button>
<button class="py-3 border border-white/5 bg-surface-container text-on-surface-variant font-data-mono text-sm hover:border-primary">11:00</button>
<button class="py-3 border border-white/5 bg-surface-container text-on-surface-variant font-data-mono text-sm hover:border-primary">13:00</button>
<button class="py-3 border border-white/5 bg-surface-container text-on-surface-variant font-data-mono text-sm opacity-50 cursor-not-allowed">14:00 (Full)</button>
<button class="py-3 border border-white/5 bg-surface-container text-on-surface-variant font-data-mono text-sm hover:border-primary">15:00</button>
</div>
</div>
</div>
</div>
</div>
<!-- Right Side: Confirmation Summary (Sticky) -->
<div class="lg:col-span-4">
<div class="sticky top-28 space-y-6">
<div class="bg-surface-card border border-white/10 p-8 shadow-xl">
<h3 class="font-headline-md text-headline-md text-white mb-6 border-b border-white/5 pb-4 italic">RINGKASAN BOOKING</h3>
<div class="space-y-6">
<div>
<label class="text-[10px] text-on-surface-variant uppercase tracking-[0.2em] block mb-2">Motor</label>
<p class="text-white font-bold">Honda CBR250RR (2022)</p>
<p class="text-on-surface-variant text-sm font-data-mono">B 1234 XYZ</p>
</div>
<div>
<label class="text-[10px] text-on-surface-variant uppercase tracking-[0.2em] block mb-2">Layanan Terpilih</label>
<div class="flex justify-between text-sm">
<span class="text-white">Servis Ringan</span>
<span class="text-on-surface-variant font-data-mono">Rp 150.000</span>
</div>
<div class="flex justify-between text-sm mt-1">
<span class="text-white">Biaya Pendaftaran</span>
<span class="text-on-surface-variant font-data-mono">Rp 10.000</span>
</div>
</div>
<div class="pt-4 border-t border-dashed border-white/10">
<label class="text-[10px] text-on-surface-variant uppercase tracking-[0.2em] block mb-2">Jadwal</label>
<p class="text-white font-bold flex items-center gap-2">
<span class="material-symbols-outlined text-primary text-lg">event_available</span>
                                    Minggu, 6 Okt 2024 - 09:00 WIB
                                </p>
</div>
<div class="bg-surface-accent p-4 border-l-4 border-secondary">
<div class="flex justify-between items-center">
<span class="text-on-surface-variant text-sm">Total Estimasi</span>
<span class="text-secondary font-headline-md text-2xl">Rp 160.000</span>
</div>
</div>
<button class="w-full bg-primary-container text-white font-headline-md text-headline-md py-4 hover:shadow-[0_0_20px_rgba(255,86,37,0.4)] transition-all active:scale-[0.98] uppercase">KONFIRMASI BOOKING</button>
<p class="text-center text-[11px] text-on-surface-variant italic">Layanan dibayar di bengkel setelah servis selesai.</p>
</div>
</div>
<!-- Workshop Info Card -->
<div class="bg-surface-container p-6 border border-white/5 flex items-center gap-4">
<div class="w-12 h-12 rounded-full bg-surface-accent flex items-center justify-center">
<span class="material-symbols-outlined text-primary">location_on</span>
</div>
<div>
<h5 class="text-white font-bold text-sm">FITS MOTOR Workshop</h5>
<p class="text-on-surface-variant text-[12px]">Jl. Industri Raya No. 42, Jakarta Pusat</p>
</div>
</div>
</div>
</div>
</section>
<!-- Stats Bar (Decorative Brand Component) -->
<section class="bg-surface-accent/30 py-12 border-y border-white/5">
<div class="max-w-[1440px] mx-auto px-8 grid grid-cols-2 md:grid-cols-4 gap-8">
<div class="text-center">
<div class="text-secondary font-display-hero text-4xl mb-1">15K+</div>
<div class="text-on-surface-variant font-label-md text-xs uppercase tracking-widest">Servis Selesai</div>
</div>
<div class="text-center">
<div class="text-secondary font-display-hero text-4xl mb-1">98%</div>
<div class="text-on-surface-variant font-label-md text-xs uppercase tracking-widest">Pelanggan Puas</div>
</div>
<div class="text-center">
<div class="text-secondary font-display-hero text-4xl mb-1">20+</div>
<div class="text-on-surface-variant font-label-md text-xs uppercase tracking-widest">Mekanik Pro</div>
</div>
<div class="text-center">
<div class="text-secondary font-display-hero text-4xl mb-1">2</div>
<div class="text-on-surface-variant font-label-md text-xs uppercase tracking-widest">Tahun Garansi</div>
</div>
</div>
</section>
</main>
<!-- Footer Shell -->
<footer class="bg-surface-container-lowest border-t border-white/5">
<div class="grid grid-cols-1 md:grid-cols-4 gap-gutter max-w-[1440px] mx-auto px-8 py-16">
<div class="space-y-6">
<div class="font-headline-md text-headline-md text-primary">FITS MOTOR</div>
<p class="text-on-surface-variant font-body-md text-body-md">Pusat perawatan motor premium dengan teknologi presisi dan mekanik bersertifikat. Menghadirkan performa terbaik untuk setiap putaran mesin Anda.</p>
<div class="flex gap-4">
<span class="material-symbols-outlined text-on-surface-variant hover:text-primary cursor-pointer">public</span>
<span class="material-symbols-outlined text-on-surface-variant hover:text-primary cursor-pointer">chat</span>
<span class="material-symbols-outlined text-on-surface-variant hover:text-primary cursor-pointer">mail</span>
</div>
</div>
<div class="space-y-4">
<h4 class="text-white font-title-lg text-title-lg mb-6">Layanan</h4>
<nav class="flex flex-col space-y-3">
<a class="text-on-surface-variant font-label-md text-label-md hover:text-secondary transition-colors" href="#">Layanan Servis</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-secondary transition-colors" href="#">Suku Cadang</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-secondary transition-colors" href="#">Modifikasi</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-secondary transition-colors" href="#">Diagnosa Engine</a>
</nav>
</div>
<div class="space-y-4">
<h4 class="text-white font-title-lg text-title-lg mb-6">Informasi</h4>
<nav class="flex flex-col space-y-3">
<a class="text-on-surface-variant font-label-md text-label-md hover:text-secondary transition-colors" href="#">Garansi</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-secondary transition-colors" href="#">Karir</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-secondary transition-colors" href="#">Kebijakan Privasi</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-secondary transition-colors" href="#">Syarat &amp; Ketentuan</a>
</nav>
</div>
<div class="space-y-4">
<h4 class="text-white font-title-lg text-title-lg mb-6">Workshop</h4>
<p class="text-on-surface-variant font-body-md text-body-md">Buka Setiap Hari<br/>08:00 - 17:00 WIB</p>
<div class="bg-surface-accent p-4 border border-white/5 mt-4">
<p class="text-white font-bold text-sm mb-2">Panggil Mekanik?</p>
<button class="bg-primary text-on-primary font-label-md text-label-md px-4 py-2 w-full">Home Service</button>
</div>
</div>
</div>
<div class="max-w-[1440px] mx-auto px-8 py-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
<p class="text-on-surface-variant font-label-md text-label-md">© 2024 FITS MOTOR. Premium Motorcycle Workshop.</p>
<div class="flex gap-6">
<img class="h-6 grayscale opacity-50" data-alt="Logos of major motorcycle manufacturers like Honda, Yamaha, and Kawasaki arranged in a minimalist horizontal row. The images are high-contrast grayscale, fitting a professional automotive brand environment, showing industrial precision and authority in the motor sector." src="https://lh3.googleusercontent.com/aida-public/AB6AXuA-shCHzNjkw0dlHpD_v7gOGkDcwfqGnQd8ktwdCZ4GgsTl4uJF0amNbjPdMhwtrPPLE0d9Xf3kseCNkESaJYqBUDiuxJWom6PYXpOKO_sofKbQQGDr2EgdOnoIPbg_MJQN2g3HhrCZkiwu3FnuGChq3v1yCpgnSAKpdZQ1lLbyxDpcJ_X9Hvn_PCfbGonIUdOG57mMo1RUMiLDuXypizz3S7m1eoABI8fnEZ5wkLG8TsMtTcQhgh7il0McsmC7e-jURG0Nr-i_sZs"/>
</div>
</div>
</footer>
</body></html>