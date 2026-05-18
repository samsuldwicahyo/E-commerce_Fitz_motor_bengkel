<!DOCTYPE html>

<html class="dark" lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;family=DM+Sans:wght@400;500;700&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            font-family: 'DM Sans', sans-serif;
            background-color: #111125;
        }
        .bebas {
            font-family: 'Bebas Neue', cursive;
        }
    </style>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "inverse-surface": "#e2e0fc",
                    "tertiary-container": "#3aa36b",
                    "background": "#111125",
                    "on-secondary-fixed": "#241a00",
                    "admin-sidebar": "#0F1724",
                    "outline-variant": "#5d4038",
                    "on-primary": "#601400",
                    "error": "#ffb4ab",
                    "on-primary-fixed-variant": "#872000",
                    "surface-container-lowest": "#0c0c1f",
                    "tertiary-fixed-dim": "#74db9d",
                    "tertiary-fixed": "#91f8b8",
                    "on-secondary": "#3d2f00",
                    "surface-container-low": "#1a1a2e",
                    "text-primary": "#FFFFFF",
                    "on-error-container": "#ffdad6",
                    "inverse-on-surface": "#2f2e43",
                    "surface": "#111125",
                    "surface-bright": "#37374d",
                    "surface-container-high": "#28283d",
                    "on-tertiary-container": "#00311a",
                    "on-secondary-fixed-variant": "#584400",
                    "primary-fixed": "#ffdbd1",
                    "tertiary": "#74db9d",
                    "danger-red": "#E53E3E",
                    "on-tertiary-fixed": "#002110",
                    "on-surface-variant": "#e7bdb2",
                    "secondary": "#ffd660",
                    "surface-tint": "#ffb5a0",
                    "surface-container": "#1e1e32",
                    "outline": "#ad887e",
                    "surface-card": "#16213E",
                    "on-surface": "#e2e0fc",
                    "secondary-fixed": "#ffe08e",
                    "primary-fixed-dim": "#ffb5a0",
                    "on-secondary-container": "#5f4a00",
                    "inverse-primary": "#b12d00",
                    "on-primary-container": "#541100",
                    "on-tertiary": "#00391f",
                    "surface-variant": "#333348",
                    "surface-accent": "#0F3460",
                    "error-container": "#93000a",
                    "surface-container-highest": "#333348",
                    "on-tertiary-fixed-variant": "#00522f",
                    "text-secondary": "#A0AEC0",
                    "surface-dim": "#111125",
                    "on-background": "#e2e0fc",
                    "primary": "#ffb5a0",
                    "secondary-fixed-dim": "#f1c014",
                    "on-error": "#690005",
                    "secondary-container": "#e8b800",
                    "primary-container": "#ff5625",
                    "on-primary-fixed": "#3b0900"
            },
            "borderRadius": {
                    "DEFAULT": "0.125rem",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem"
            },
            "spacing": {
                    "gutter": "24px",
                    "margin-mobile": "16px",
                    "container-max": "1440px",
                    "base": "8px",
                    "stack-sm": "4px",
                    "stack-md": "12px",
                    "stack-lg": "24px"
            },
            "fontFamily": {
                    "headline-md": ["Bebas Neue"],
                    "display-hero-mobile": ["Bebas Neue"],
                    "body-md": ["DM Sans"],
                    "display-hero": ["Bebas Neue"],
                    "data-mono": ["JetBrains Mono"],
                    "body-lg": ["DM Sans"],
                    "title-lg": ["DM Sans"],
                    "label-md": ["DM Sans"],
                    "headline-lg": ["Bebas Neue"]
            },
            "fontSize": {
                    "headline-md": ["32px", {"lineHeight": "120%", "letterSpacing": "0.05em", "fontWeight": "400"}],
                    "display-hero-mobile": ["48px", {"lineHeight": "110%", "letterSpacing": "0.02em", "fontWeight": "700"}],
                    "body-md": ["16px", {"lineHeight": "160%", "fontWeight": "400"}],
                    "display-hero": ["96px", {"lineHeight": "100%", "letterSpacing": "0.02em", "fontWeight": "700"}],
                    "data-mono": ["14px", {"lineHeight": "140%", "fontWeight": "500"}],
                    "body-lg": ["18px", {"lineHeight": "160%", "fontWeight": "400"}],
                    "title-lg": ["20px", {"lineHeight": "140%", "fontWeight": "700"}],
                    "label-md": ["14px", {"lineHeight": "100%", "letterSpacing": "0.01em", "fontWeight": "600"}],
                    "headline-lg": ["48px", {"lineHeight": "120%", "letterSpacing": "0.05em", "fontWeight": "400"}]
            }
          },
        },
      }
    </script>
