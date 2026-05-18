<!DOCTYPE html>

<html class="dark" lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>FITS MOTOR - Login &amp; Register</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&amp;family=Bebas+Neue&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .industrial-pattern {
            background-color: #111125;
            background-image: radial-gradient(#1e1e32 1px, transparent 1px);
            background-size: 32px 32px;
        }
        .glass-card {
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
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
                        "surface-dim": "#111125",
                        "secondary-fixed-dim": "#f1c014",
                        "surface-container-high": "#28283d",
                        "on-background": "#e2e0fc",
                        "on-primary": "#601400",
                        "on-tertiary-fixed": "#002110",
                        "secondary-container": "#e8b800",
                        "on-surface-variant": "#e7bdb2",
                        "on-tertiary-container": "#00311a",
                        "on-secondary-container": "#5f4a00",
                        "admin-sidebar": "#0F1724",
                        "surface-variant": "#333348",
                        "tertiary": "#74db9d",
                        "on-tertiary-fixed-variant": "#00522f",
                        "on-error-container": "#ffdad6",
                        "background": "#111125",
                        "surface-card": "#16213E",
                        "on-tertiary": "#00391f",
                        "tertiary-container": "#3aa36b",
                        "text-secondary": "#A0AEC0",
                        "surface-container-low": "#1a1a2e",
                        "secondary": "#ffd660",
                        "danger-red": "#E53E3E",
                        "primary-fixed": "#ffdbd1",
                        "outline-variant": "#5d4038",
                        "text-primary": "#FFFFFF",
                        "inverse-primary": "#b12d00",
                        "tertiary-fixed-dim": "#74db9d",
                        "surface-container-lowest": "#0c0c1f",
                        "tertiary-fixed": "#91f8b8",
                        "inverse-on-surface": "#2f2e43",
                        "inverse-surface": "#e2e0fc",
                        "surface-container": "#1e1e32",
                        "surface-accent": "#0F3460",
                        "on-secondary-fixed": "#241a00",
                        "primary-fixed-dim": "#ffb5a0",
                        "primary-container": "#ff5625",
                        "primary": "#ffb5a0",
                        "surface-container-highest": "#333348",
                        "surface": "#111125",
                        "on-surface": "#e2e0fc",
                        "on-secondary": "#3d2f00",
                        "surface-tint": "#ffb5a0",
                        "surface-bright": "#37374d",
                        "on-primary-container": "#541100",
                        "on-primary-fixed": "#3b0900",
                        "outline": "#ad887e",
                        "on-secondary-fixed-variant": "#584400",
                        "error-container": "#93000a",
                        "on-primary-fixed-variant": "#872000",
                        "error": "#ffb4ab",
                        "on-error": "#690005",
                        "secondary-fixed": "#ffe08e"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "fontFamily": {
                        "title-lg": ["DM Sans"],
                        "headline-lg": ["Bebas Neue"],
                        "data-mono": ["JetBrains Mono"],
                        "body-md": ["DM Sans"],
                        "body-lg": ["DM Sans"],
                        "display-hero": ["Bebas Neue"],
                        "label-md": ["DM Sans"],
                        "headline-md": ["Bebas Neue"]
                    },
                    "fontSize": {
                        "title-lg": ["20px", {"lineHeight": "140%", "fontWeight": "700"}],
                        "headline-lg": ["48px", {"lineHeight": "120%", "letterSpacing": "0.05em"}],
                        "body-md": ["16px", {"lineHeight": "160%"}],
                        "body-lg": ["18px", {"lineHeight": "160%"}],
                        "headline-md": ["32px", {"lineHeight": "120%", "letterSpacing": "0.05em"}]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background text-on-background font-body-md industrial-pattern min-h-screen py-12 px-margin-mobile">
<!-- FRAME 1 & 2 CONTAINER: LOGIN PAGE -->
<section class="max-w-[1440px] mx-auto flex flex-col items-center justify-center min-h-[819px] gap-stack-lg">
<div class="w-full max-w-[440px] bg-surface-card rounded-xl p-8 shadow-2xl glass-card relative overflow-hidden">
<!-- Decorative accent line -->
<div class="absolute top-0 left-0 w-full h-1 bg-primary-container"></div>
<div class="text-center mb-8">
<span class="font-headline-md text-headline-md text-primary tracking-wider block mb-4">FITS MOTOR</span>
<h1 class="font-headline-md text-headline-md text-text-primary uppercase">MASUK KE AKUN</h1>
<p class="font-body-md text-body-md text-on-surface-variant">Akses riwayat pesanan &amp; booking kamu</p>
</div>
<!-- FRAME 2: LOGIN ERROR STATE (Toggleable visual representation) -->
<div class="mb-6 bg-error-container/20 border border-error p-4 rounded flex items-center gap-3">
<span class="material-symbols-outlined text-error">warning</span>
<p class="text-error font-label-md">Email atau password salah. Coba lagi.</p>
</div>
<form class="space-y-6">
<div class="space-y-2">
<label class="block font-label-md text-on-surface">Email *</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">mail</span>
<input class="w-full bg-surface-container-lowest border-danger-red border-2 rounded p-3 pl-10 focus:ring-0 focus:border-primary-container text-text-primary outline-none transition-all" placeholder="nama@email.com" type="email"/>
</div>
</div>
<div class="space-y-2">
<label class="block font-label-md text-on-surface">Password *</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">lock</span>
<input class="w-full bg-surface-container-lowest border-danger-red border-2 rounded p-3 pl-10 pr-10 focus:ring-0 focus:border-primary-container text-text-primary outline-none transition-all" placeholder="••••••••" type="password"/>
<span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant cursor-pointer">visibility</span>
</div>
</div>
<div class="flex justify-between items-center text-label-md">
<label class="flex items-center gap-2 cursor-pointer">
<input class="w-4 h-4 rounded border-outline bg-surface-container-lowest text-primary-container focus:ring-primary-container" type="checkbox"/>
<span class="text-on-surface-variant">Ingat saya</span>
</label>
<a class="text-primary-container hover:underline" href="#">Lupa Password?</a>
</div>
<button class="w-full bg-primary-container text-on-primary-container font-headline-md text-title-lg py-4 rounded-lg hover:brightness-110 active:scale-95 transition-all uppercase tracking-widest shadow-lg shadow-primary-container/20" type="submit">
                    MASUK
                </button>
</form>
<div class="flex items-center gap-4 my-8">
<div class="h-[1px] flex-1 bg-white/10"></div>
<span class="text-on-surface-variant font-label-md">atau</span>
<div class="h-[1px] flex-1 bg-white/10"></div>
</div>
<button class="w-full border-2 border-outline text-text-primary font-label-md py-4 rounded-lg flex items-center justify-center gap-2 hover:bg-white/5 transition-colors group">
<span class="material-symbols-outlined group-hover:text-tertiary">chat</span>
                Masuk dengan WhatsApp OTP
            </button>
<p class="text-center mt-8 font-body-md text-on-surface-variant">
                Belum punya akun? <a class="text-primary-container font-bold hover:underline" href="#">Daftar Sekarang →</a>
</p>
</div>
</section>
<!-- FRAME 3 & 4 CONTAINER: REGISTER PAGE -->
<section class="max-w-[1440px] mx-auto flex flex-col items-center justify-center min-h-screen py-24 gap-stack-lg">
<div class="w-full max-w-[440px] bg-surface-card rounded-xl p-8 shadow-2xl glass-card relative overflow-hidden">
<div class="absolute top-0 left-0 w-full h-1 bg-primary-container"></div>
<div class="text-center mb-8">
<h1 class="font-headline-md text-headline-md text-text-primary uppercase">BUAT AKUN BARU</h1>
<p class="font-body-md text-body-md text-on-surface-variant">Daftar gratis, dapatkan notifikasi pesanan &amp; booking</p>
</div>
<form class="space-y-4">
<div class="space-y-2">
<label class="block font-label-md text-on-surface">Nama Lengkap *</label>
<input class="w-full bg-surface-container-lowest border border-outline rounded p-3 focus:ring-0 focus:border-primary-container text-text-primary outline-none" placeholder="Masukkan nama lengkap" type="text"/>
</div>
<div class="space-y-2">
<label class="block font-label-md text-on-surface">Email *</label>
<input class="w-full bg-surface-container-lowest border border-danger-red rounded p-3 focus:ring-0 focus:border-danger-red text-text-primary outline-none" placeholder="nama@email.com" type="email"/>
<!-- FRAME 4: VALIDATION STATE -->
<p class="text-danger-red text-xs font-label-md">Email sudah terdaftar</p>
</div>
<div class="space-y-2">
<label class="block font-label-md text-on-surface">Nomor HP/WA *</label>
<div class="flex gap-2">
<div class="bg-surface-container-lowest border border-outline rounded p-3 text-on-surface-variant font-label-md flex items-center">+62</div>
<input class="flex-1 bg-surface-container-lowest border border-outline rounded p-3 focus:ring-0 focus:border-primary-container text-text-primary outline-none" placeholder="8123456789" type="tel"/>
</div>
</div>
<div class="space-y-2">
<label class="block font-label-md text-on-surface">Password *</label>
<input class="w-full bg-surface-container-lowest border border-outline rounded p-3 focus:ring-0 focus:border-primary-container text-text-primary outline-none" placeholder="••••••••" type="password"/>
<!-- Password Strength Bar -->
<div class="space-y-1">
<div class="flex gap-1 h-1.5 w-full">
<div class="flex-1 bg-tertiary rounded-full"></div>
<div class="flex-1 bg-tertiary rounded-full"></div>
<div class="flex-1 bg-tertiary rounded-full"></div>
</div>
<p class="text-tertiary text-xs font-label-md">Password kuat ✓</p>
</div>
</div>
<div class="space-y-2">
<label class="block font-label-md text-on-surface">Konfirmasi Password *</label>
<input class="w-full bg-surface-container-lowest border border-danger-red rounded p-3 focus:ring-0 focus:border-danger-red text-text-primary outline-none" placeholder="••••••••" type="password"/>
<p class="text-danger-red text-xs font-label-md">Password tidak cocok</p>
</div>
<div class="py-2">
<label class="flex items-start gap-3 cursor-pointer">
<input class="mt-1 w-4 h-4 rounded border-outline bg-surface-container-lowest text-primary-container focus:ring-primary-container" required="" type="checkbox"/>
<span class="text-on-surface-variant text-label-md">Saya setuju dengan <a class="text-primary-container underline" href="#">Syarat &amp; Ketentuan</a> layanan FITS MOTOR.</span>
</label>
</div>
<button class="w-full bg-primary-container text-on-primary-container font-headline-md text-title-lg py-4 rounded-lg hover:brightness-110 transition-all uppercase tracking-widest mt-4" type="submit">
                    DAFTAR SEKARANG
                </button>
</form>
<p class="text-center mt-8 font-body-md text-on-surface-variant">
                Sudah punya akun? <a class="text-primary-container font-bold hover:underline" href="#">Masuk →</a>
</p>
</div>
</section>
<!-- FRAME 5 CONTAINER: LUPA PASSWORD -->
<section class="max-w-[1440px] mx-auto flex flex-col items-center justify-center min-h-[614px] gap-stack-lg">
<!-- Input State -->
<div class="w-full max-w-[400px] bg-surface-card rounded-xl p-8 shadow-2xl glass-card border border-white/5">
<h2 class="font-headline-md text-headline-md text-text-primary mb-2 uppercase">LUPA PASSWORD</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-6">Masukkan email kamu untuk menerima link reset password.</p>
<form class="space-y-4">
<input class="w-full bg-surface-container-lowest border border-outline rounded p-3 focus:ring-0 focus:border-primary-container text-text-primary outline-none" placeholder="nama@email.com" type="email"/>
<button class="w-full bg-primary-container text-on-primary-container font-headline-md text-title-lg py-4 rounded-lg hover:brightness-110 transition-all uppercase" type="submit">
                    KIRIM LINK RESET
                </button>
</form>
</div>
<!-- Success State -->
<div class="w-full max-w-[400px] bg-surface-container-high rounded-xl p-8 shadow-2xl border border-tertiary/20 flex flex-col items-center text-center">
<div class="w-16 h-16 bg-tertiary/10 rounded-full flex items-center justify-center mb-6">
<span class="material-symbols-outlined text-tertiary text-4xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
</div>
<h3 class="text-text-primary font-title-lg mb-2">Link Terkirim!</h3>
<p class="font-body-md text-tertiary">✅ Link reset dikirim ke email kamu. Silakan periksa kotak masuk atau spam.</p>
<button class="mt-6 text-on-surface-variant font-label-md hover:text-text-primary transition-colors">
                Kembali ke Login
            </button>
</div>
</section>
<!-- Footer Decoration Area -->
<footer class="w-full max-w-[1440px] mx-auto mt-24 px-8 py-12 border-t border-white/5 opacity-50">
<div class="flex flex-col md:flex-row justify-between items-center gap-6">
<span class="font-headline-md text-headline-md text-primary tracking-widest">FITS MOTOR</span>
<p class="font-body-md text-on-surface-variant text-sm">© 2024 FITS MOTOR. Premium Motorcycle Workshop. Precision in every gear.</p>
</div>
</footer>
</body></html>