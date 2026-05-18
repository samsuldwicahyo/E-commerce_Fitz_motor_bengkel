<!DOCTYPE html>

<html class="dark" lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&amp;family=JetBrains+Mono:wght@500&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 48;
        }
        body {
            background-color: #111125;
            color: #e2e0fc;
            font-family: 'DM Sans', sans-serif;
        }
        .glass-effect {
            backdrop-filter: blur(12px);
            background-color: rgba(26, 26, 46, 0.8);
        }
        .industrial-border {
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .primary-glow:hover {
            box-shadow: 0px 20px 40px rgba(255, 69, 0, 0.08);
        }
    </style>
<script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              "colors": {
                      "outline-variant": "#5d4038",
                      "danger-red": "#E53E3E",
                      "surface-card": "#16213E",
                      "secondary-fixed-dim": "#f1c014",
                      "inverse-surface": "#e2e0fc",
                      "on-tertiary": "#00391f",
                      "secondary": "#ffd660",
                      "on-primary-fixed-variant": "#872000",
                      "on-secondary": "#3d2f00",
                      "surface-accent": "#0F3460",
                      "on-secondary-fixed-variant": "#584400",
                      "surface-variant": "#333348",
                      "surface-container-highest": "#333348",
                      "error": "#ffb4ab",
                      "on-primary-fixed": "#3b0900",
                      "surface-bright": "#37374d",
                      "on-tertiary-fixed": "#002110",
                      "tertiary": "#74db9d",
                      "primary": "#ffb5a0",
                      "secondary-container": "#e8b800",
                      "surface-tint": "#ffb5a0",
                      "surface-dim": "#111125",
                      "on-tertiary-fixed-variant": "#00522f",
                      "surface": "#111125",
                      "primary-fixed": "#ffdbd1",
                      "outline": "#ad887e",
                      "surface-container": "#1e1e32",
                      "secondary-fixed": "#ffe08e",
                      "primary-container": "#ff5625",
                      "tertiary-fixed-dim": "#74db9d",
                      "on-primary-container": "#541100",
                      "text-secondary": "#A0AEC0",
                      "on-secondary-fixed": "#241a00",
                      "on-background": "#e2e0fc",
                      "on-secondary-container": "#5f4a00",
                      "surface-container-low": "#1a1a2e",
                      "on-surface": "#e2e0fc",
                      "tertiary-fixed": "#91f8b8",
                      "on-error": "#690005",
                      "admin-sidebar": "#0F1724",
                      "surface-container-lowest": "#0c0c1f",
                      "on-tertiary-container": "#00311a",
                      "background": "#111125",
                      "inverse-on-surface": "#2f2e43",
                      "on-primary": "#601400",
                      "text-primary": "#FFFFFF",
                      "tertiary-container": "#3aa36b",
                      "on-surface-variant": "#e7bdb2",
                      "primary-fixed-dim": "#ffb5a0",
                      "on-error-container": "#ffdad6",
                      "surface-container-high": "#28283d",
                      "inverse-primary": "#b12d00",
                      "error-container": "#93000a"
              },
              "borderRadius": {
                      "DEFAULT": "0.125rem",
                      "lg": "0.25rem",
                      "xl": "0.5rem",
                      "full": "0.75rem"
              },
              "spacing": {
                      "margin-mobile": "16px",
                      "gutter": "24px",
                      "stack-sm": "4px",
                      "container-max": "1440px",
                      "base": "8px",
                      "stack-lg": "24px",
                      "stack-md": "12px"
              },
              "fontFamily": {
                      "headline-lg": ["Bebas Neue"],
                      "label-md": ["DM Sans"],
                      "headline-md": ["Bebas Neue"],
                      "data-mono": ["JetBrains Mono"],
                      "display-hero-mobile": ["Bebas Neue"],
                      "display-hero": ["Bebas Neue"],
                      "body-md": ["DM Sans"],
                      "body-lg": ["DM Sans"],
                      "title-lg": ["DM Sans"]
              },
              "fontSize": {
                      "headline-lg": ["48px", {"lineHeight": "120%", "letterSpacing": "0.05em", "fontWeight": "400"}],
                      "label-md": ["14px", {"lineHeight": "100%", "letterSpacing": "0.01em", "fontWeight": "600"}],
                      "headline-md": ["32px", {"lineHeight": "120%", "letterSpacing": "0.05em", "fontWeight": "400"}],
                      "data-mono": ["14px", {"lineHeight": "140%", "fontWeight": "500"}],
                      "display-hero-mobile": ["48px", {"lineHeight": "110%", "letterSpacing": "0.02em", "fontWeight": "700"}],
                      "display-hero": ["96px", {"lineHeight": "100%", "letterSpacing": "0.02em", "fontWeight": "700"}],
                      "body-md": ["16px", {"lineHeight": "160%", "fontWeight": "400"}],
                      "body-lg": ["18px", {"lineHeight": "160%", "fontWeight": "400"}],
                      "title-lg": ["20px", {"lineHeight": "140%", "fontWeight": "700"}]
              }
            },
          },
        }
    </script>