</head>
<body class="text-on-background bg-background">
<!-- TopNavBar -->
<header class="fixed top-0 w-full z-50 bg-background/80 backdrop-blur-xl border-b border-white/10 shadow-lg h-20">
<div class="flex justify-between items-center w-full px-8 h-full max-w-[1440px] mx-auto">
<div class="font-headline-md text-headline-md text-primary tracking-wider bebas">FITS MOTOR</div>
<nav class="hidden md:flex gap-8">
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Beranda</a>
<a class="text-primary border-b-2 border-primary pb-1 font-label-md text-label-md" href="#">Produk</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Booking</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Tentang</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Kontak</a>
</nav>
<div class="flex items-center gap-6">
<button class="relative active:scale-95 transition-transform duration-150">
<span class="material-symbols-outlined text-on-surface">shopping_cart</span>
<span class="absolute -top-2 -right-2 bg-primary-container text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold">2</span>
</button>
<button class="bg-primary-container text-on-primary-container px-6 py-2 bebas text-lg hover:brightness-110 transition-all">Masuk</button>
</div>
</div>
</header>
<main class="pt-24 pb-20 max-w-[1440px] mx-auto px-8">
<!-- FRAME 1: CART PAGE -->
<section class="grid grid-cols-1 lg:grid-cols-12 gap-gutter mb-20">
<!-- Left Column: Items -->
<div class="lg:col-span-8">
<h1 class="font-headline-lg text-headline-lg mb-8 bebas uppercase">Keranjang Belanja 🛒</h1>
<div class="space-y-4">
<!-- Cart Item 1 -->
<div class="bg-surface-card border border-white/5 p-6 rounded-lg flex items-center gap-6">
<div class="w-24 h-24 bg-surface-container rounded-lg overflow-hidden flex-shrink-0">
<img class="w-full h-full object-cover" data-alt="A high-performance motorcycle engine component featuring intricate metallic details and precision engineering. The component is showcased in a dark industrial garage setting with dramatic rim lighting and deep blue shadows. The atmosphere is professional and high-tech, emphasizing premium craftsmanship and technical excellence." src="https://lh3.googleusercontent.com/aida-public/AB6AXuACMS2jfW6hz3eZAkGL8a8TgUaPDPIkr671uLYpqmdEZN4b0tNBAosX2x51GGyns6CXoo5leeVw0xTh7Pzo7awTFM_6cz3mqDEBgxTWAB7qvWDXu5qybUpAM6nknmpG30Mw_lwWbYRVmB786bBtmWOE7K_8YHwwui19gLgpBUi0tFqm4BtJ9YP4KXX1sGNkq8F6-zB-hrpGB9CCQ-lDPTociUuRj95_GvQoUFrorsPHxBXmPL8sGgwgzNf4lnnl2q8PmQmu_j2YUmc"/>
</div>
<div class="flex-grow">
<h3 class="font-title-lg text-title-lg text-white font-bold">Racing Exhaust System X-500</h3>
<p class="text-text-secondary text-body-md">Carbon Fiber Finish - Universal Fit</p>
<div class="mt-4 flex items-center justify-between">
<div class="flex items-center bg-surface-container rounded overflow-hidden">
<button class="px-3 py-1 hover:bg-surface-accent text-primary text-xl">−</button>
<span class="px-4 font-data-mono text-data-mono text-white">1</span>
<button class="px-3 py-1 hover:bg-surface-accent text-primary text-xl">+</button>
</div>
<span class="text-primary-container font-headline-md text-2xl bebas">IDR 4.500.000</span>
</div>
</div>
<button class="text-on-surface-variant hover:text-danger-red transition-colors">
<span class="material-symbols-outlined">delete</span>
</button>
</div>
<!-- Cart Item 2 -->
<div class="bg-surface-card border border-white/5 p-6 rounded-lg flex items-center gap-6">
<div class="w-24 h-24 bg-surface-container rounded-lg overflow-hidden flex-shrink-0">
<img class="w-full h-full object-cover" data-alt="A premium full-face carbon fiber motorcycle helmet with a sleek matte finish and aerodynamic curves. The lighting is aggressive and high-contrast, casting sharp highlights on the carbon weave. The background is a moody, dark industrial workshop with technical accents in deep navy and vibrant orange." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBCoXDg-a4dIgVZG92QgOGfQwSax-LhYWDQmiq3ejXZVayon-MEIU4aYRHbbfyl7tIMyUOPLnO6lMzAymD_8xYVLajvl94WROUzVgAxYlDkDccn4UBZH7wV4b3zFxHX4PVSo0VD5QTZX5cQlDpTCcW3pn20GDXUlluGcdpBTMTbBydO5f1puRAWHsCJ03_l2XQbdaUSbDU2-Re3MGw5w3hf6a3mXoqrN4sKLrbLrInFayHaAHsCVBNPBvQgKMuOS9D5ZxCHFa9BNu8"/>
</div>
<div class="flex-grow">
<h3 class="font-title-lg text-title-lg text-white font-bold">Carbon Pro Helmet V2</h3>
<p class="text-text-secondary text-body-md">Matte Black - Large</p>
<div class="mt-4 flex items-center justify-between">
<div class="flex items-center bg-surface-container rounded overflow-hidden">
<button class="px-3 py-1 hover:bg-surface-accent text-primary text-xl">−</button>
<span class="px-4 font-data-mono text-data-mono text-white">1</span>
<button class="px-3 py-1 hover:bg-surface-accent text-primary text-xl">+</button>
</div>
<span class="text-primary-container font-headline-md text-2xl bebas">IDR 7.200.000</span>
</div>
</div>
<button class="text-on-surface-variant hover:text-danger-red transition-colors">
<span class="material-symbols-outlined">delete</span>
</button>
</div>
</div>
</div>
<!-- Right Column: Summary -->
<div class="lg:col-span-4">
<div class="sticky top-24 bg-surface-card border-t-2 border-primary-container p-8 rounded-lg shadow-2xl">
<h2 class="font-headline-md text-headline-md text-white mb-6 bebas">Ringkasan Pesanan</h2>
<div class="space-y-4 mb-8">
<div class="flex justify-between text-body-md">
<span class="text-text-secondary">Subtotal</span>
<span class="text-white font-data-mono">IDR 11.700.000</span>
</div>
<div class="flex justify-between text-body-md">
<span class="text-text-secondary">Pengiriman</span>
<span class="text-text-secondary">Belum Dihitung</span>
</div>
<div class="flex justify-between text-body-md">
<span class="text-text-secondary">Diskon</span>
<span class="text-tertiary-container">- IDR 200.000</span>
</div>
<div class="pt-4 border-t border-white/5 flex justify-between items-end">
<span class="text-white font-bold">TOTAL</span>
<span class="text-primary-container font-headline-lg text-3xl bebas">IDR 11.500.000</span>
</div>
</div>
<div class="mb-6">
<label class="text-label-md font-label-md text-text-secondary mb-2 block uppercase">Kode Voucher</label>
<div class="flex gap-2">
<input class="flex-grow bg-surface-container border border-white/10 rounded px-4 py-2 text-white focus:border-primary-container outline-none transition-all" placeholder="MASUKKAN KODE" type="text"/>
<button class="bg-surface-accent text-white px-4 py-2 bebas text-lg hover:bg-opacity-80">APPLY</button>
</div>
</div>
<button class="w-full bg-primary-container text-white py-4 bebas text-2xl flex items-center justify-center gap-2 hover:brightness-110 active:scale-95 transition-all">
                        LANJUT KE CHECKOUT <span class="material-symbols-outlined">arrow_forward</span>
</button>
</div>
</div>
</section>
<!-- PROGRESS BAR -->
<div class="max-w-4xl mx-auto mb-12">
<div class="flex items-center justify-between relative">
<div class="absolute top-1/2 left-0 w-full h-[2px] bg-white/10 -translate-y-1/2 z-0"></div>
<div class="absolute top-1/2 left-0 w-1/2 h-[2px] bg-primary-container -translate-y-1/2 z-0"></div>
<div class="relative z-10 flex flex-col items-center">
<div class="w-10 h-10 rounded-full bg-primary-container text-white flex items-center justify-center font-bold">1</div>
<span class="text-xs mt-2 text-primary uppercase font-bold tracking-wider">Alamat</span>
</div>
<div class="relative z-10 flex flex-col items-center">
<div class="w-10 h-10 rounded-full bg-primary-container text-white flex items-center justify-center font-bold">2</div>
<span class="text-xs mt-2 text-primary uppercase font-bold tracking-wider">Pengiriman</span>
</div>
<div class="relative z-10 flex flex-col items-center">
<div class="w-10 h-10 rounded-full bg-surface-container-high border-2 border-white/10 text-text-secondary flex items-center justify-center font-bold">3</div>
<span class="text-xs mt-2 text-text-secondary uppercase font-bold tracking-wider">Pembayaran</span>
</div>
</div>
</div>
<!-- FRAME 4-6: CHECKOUT FLOW -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter mb-20">
<!-- Step 1 & 2: Address & Shipping -->
<div class="space-y-gutter">
<!-- Address Selection -->
<div class="bg-surface-card p-8 border border-white/5 rounded-lg shadow-xl">
<div class="flex justify-between items-center mb-6">
<h2 class="font-headline-md text-headline-md text-white bebas">Alamat Pengiriman</h2>
<a class="text-primary-container text-sm font-bold flex items-center gap-1" href="#">
<span class="material-symbols-outlined text-sm">add_circle</span> TAMBAH BARU
                        </a>