</head>
<body class="bg-background text-on-background">
<!-- TopNavBar -->
<nav class="fixed top-0 w-full z-50 bg-background/80 backdrop-blur-xl dark:bg-background/80 border-b border-white/10 shadow-lg">
<div class="flex justify-between items-center w-full px-8 h-20 max-w-[1440px] mx-auto">
<div class="font-headline-md text-headline-md text-primary-container tracking-wider">FITS MOTOR</div>
<div class="hidden md:flex items-center gap-stack-lg">
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Beranda</a>
<a class="text-primary border-b-2 border-primary pb-1 font-label-md text-label-md" href="#">Produk</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Booking</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Tentang</a>
<a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors duration-200" href="#">Kontak</a>
</div>
<div class="flex items-center gap-stack-md">
<button class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors" data-icon="shopping_cart">shopping_cart</button>
<div class="h-6 w-[1px] bg-white/10 mx-2"></div>
<button class="font-label-md text-label-md text-on-surface-variant hover:text-primary">Masuk</button>
<button class="bg-[#38A169] text-white px-6 py-2 rounded-lg font-label-md text-label-md flex items-center gap-2 hover:brightness-110 active:scale-95 transition-all">
                    Konsultasi WA
                </button>
</div>
</div>
</nav>
<!-- Main Content -->
<main class="pt-28 pb-16 px-8 max-w-[1440px] mx-auto">
<!-- Breadcrumb -->
<nav class="flex items-center gap-2 mb-8 font-label-md text-label-md text-on-surface-variant">
<a class="hover:text-primary" href="#">Beranda</a>
<span class="material-symbols-outlined text-[14px]">chevron_right</span>
<a class="hover:text-primary" href="#">Produk</a>
<span class="material-symbols-outlined text-[14px]">chevron_right</span>
<a class="hover:text-primary" href="#">Aksesori</a>
<span class="material-symbols-outlined text-[14px]">chevron_right</span>
<span class="text-primary">Lampu LED Projector</span>
</nav>
<div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
<!-- LEFT — IMAGE GALLERY -->
<div class="lg:col-span-7">
<div class="relative bg-white rounded-xl aspect-square flex items-center justify-center overflow-hidden shadow-2xl group cursor-zoom-in">
<img alt="Lampu LED Projector" class="w-4/5 h-4/5 object-contain" data-alt="A high-end motorcycle LED projector lamp assembly presented in a crisp, studio setting with a clean white background. The product features intricate internal cooling fins, a crystal-clear spherical lens, and premium metallic casing. The lighting is sharp, emphasizing the precision engineering and high-performance technical quality of the motorcycle component. The aesthetic is professional and commercial." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAzkneBxRKg1pzORgv2geDUC69Z6MPMwswA_FkxfoOWlQmfSXhqmajVxhLRRMPhf7lJCHFtzUv6y6T7ib4CTiEdjjK1HqLdO1GHUZb8Ao18PfiysGS4DMpK8qtmfwAULL6kiYmANOspOFq6ZXJXkUp6xJJFUYo_DAYIu0siZQhLpuPQ-b2BwE9aod1mznd_DquPLpm7Onf4WU8uPhgnnWuceZg3Ja-zA8RZz9V7tNl5cQ1OCo_d3ULAt55arKIpxOBRSV0z6aEqqkk"/>
<div class="absolute top-6 left-6 bg-danger-red text-white px-4 py-1 rounded-full font-label-md text-label-md font-bold tracking-wider">DISKON 20%</div>
<div class="absolute top-6 right-6 bg-[#38A169] text-white px-3 py-1 rounded-lg font-label-md text-label-md font-medium text-[12px]">STOK: 48 unit</div>
<!-- Zoom Icon Overlay -->
<div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 flex items-center justify-center transition-all opacity-0 group-hover:opacity-100">
<div class="bg-white/80 backdrop-blur p-4 rounded-full">
<span class="material-symbols-outlined text-background scale-150" data-icon="zoom_in">zoom_in</span>
</div>
</div>
</div>
<div class="grid grid-cols-4 gap-4 mt-6">
<button class="bg-surface-card rounded-lg overflow-hidden border-2 border-primary-container p-2 aspect-square flex items-center justify-center">
<img alt="Thumbnail 1" class="w-full h-full object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBCbHPqA68eau0QdNF_eUEL8_QrSaYoJ0zSvgm_qxMpjv_Dt8iKm5TJH-frN-dMb1IFgYbY_PCIxC0xLzyDnXaHF7sOxJlNbsF_1zgUR5_e4dTJJe1K-qcC9NsUyh1G6fg-h8cHNzT5HTrYPvxwJr6CVRzUnK5Kxj1gyiz999GTGx9GSaqE_jSI60WAwh9zBfgVNadY4pZ_9tqGKEsBVnekUNcZCtqx_KPUMmGiEaHRLn9lpR9f6lmoD7WB_k1iGZli4fb2s55Ykxg"/>
</button>
<button class="bg-surface-card rounded-lg overflow-hidden border border-white/5 p-2 aspect-square flex items-center justify-center hover:border-primary-container/50 transition-colors">
<img alt="Thumbnail 2" class="w-full h-full object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAAgNKs2bVCZFKfHor1QkD0TUaeGKSrUSIC0n-c0UBWX_xHdQGmG6wbU2CnYdLV7gyQI6upHgWH6ck7WwEVxunMiAjGyqRF94AioUD3EB4OppivdiqbEOQfL7-Uh6WRVniZ7x7SFWaD7u0Jr5pAr9t3DFrSviZzh8JCjgT2yCS8zizvi8TyW4A2wTVR0WasxKBnlhojPBMAmBJTk0uNkwTFVRLPa3uB2sv1oDbmvvqSo0z83-yERs1_o_CVegDirY2fUkdXQSjn41c"/>
</button>
<button class="bg-surface-card rounded-lg overflow-hidden border border-white/5 p-2 aspect-square flex items-center justify-center hover:border-primary-container/50 transition-colors">
<img alt="Thumbnail 3" class="w-full h-full object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAaktDbTBqKXCh2HuEuzv4vj6kQfmZf2ZCGWlUuf1_wHK5HgnJ3zVgnDccek17nrk5V8wr59Ro7iEiDgqEcy3TqEm-mHcGiK_Vu8BvlMvGuVuaenlP0PQkyh1P130JU8bF5cHGljmBWQKThx2_79iVWOEvxQHFAhLw2BP1lO_NS3MciW_9hQJgTRIZLRxNO0XGJ6-O-LCgpIyp2fg2BohxIHmTMqfanhD8rNw_efsxUs8524TtpThhdDrv5-BPX8J2rafnGKiG1bm4"/>
</button>
<button class="bg-surface-card rounded-lg overflow-hidden border border-white/5 p-2 aspect-square flex items-center justify-center hover:border-primary-container/50 transition-colors">
<img alt="Thumbnail 4" class="w-full h-full object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBnLXtJi5DfBu5faF9HKnBqXgRjM_3nga2fbV7vtl7O-pGmbspDwX6cQcVA6C-hPxEnhKJOXKGFIxMbqJN-a4dLskgALTyTz0DUiVJZXSr8uQbXHizgqI_NZXQQuo8ZD6Z1VAN1nDC4CpY-y516_COnwaS4NLnxkT_ai2u08RmTTXoNSsyr75A8epTgfgZfnFWG2oBwl8klMohi_Q6TD4Dhdu5k7KxSWAT_P-fpEUKRn0svcU7wyMIw8NQvwH2gp_X0WUsTzDxKUDk"/>
</button>
</div>
</div>
<!-- RIGHT — PRODUCT INFO -->
<div class="lg:col-span-5 flex flex-col gap-6">
<div>
<span class="text-secondary font-headline-md text-[14px] tracking-[0.2em]">HONDA GENUINE PARTS</span>
<h1 class="font-headline-lg text-headline-lg text-white mt-2 leading-tight uppercase">Lampu LED Projector Honda Beat 2020-2024</h1>
<div class="flex items-center gap-4 mt-4 text-on-surface-variant font-label-md text-label-md">
<div class="flex items-center text-secondary">
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[18px]">star_half</span>
<span class="ml-2 text-white font-bold">4.8</span>
</div>
<div class="w-[1px] h-4 bg-white/10"></div>
<span>(342 ulasan)</span>
<div class="w-[1px] h-4 bg-white/10"></div>
<span>1.2k terjual</span>
</div>
</div>
<div class="bg-surface-card p-6 rounded-xl industrial-border">
<div class="flex items-center gap-4">
<span class="text-on-surface-variant line-through font-body-md text-body-md">Rp 285.000</span>
<span class="bg-[#38A169]/20 text-[#38A169] px-3 py-1 rounded-full font-label-md text-[12px] font-bold">Hemat Rp 57.000</span>
</div>
<div class="font-headline-lg text-[42px] text-primary-container mt-1">Rp 228.000</div>
</div>
<!-- COMPATIBILITY SELECTOR -->
<div class="flex flex-col gap-3">
<label class="font-label-md text-label-md text-white">Kompatibilitas Kendaraan:</label>
<div class="flex flex-wrap gap-2">
<button class="px-6 py-2 rounded-full font-label-md text-label-md border-2 border-primary-container bg-primary-container text-on-primary-container transition-all">Honda Beat</button>
<button class="px-6 py-2 rounded-full font-label-md text-label-md border-2 border-white/10 bg-transparent text-on-surface-variant hover:border-primary-container transition-all">Honda Vario</button>
<button class="px-6 py-2 rounded-full font-label-md text-label-md border-2 border-white/10 bg-transparent text-on-surface-variant hover:border-primary-container transition-all">Honda PCX</button>
<button class="px-6 py-2 rounded-full font-label-md text-label-md border-2 border-white/10 bg-transparent text-on-surface-variant hover:border-primary-container transition-all">Honda CBR</button>
</div>
</div>
<!-- QUANTITY SELECTOR -->
<div class="flex flex-col gap-3">
<label class="font-label-md text-label-md text-white">Jumlah Pesanan:</label>
<div class="flex items-center border-2 border-primary-container rounded-lg w-fit overflow-hidden">
<button class="px-4 py-2 hover:bg-primary-container/10 transition-colors text-primary-container">
<span class="material-symbols-outlined">remove</span>
</button>
<div class="w-12 text-center font-bold font-data-mono">2</div>
<button class="px-4 py-2 hover:bg-primary-container/10 transition-colors text-primary-container">
<span class="material-symbols-outlined">add</span>
</button>
</div>
</div>
<!-- ACTION BUTTONS -->
<div class="flex flex-col gap-3 mt-4">
<div class="grid grid-cols-2 gap-3">
<button class="border-2 border-primary-container text-primary-container font-headline-md text-headline-md py-4 rounded-xl hover:bg-primary-container/5 transition-all flex items-center justify-center gap-2">
<span class="material-symbols-outlined">shopping_cart</span>
                            TAMBAH KE KERANJANG
                        </button>