</div>
<div class="border-2 border-primary-container bg-surface-accent p-4 rounded-lg mb-4 relative">
<div class="absolute top-4 right-4">
<span class="bg-primary-container text-white text-[10px] px-2 py-1 font-bold">UTAMA</span>
</div>
<p class="font-bold text-white mb-1">Rumah (Budi Santoso)</p>
<p class="text-on-surface-variant text-sm leading-relaxed">Jl. Balap Motor No. 42, Senayan, Jakarta Selatan, 12190. (+62) 812-3456-7890</p>
</div>
<div class="border border-white/10 p-4 rounded-lg opacity-60 hover:opacity-100 transition-opacity cursor-pointer">
<p class="font-bold text-white mb-1">Kantor (FITS Creative)</p>
<p class="text-on-surface-variant text-sm">Gedung Astra Tower, Lt. 22, Sudirman, Jakarta Pusat.</p>
</div>
</div>
<!-- Shipping Selector -->
<div class="bg-surface-card p-8 border border-white/5 rounded-lg shadow-xl">
<h2 class="font-headline-md text-headline-md text-white mb-6 bebas">Pilih Kurir</h2>
<div class="flex gap-4 mb-6 overflow-x-auto pb-2">
<button class="px-6 py-2 bg-primary-container text-white bebas text-lg rounded whitespace-nowrap">JNE EXPRESS</button>
<button class="px-6 py-2 bg-surface-container text-on-surface-variant bebas text-lg rounded border border-white/5 whitespace-nowrap hover:border-primary-container">SICEPAT</button>
<button class="px-6 py-2 bg-surface-container text-on-surface-variant bebas text-lg rounded border border-white/5 whitespace-nowrap hover:border-primary-container">ANTERAJA</button>
</div>
<div class="space-y-3">
<div class="flex items-center justify-between p-4 bg-surface-container border border-primary-container rounded-lg">
<div class="flex items-center gap-3">
<div class="w-5 h-5 rounded-full border-4 border-primary-container"></div>
<div>
<p class="font-bold text-white">REGULAR (2-3 Hari)</p>
<p class="text-xs text-text-secondary">Estimasi tiba 15-16 Okt</p>
</div>
</div>
<span class="font-data-mono text-primary-container">IDR 35.000</span>
</div>
<div class="flex items-center justify-between p-4 bg-surface-container border border-white/5 rounded-lg hover:border-white/20 transition-all cursor-pointer">
<div class="flex items-center gap-3">
<div class="w-5 h-5 rounded-full border-2 border-white/20"></div>
<div>
<p class="font-bold text-white">YES (Yakin Esok Sampai)</p>
<p class="text-xs text-text-secondary">Estimasi tiba Besok</p>
</div>
</div>
<span class="font-data-mono text-white">IDR 85.000</span>
</div>
</div>
</div>
</div>
<!-- Step 3: Payment -->
<div class="bg-surface-card p-8 border border-white/5 rounded-lg shadow-xl h-fit">
<h2 class="font-headline-md text-headline-md text-white mb-6 bebas">Metode Pembayaran</h2>
<div class="space-y-4">
<!-- Bank Transfer Accordion -->
<div class="border border-primary-container rounded-lg overflow-hidden">
<button class="w-full flex items-center justify-between p-5 bg-surface-accent">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary">account_balance</span>
<span class="font-bold text-white uppercase tracking-wider">Transfer Bank (VA)</span>
</div>
<span class="material-symbols-outlined">expand_less</span>
</button>
<div class="p-5 bg-surface-container border-t border-white/5">
<div class="grid grid-cols-2 gap-4">
<label class="border-2 border-primary-container p-4 rounded flex flex-col items-center gap-2 cursor-pointer bg-surface-accent">
<div class="h-8 w-16 bg-white/10 rounded flex items-center justify-center font-black text-xs italic">BRI</div>
<span class="text-xs text-white">Virtual Account</span>
</label>
<label class="border border-white/10 p-4 rounded flex flex-col items-center gap-2 cursor-pointer hover:border-primary-container transition-all">
<div class="h-8 w-16 bg-white/10 rounded flex items-center justify-center font-black text-xs italic">BCA</div>
<span class="text-xs text-text-secondary">Virtual Account</span>
</label>
<label class="border border-white/10 p-4 rounded flex flex-col items-center gap-2 cursor-pointer hover:border-primary-container transition-all">
<div class="h-8 w-16 bg-white/10 rounded flex items-center justify-center font-black text-xs italic">MANDIRI</div>
<span class="text-xs text-text-secondary">Virtual Account</span>
</label>
<label class="border border-white/10 p-4 rounded flex flex-col items-center gap-2 cursor-pointer hover:border-primary-container transition-all">
<div class="h-8 w-16 bg-white/10 rounded flex items-center justify-center font-black text-xs italic">BNI</div>
<span class="text-xs text-text-secondary">Virtual Account</span>
</label>
</div>
</div>
</div>
<!-- Other Methods -->
<button class="w-full flex items-center justify-between p-5 bg-surface-container border border-white/5 rounded-lg opacity-80 hover:opacity-100">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined">qr_code_2</span>
<span class="font-bold text-white uppercase tracking-wider">QRIS (GoPay, OVO, Dana)</span>
</div>
<span class="material-symbols-outlined">expand_more</span>
</button>
<button class="w-full flex items-center justify-between p-5 bg-surface-container border border-white/5 rounded-lg opacity-80 hover:opacity-100">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined">storefront</span>
<span class="font-bold text-white uppercase tracking-wider">Gerai Ritel (Alfamart/Indomaret)</span>
</div>
<span class="material-symbols-outlined">expand_more</span>
</button>
</div>
<div class="mt-8 pt-8 border-t border-white/5">
<button class="w-full bg-primary-container text-white py-4 bebas text-2xl shadow-lg hover:shadow-primary/20 transition-all">
                        KONFIRMASI &amp; BAYAR SEKARANG
                    </button>