<button class="bg-primary-container text-on-primary-container font-headline-md text-headline-md py-4 rounded-xl hover:brightness-110 transition-all flex items-center justify-center gap-2">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">bolt</span>
                            BELI SEKARANG
                        </button>
</div>
<button class="bg-[#38A169] text-white font-headline-md text-headline-md py-4 rounded-xl hover:brightness-110 transition-all flex items-center justify-center gap-2">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">chat</span>
                        Tanya via WhatsApp
                    </button>
</div>
<!-- TRUST BADGES -->
<div class="grid grid-cols-3 gap-2 py-6 border-t border-white/5 mt-4">
<div class="flex flex-col items-center text-center gap-2">
<div class="w-10 h-10 rounded-full bg-surface-accent flex items-center justify-center text-tertiary">
<span class="material-symbols-outlined" data-icon="verified">verified</span>
</div>
<span class="font-label-md text-[10px] text-on-surface-variant uppercase tracking-wider">Garansi Resmi</span>
</div>
<div class="flex flex-col items-center text-center gap-2">
<div class="w-10 h-10 rounded-full bg-surface-accent flex items-center justify-center text-tertiary">
<span class="material-symbols-outlined" data-icon="local_shipping">local_shipping</span>
</div>
<span class="font-label-md text-[10px] text-on-surface-variant uppercase tracking-wider">Free Ongkir</span>
</div>
<div class="flex flex-col items-center text-center gap-2">
<div class="w-10 h-10 rounded-full bg-surface-accent flex items-center justify-center text-tertiary">
<span class="material-symbols-outlined" data-icon="history">history</span>
</div>
<span class="font-label-md text-[10px] text-on-surface-variant uppercase tracking-wider">Retur 7 Hari</span>
</div>
</div>
</div>
</div>
<!-- TABS SECTION -->
<section class="mt-24">
<div class="flex border-b border-white/10 mb-8">
<button class="px-8 py-4 font-headline-md text-headline-md text-primary-container border-b-2 border-primary-container">Deskripsi</button>
<button class="px-8 py-4 font-headline-md text-headline-md text-on-surface-variant hover:text-white transition-colors">Spesifikasi</button>
<button class="px-8 py-4 font-headline-md text-headline-md text-on-surface-variant hover:text-white transition-colors">Cara Pemasangan</button>
<button class="px-8 py-4 font-headline-md text-headline-md text-on-surface-variant hover:text-white transition-colors">Ulasan (342)</button>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-16">
<div>
<h3 class="font-headline-md text-headline-md text-white mb-6">Spesifikasi Teknis</h3>
<div class="bg-surface-card rounded-xl overflow-hidden border border-white/5">
<div class="grid grid-cols-2 border-b border-white/5">
<div class="p-4 bg-white/5 font-label-md text-label-md text-on-surface-variant">Model</div>
<div class="p-4 font-label-md text-label-md text-white">Projector LED V4.0 High-Beam</div>
</div>
<div class="grid grid-cols-2 border-b border-white/5">
<div class="p-4 bg-white/5 font-label-md text-label-md text-on-surface-variant">Voltase</div>
<div class="p-4 font-label-md text-label-md text-white">DC 12V - 15V</div>
</div>
<div class="grid grid-cols-2 border-b border-white/5">
<div class="p-4 bg-white/5 font-label-md text-label-md text-on-surface-variant">Daya (Watt)</div>
<div class="p-4 font-label-md text-label-md text-white">35 Watt / Bulb</div>
</div>
<div class="grid grid-cols-2 border-b border-white/5">
<div class="p-4 bg-white/5 font-label-md text-label-md text-on-surface-variant">Temperatur Warna</div>
<div class="p-4 font-label-md text-label-md text-white">6000K (Crystal White)</div>
</div>
<div class="grid grid-cols-2">
<div class="p-4 bg-white/5 font-label-md text-label-md text-on-surface-variant">Sertifikasi</div>
<div class="p-4 font-label-md text-label-md text-white">IP68 Waterproof</div>
</div>
</div>
</div>
<div>
<h3 class="font-headline-md text-headline-md text-white mb-6">Ulasan Pelanggan</h3>
<div class="flex items-center gap-8 mb-8 bg-surface-accent/30 p-6 rounded-xl border border-primary-container/20">
<div class="text-center">
<div class="text-[56px] font-headline-lg leading-none text-white">4.8</div>
<div class="flex items-center justify-center text-secondary mt-2">
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<div class="font-label-md text-[12px] text-on-surface-variant mt-1">342 Ulasan</div>
</div>
<div class="flex-1 flex flex-col gap-2">
<div class="flex items-center gap-4">
<span class="font-label-md text-[12px] w-4 text-white">5</span>
<div class="flex-1 h-2 bg-white/5 rounded-full overflow-hidden">
<div class="h-full bg-secondary w-[85%]"></div>
</div>
</div>
<div class="flex items-center gap-4">
<span class="font-label-md text-[12px] w-4 text-white">4</span>
<div class="flex-1 h-2 bg-white/5 rounded-full overflow-hidden">
<div class="h-full bg-secondary w-[10%]"></div>
</div>
</div>
<div class="flex items-center gap-4">
<span class="font-label-md text-[12px] w-4 text-white">3</span>
<div class="flex-1 h-2 bg-white/5 rounded-full overflow-hidden">
<div class="h-full bg-secondary w-[3%]"></div>
</div>
</div>
<div class="flex items-center gap-4">
<span class="font-label-md text-[12px] w-4 text-white">2</span>
<div class="flex-1 h-2 bg-white/5 rounded-full overflow-hidden">
<div class="h-full bg-secondary w-[1%]"></div>
</div>
</div>
<div class="flex items-center gap-4">
<span class="font-label-md text-[12px] w-4 text-white">1</span>
<div class="flex-1 h-2 bg-white/5 rounded-full overflow-hidden">
<div class="h-full bg-secondary w-[1%]"></div>
</div>
</div>
</div>
</div>
<!-- Review Card -->
<div class="flex flex-col gap-4">
<div class="bg-surface-card p-4 rounded-lg industrial-border">
<div class="flex justify-between items-start mb-2">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-surface-accent flex items-center justify-center text-primary-container font-bold">R</div>
<div>
<div class="font-label-md text-label-md text-white">Rizky Pratama</div>
<div class="text-secondary text-[12px] flex">
<span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
</div>
</div>
<span class="text-on-surface-variant text-[12px]">2 hari lalu</span>
</div>
<p class="text-on-surface-variant font-body-md text-body-md">Barang sampai dengan aman. Kualitas cahaya mantap banget, sangat fokus dan terang dibanding lampu standar. Pemasangan PnP di Beat 2022 saya.</p>
</div>
</div>
</div>
</div>
</section>
<!-- RELATED PRODUCTS -->
<section class="mt-24">
<div class="flex justify-between items-end mb-8">
<div>
<h2 class="font-headline-md text-headline-md text-white uppercase tracking-wider">Produk Terkait</h2>
<div class="h-1 w-20 bg-primary-container mt-2"></div>
</div>
<div class="flex gap-2">
<button class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center text-white hover:bg-white/5"><span class="material-symbols-outlined">chevron_left</span></button>
<button class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center text-white hover:bg-white/5"><span class="material-symbols-outlined">chevron_right</span></button>
</div>
</div>
<div class="flex gap-gutter overflow-x-auto pb-8 snap-x no-scrollbar">
<!-- Product Card 1 -->
<div class="min-w-[280px] bg-surface-card rounded-xl overflow-hidden border border-white/5 group transition-all hover:-translate-y-2 snap-start">
<div class="relative bg-white aspect-square p-6 flex items-center justify-center">
<img alt="Related 1" class="w-full h-full object-contain" data-alt="Close up photography of high-performance motorcycle engine spark plugs on a clean white surface. The metal finish glows with technical precision under soft studio lighting. A high-contrast premium industrial aesthetic suitable for a motor parts catalog." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCUvg2GR9MmQxqkXvwfjrrSHEXtf1uPy717LTJPOw6QU9vUR6ahYkwIwrfigYqRvffJNGABZqPrUTWZjbwQ1nzglajKV_d2YFSjpLBiN4OsgI7Un4A3dncAeGGtkU3daq4e9vkbJZEiGVPSMtlKt-ac0akqgHvVex8PuXVijhqqiwbK7oapH14gLLIg2nR_SMB9SjTgf82nkNNTmLQw3wcfHLw1EcRu5RZrGWNrzvzexM-aKIhroxN7wMXLMTmRJUatjXKnQZ3_N7w"/>
<span class="absolute top-4 left-4 bg-danger-red text-white text-[10px] font-bold px-2 py-1 rounded">TERLARIS</span>
</div>
<div class="p-5">
<h4 class="font-label-md text-label-md text-white group-hover:text-primary-container transition-colors">Busi Iridium Racing Honda</h4>
<div class="font-headline-md text-headline-md text-primary-container mt-2">Rp 125.000</div>
<button class="w-full mt-4 border border-white/10 py-2 rounded font-label-md text-[12px] uppercase tracking-widest text-on-surface-variant hover:bg-white/5 transition-all">Lihat Detail</button>
</div>
</div>
<!-- Product Card 2 -->
<div class="min-w-[280px] bg-surface-card rounded-xl overflow-hidden border border-white/5 group transition-all hover:-translate-y-2 snap-start">
<div class="relative bg-white aspect-square p-6 flex items-center justify-center">
<img alt="Related 2" class="w-full h-full object-contain" data-alt="Motorcycle high-performance disk brake component with drilled holes for cooling. Shot in professional studio lighting on a bright white background. Minimalist industrial design focus with sharp details on the metal texture." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCUcywZOw20V6DabgrdzaREOF7idV0I9s5HCVfHhW8t_aPAE-mIDUpLTBfyZh4n4DkH3CXl2F7b9SHgcZHjNK8QVzSaVq_cArWzE8wbEWJ5nNGje58T7aPthZC2rq64d32yyrYwSt6LzyRN7wLcIztKOwjVao6WfTROQxKM1Al99KABYhAgi6jEiVzdVmnkuEKXjvg4CchFnCXJ9xVfXmD1uNtwZhR3kh_2Wq_v74c5Q8-N2MS1jzCqyof0RRqUN-0WAgdTrPiC9kw"/>
</div>
<div class="p-5">
<h4 class="font-label-md text-label-md text-white group-hover:text-primary-container transition-colors">Piringan Cakram Variasi</h4>
<div class="font-headline-md text-headline-md text-primary-container mt-2">Rp 450.000</div>
<button class="w-full mt-4 border border-white/10 py-2 rounded font-label-md text-[12px] uppercase tracking-widest text-on-surface-variant hover:bg-white/5 transition-all">Lihat Detail</button>
</div>
</div>
<!-- Product Card 3 -->
<div class="min-w-[280px] bg-surface-card rounded-xl overflow-hidden border border-white/5 group transition-all hover:-translate-y-2 snap-start">
<div class="relative bg-white aspect-square p-6 flex items-center justify-center">
<img alt="Related 3" class="w-full h-full object-contain" data-alt="High quality carbon fiber motorcycle rear view mirror set. Glossy carbon weave pattern visible under bright studio lights against a pure white background. Sleek, aerodynamic shape emphasizing premium motor accessory styling." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAxZEK1dfqb2wV5cR9cVfX7vWFBGKXMK86nxsGJanXwR3W06P-kh7i79dswV2Cd9MAe1nIhtgjcvchNiSC8l3I0kAjF4e8kYkDcklt0GdXMqHWDnZ5uISNPcXLEs92IUis4n1akHGjWOxtGL5dhJy_Q7w9OczRFX8ttMlNjwYNmnagTNwyiOHFSn3xKSFYKwrXiR63T9ppwZrQlSQMOKFk-dmAKeZ2uywn7DBjWTUWHUUqDIXb-xpAwjwjShO9UDWZyl27m0bLgnlQ"/>
</div>
<div class="p-5">
<h4 class="font-label-md text-label-md text-white group-hover:text-primary-container transition-colors">Spion Carbon Fiber</h4>
<div class="font-headline-md text-headline-md text-primary-container mt-2">Rp 320.000</div>
<button class="w-full mt-4 border border-white/10 py-2 rounded font-label-md text-[12px] uppercase tracking-widest text-on-surface-variant hover:bg-white/5 transition-all">Lihat Detail</button>
</div>
</div>
<!-- Product Card 4 -->
<div class="min-w-[280px] bg-surface-card rounded-xl overflow-hidden border border-white/5 group transition-all hover:-translate-y-2 snap-start">
<div class="relative bg-white aspect-square p-6 flex items-center justify-center">
<img alt="Related 4" class="w-full h-full object-contain" data-alt="A bottle of premium synthetic motorcycle engine oil with professional branding. Clean studio environment, white background, soft shadows. Industrial precision and performance maintenance aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBuODXJul5v7vp8ja2wtG_DYR20_FCrq0wUkuupN3rGiD0ataGilEvT-dI-M8b6UGsPrq7uQ29NZ9jiXzfP4NyO6cAIvSbeSaCcxqHndp-laJXj-eUwSwKrmNX03BzAEwPIs7QQwtMT8hVk5nUN_NmivRno8_vSu-Wm8TEmP31SSnep41wV87X6UrBc3hacCjtoGXaJfASPfBkoBj2sOT9VT3ZmgpNeOJ39QmZ3onX_dFr3L0oR1cdcYmm3aq4KZu2DhSePZtBFV30"/>
</div>
<div class="p-5">
<h4 class="font-label-md text-label-md text-white group-hover:text-primary-container transition-colors">Oli SPX2 Full Synthetic</h4>
<div class="font-headline-md text-headline-md text-primary-container mt-2">Rp 85.000</div>
<button class="w-full mt-4 border border-white/10 py-2 rounded font-label-md text-[12px] uppercase tracking-widest text-on-surface-variant hover:bg-white/5 transition-all">Lihat Detail</button>
</div>
</div>
<!-- Product Card 5 -->
<div class="min-w-[280px] bg-surface-card rounded-xl overflow-hidden border border-white/5 group transition-all hover:-translate-y-2 snap-start">
<div class="relative bg-white aspect-square p-6 flex items-center justify-center">
<img alt="Related 5" class="w-full h-full object-contain" data-alt="Heavy duty gold motorcycle drive chain laid in a circular pattern on a white studio background. Sharp focus on the precision links and oily metallic sheen. Technical industrial quality for racing and performance bikes." src="https://lh3.googleusercontent.com/aida-public/AB6AXuD9SMPGHutrTn_Dtt7pESEC0Y11giPvKTV8xR4WC8ddxSmPOMN72auGM6Uy2AaQf2iS9fDAtE2X0qlkT9mBoeWn4d28ZTcCZFDXI9IiUJ8tUTl4kezalNguOf05-SD6uE3UNvK1_KNGGsVH6S7ZAJJPzDtlW_p5mIsB5uMKcabmn2JBkMnoK5lYSfl32yHRS0U8tMUPKFkbYc_TAIBJoZ0KbozBapDyIizYWnIFiO04wShHQjgywiIaoO1OyJI1hno6hNeW7k8OgiI"/>
</div>
<div class="p-5">
<h4 class="font-label-md text-label-md text-white group-hover:text-primary-container transition-colors">Rantai Set Gold Racing</h4>
<div class="font-headline-md text-headline-md text-primary-container mt-2">Rp 580.000</div>
<button class="w-full mt-4 border border-white/10 py-2 rounded font-label-md text-[12px] uppercase tracking-widest text-on-surface-variant hover:bg-white/5 transition-all">Lihat Detail</button>
</div>
</div>
</div>
</section>
</main>
<!-- Success Toast (State Mockup) -->
<div class="fixed bottom-10 right-10 z-[100] flex items-center gap-4 bg-[#16213E] border-2 border-[#38A169] p-4 rounded-xl shadow-[0_0_30px_rgba(56,161,105,0.2)] max-w-sm animate-bounce">
<div class="bg-[#38A169] text-white p-2 rounded-lg">
<span class="material-symbols-outlined">check_circle</span>
</div>
<div>
<h5 class="text-white font-bold text-[14px]">Berhasil Ditambahkan!</h5>
<p class="text-on-surface-variant text-[12px]">Item telah masuk ke keranjang belanja Anda.</p>
</div>
<button class="text-on-surface-variant hover:text-white ml-4">
<span class="material-symbols-outlined text-[18px]">close</span>
</button>
</div>
<!-- Footer -->
<footer class="bg-surface-container-lowest dark:bg-surface-container-lowest border-t border-white/5">
<div class="grid grid-cols-1 md:grid-cols-4 gap-gutter max-w-[1440px] mx-auto px-8 py-16">
<div class="flex flex-col gap-4">
<div class="font-headline-md text-headline-md text-primary-container">FITS MOTOR</div>
<p class="font-body-md text-body-md text-on-surface-variant">Premium Motorcycle Workshop &amp; Spareparts. Presisi untuk performa maksimal kendaraan Anda.</p>
</div>
<div class="flex flex-col gap-4">
<h4 class="font-headline-md text-[20px] text-white">Layanan</h4>
<ul class="flex flex-col gap-2 font-label-md text-label-md text-on-surface-variant">
<li class="hover:text-secondary cursor-pointer">Layanan Servis</li>
<li class="hover:text-secondary cursor-pointer">Suku Cadang</li>
<li class="hover:text-secondary cursor-pointer">Garansi</li>
</ul>
</div>
<div class="flex flex-col gap-4">
<h4 class="font-headline-md text-[20px] text-white">Perusahaan</h4>
<ul class="flex flex-col gap-2 font-label-md text-label-md text-on-surface-variant">
<li class="hover:text-secondary cursor-pointer">Karir</li>
<li class="hover:text-secondary cursor-pointer">Kebijakan Privasi</li>
<li class="hover:text-secondary cursor-pointer">Syarat &amp; Ketentuan</li>
</ul>
</div>
<div class="flex flex-col gap-4">
<h4 class="font-headline-md text-[20px] text-white">Alamat</h4>
<p class="font-body-md text-body-md text-on-surface-variant">Jl. Racing No. 42, Jakarta Selatan, Indonesia 12150</p>
<div class="flex gap-4 mt-2">
<span class="material-symbols-outlined text-secondary cursor-pointer">social_leaderboard</span>
<span class="material-symbols-outlined text-secondary cursor-pointer">public</span>
<span class="material-symbols-outlined text-secondary cursor-pointer">alternate_email</span>
</div>
</div>
</div>
<div class="max-w-[1440px] mx-auto px-8 py-8 border-t border-white/5 flex justify-between items-center text-on-surface-variant font-label-md text-[12px]">
<span>© 2024 FITS MOTOR. Premium Motorcycle Workshop.</span>
<div class="flex gap-6">
<span>VISA</span>
<span>MASTERCARD</span>
<span>GOPAY</span>
<span>OVO</span>
</div>
</div>
</footer>
</body></html>