<p class="text-[10px] text-text-secondary mt-4 text-center">Dengan mengklik tombol di atas, Anda menyetujui Syarat &amp; Ketentuan kami.</p>
</div>
</div>
</div>
<!-- FRAME 7: PAYMENT INSTRUCTION -->
<section class="max-w-2xl mx-auto mb-20">
<div class="bg-surface-card border border-primary-container rounded-lg p-10 text-center shadow-2xl relative overflow-hidden">
<!-- Background Glow -->
<div class="absolute -top-20 -right-20 w-64 h-64 bg-primary/10 rounded-full blur-3xl"></div>
<div class="relative z-10">
<div class="inline-flex items-center gap-2 bg-error-container/20 text-error px-4 py-2 rounded-full mb-8">
<span class="material-symbols-outlined text-sm">schedule</span>
<span class="text-xs font-bold font-data-mono">SISA WAKTU: 23:59:45</span>
</div>
<h2 class="font-headline-md text-headline-md text-white mb-2 bebas">PEMBAYARAN VIRTUAL ACCOUNT</h2>
<p class="text-text-secondary mb-8">Silakan selesaikan pembayaran sebelum batas waktu berakhir.</p>
<div class="bg-surface-container-low p-8 rounded-lg border border-white/5 mb-8">
<div class="mb-6">
<p class="text-xs text-text-secondary uppercase mb-2 tracking-widest">Nomor Virtual Account BRI</p>
<div class="flex items-center justify-center gap-4">
<span class="font-headline-md text-4xl text-primary tracking-widest bebas">8077 0812 3456 7890</span>
<button class="p-2 text-primary-container hover:bg-white/5 rounded-full transition-colors">
<span class="material-symbols-outlined">content_copy</span>
</button>
</div>
</div>
<div class="pt-6 border-t border-white/5">
<p class="text-xs text-text-secondary uppercase mb-2 tracking-widest">Total Pembayaran</p>
<p class="text-primary-container font-headline-lg text-4xl bebas">IDR 11.535.000</p>
</div>
</div>
<div class="text-left space-y-4 mb-10">
<h4 class="font-bold text-white text-sm uppercase">Instruksi Pembayaran ATM BRI:</h4>
<ol class="text-sm text-text-secondary space-y-2 list-decimal ml-4">
<li>Masukkan kartu ATM dan PIN Anda.</li>
<li>Pilih menu <span class="text-white">Transaksi Lain</span> &gt; <span class="text-white">Pembayaran</span>.</li>
<li>Pilih <span class="text-white">Lainnya</span> &gt; <span class="text-white">Briva</span>.</li>
<li>Masukkan <span class="text-white">Nomor Virtual Account</span> di atas.</li>
<li>Periksa detail transaksi dan tekan <span class="text-white">Ya</span> untuk membayar.</li>
</ol>
</div>
<div class="flex flex-col md:flex-row gap-4">
<button class="flex-grow bg-primary-container text-white py-4 bebas text-xl hover:brightness-110 transition-all">CEK STATUS PESANAN</button>
<button class="flex-grow border-2 border-white/10 text-white py-4 bebas text-xl hover:bg-white/5 transition-all">HUBUNGI KAMI</button>
</div>
</div>
</div>
</section>
</main>
<!-- Footer -->
<footer class="bg-surface-container-lowest border-t border-white/5">
<div class="grid grid-cols-1 md:grid-cols-4 gap-gutter max-w-[1440px] mx-auto px-8 py-16">
<div class="col-span-1">
<div class="font-headline-md text-headline-md text-primary bebas mb-6">FITS MOTOR</div>
<p class="text-on-surface-variant text-body-md leading-relaxed">Penyedia suku cadang premium dan layanan servis profesional untuk performa mesin maksimal Anda.</p>
</div>
<div>
<h4 class="text-white font-bold mb-6 uppercase tracking-wider">Layanan</h4>
<ul class="space-y-4 text-on-surface-variant font-label-md text-label-md">
<li><a class="hover:text-secondary transition-colors" href="#">Layanan Servis</a></li>
<li><a class="hover:text-secondary transition-colors" href="#">Suku Cadang</a></li>
<li><a class="hover:text-secondary transition-colors" href="#">Garansi Resmi</a></li>
</ul>
</div>
<div>
<h4 class="text-white font-bold mb-6 uppercase tracking-wider">Perusahaan</h4>
<ul class="space-y-4 text-on-surface-variant font-label-md text-label-md">
<li><a class="hover:text-secondary transition-colors" href="#">Karir</a></li>
<li><a class="hover:text-secondary transition-colors" href="#">Kebijakan Privasi</a></li>
<li><a class="hover:text-secondary transition-colors" href="#">Syarat &amp; Ketentuan</a></li>
</ul>
</div>
<div>
<h4 class="text-white font-bold mb-6 uppercase tracking-wider">Ikuti Kami</h4>
<div class="flex gap-4">
<div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center hover:bg-primary-container transition-all cursor-pointer">
<span class="material-symbols-outlined text-white">public</span>
</div>
<div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center hover:bg-primary-container transition-all cursor-pointer">
<span class="material-symbols-outlined text-white">mail</span>
</div>
<div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center hover:bg-primary-container transition-all cursor-pointer">
<span class="material-symbols-outlined text-white">phone</span>
</div>
</div>
</div>
</div>
<div class="max-w-[1440px] mx-auto px-8 py-8 border-t border-white/5 text-center text-on-surface-variant text-label-md">
            © 2024 FITS MOTOR. Premium Motorcycle Workshop.
        </div>
</footer>
</body></html>