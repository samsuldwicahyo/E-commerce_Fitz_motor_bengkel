# 🎨 PROMPT STITCH AI — UI/UX FITS MOTOR WEBSITE (LENGKAP & DETAIL)
## PHP Native | Dua Role: Admin & Customer
## Gunakan prompt ini SATU PER SATU di Google Stitch

---

## ⚙️ CARA PAKAI
1. Buka **https://stitch.withgoogle.com**
2. Klik **"New Project"**
3. Copy-paste prompt satu per satu sesuai halaman
4. Setiap prompt = satu halaman penuh dengan SEMUA FRAME (desktop + mobile + state)
5. Export: **HTML/CSS** (karena project pakai PHP Native)

---

## 🔑 SYSTEM CONTEXT (Tempel di awal SETIAP prompt)

```
[SYSTEM CONTEXT — FITS MOTOR]
Brand: FITS MOTOR — bengkel motor premium Indonesia
Stack: PHP Native (bukan React/Next.js) — export sebagai HTML/CSS murni
Color Palette:
  - Background utama: #1A1A2E (deep navy black)
  - Surface/card: #16213E
  - Surface aksen: #0F3460
  - Primary CTA: #FF4500 (orange-red)
  - Gold highlight: #E8B800
  - Teks utama: #FFFFFF
  - Teks sekunder: #A0AEC0
  - Sukses/WA: #38A169
  - Bahaya: #E53E3E
Fonts:
  - Heading/display: Bebas Neue atau Barlow Condensed (bold, condensed)
  - Body: DM Sans atau Nunito (clean, readable)
Style: Dark industrial, sporty, premium — seperti garasi balap mewah
Bahasa: Bahasa Indonesia
Mata uang: Rupiah (format: Rp 150.000)
Role pengguna: Admin dan Customer (dua role terpisah)
```

---

## 📌 PROMPT 1 — LANDING PAGE / BERANDA (FULL FRAMES)

```
[SYSTEM CONTEXT — FITS MOTOR — paste dari atas]

Design a complete dark-themed landing page for "FITS MOTOR" with ALL of the following frames:

FRAME 1 — STICKY NAVBAR (desktop, 1440px):
- Left: FITS MOTOR logo (bold Bebas Neue, flame/gear icon, orange color)
- Center links: Beranda | Produk | Booking | Tentang | Kontak (white, active = orange underline)
- Right group: 
  • 🔍 Search icon
  • 🛒 Cart icon with orange badge (count "3")
  • [Masuk] button (outlined white, small, for customer login)
  • [Konsultasi WA] button (green #38A169, rounded, WhatsApp icon)
- Navbar background: dark #1A1A2E with subtle blur
- Show logged-in state variant: avatar + "Halo, Budi ▼" dropdown replacing [Masuk]

FRAME 2 — NAVBAR (mobile, 375px):
- Left: FITS MOTOR logo (smaller)
- Right: Cart icon + Hamburger ☰ icon
- Show drawer menu open state (slide-in from right):
  Dark panel full height, menu links stacked vertically
  Bottom of drawer: [Masuk / Daftar] button + WA button

FRAME 3 — HERO SECTION (desktop):
Full viewport height, dark dramatic background with motorcycle silhouette
- Large headline: "PERFORMA MOTOR, DITANGAN AHLINYA" (Bebas Neue, 96px, white, letter-spaced)
- Sub-headline: "Servis • Modifikasi • Aksesori Premium" (DM Sans, gold #E8B800)
- Two CTA buttons side by side:
  → [BELANJA SEKARANG] filled orange-red, large
  → [BOOKING SERVIS] outlined white, large
- Bottom-left animated badge: "⭐ 4.9 Rating | 2.000+ Pelanggan Puas" (pill, dark card, gold border)
- Decorative: subtle spark/flame particle effects

FRAME 4 — HERO SECTION (mobile, 375px):
- Headline 48px, buttons stacked full-width, reduced padding

FRAME 5 — SERVICES STRIP (4 cards, horizontal):
Dark strip below hero, 4 icon cards:
🔧 Servis & Tune Up | 🎨 Modifikasi Custom | 🛒 Aksesori Premium | ⚡ Part Original
Each card: icon (orange), title (white bold), short desc (grey text)
Hover state: orange border appears, card lifts slightly

FRAME 6 — SERVICES STRIP (mobile, 2x2 grid):

FRAME 7 — FEATURED PRODUCTS SECTION:
Section title: "PRODUK UNGGULAN" (Bebas Neue, large, white) + "Lihat Semua →" link (orange)
Grid: 3 columns × 2 rows = 6 product cards
Each product card (#16213E):
- Top-left badge: "TERLARIS" (red) | "PROMO" (gold) | "BARU" (green)
- Product image (white bg square, 250×250)
- Product name (2 lines, white, DM Sans bold)
- Compatibility tag: "Honda Beat / Vario" (small, grey pill)
- Pricing row: ~~Rp 285.000~~ Rp 228.000 (strikethrough grey + orange bold)
- Star rating: ★★★★☆ 4.8 | (245 terjual)
- Buttons: [🛒 Keranjang] outlined orange + [Beli →] filled orange
- Hover: card border glows orange

FRAME 8 — FEATURED PRODUCTS (mobile, 2 column grid + horizontal scroll):

FRAME 9 — STATS BAR:
Full-width dark strip (#0F3460), 4 animated counters centered:
2.000+ | 500+ | 8 Tahun | 98%
Pelanggan Puas | Produk Tersedia | Pengalaman | Kepuasan Customer
Number in orange/gold, label in white below

FRAME 10 — TESTIMONIALS SECTION:
Title: "APA KATA PELANGGAN KAMI"
3 cards side by side (dark card, subtle gold border):
Each: avatar circle (photo), customer name (bold white), motor type (orange small), 
star rating (gold), review text (grey italic), date
Card background #16213E

FRAME 11 — TESTIMONIALS (mobile, vertical stack / swipe carousel):

FRAME 12 — GALLERY STRIP (Modifikasi):
Title: "GALERI MODIFIKASI"
Masonry/grid layout, 6 photos of modified motorcycles
Hover overlay on each: dark semi-transparent + "Lihat Detail →" in white

FRAME 13 — BRAND PARTNERS:
Title: "BRAND YANG KAMI JUAL" (centered)
Logo row: AHM | Yamalube | Shell | Federal | FDR | IRC | NGK | Bosh
Logos in greyscale, color on hover

FRAME 14 — FLOATING ELEMENTS:
- WhatsApp FAB: fixed bottom-right, green circle (56px), WhatsApp icon, pulse animation ring
- Hover/expanded state: pill expands to show "💬 Konsultasi Gratis"

FRAME 15 — FOOTER:
4-column dark footer:
Column 1: FITS MOTOR logo + short desc + sosmed icons (Instagram, TikTok, Facebook, YouTube)
Column 2: Navigasi (Beranda, Produk, Booking, Tentang, Kontak)
Column 3: Layanan (Servis, Modifikasi, Aksesori, Part Original)
Column 4: Kontak (📍 Alamat, 📞 Telepon, ⏰ Jam Buka, ✉️ Email)
Bottom bar: copyright © 2025 FITS MOTOR | small divider
```

---

## 📌 PROMPT 2 — HALAMAN KATALOG PRODUK (FULL FRAMES)

```
[SYSTEM CONTEXT — FITS MOTOR]

Design a complete dark-themed product catalog page with ALL frames:

FRAME 1 — CATALOG PAGE (desktop, 1440px, full layout):
Two-column layout: left sidebar (280px fixed) + right product grid (flex)

LEFT SIDEBAR:
Title: "FILTER PRODUK" (Bebas Neue, orange underline)
Sections with collapse arrow:
▼ Kategori:
  □ Oli & Pelumas (42)
  □ Ban & Velg (28)
  □ Body Kit & Fairing (35)
  □ Aksesori (67)
  □ Knalpot (19)
  □ Kelistrikan (23)
  □ Part Mesin (44)
▼ Brand:
  Logo chip pills: AHM | Yamalube | Shell | Federal | FDR | IRC
  (selected chip = orange bg)
▼ Harga:
  Range slider Rp 10.000 — Rp 5.000.000 (orange dual handle)
  Show current range text below slider
▼ Rating:
  ★★★★★ (5 bintang ke atas)
  ★★★★☆ (4 bintang ke atas)
[TERAPKAN FILTER] button (orange full width)
[Reset semua] link (small, grey, right-aligned)

RIGHT CONTENT AREA:
Top bar:
- Left: "Menampilkan 124 produk"
- Right: [Urutkan: Terpopuler ▼] dropdown + Grid/List icon toggle
Search bar: full width, dark bg, orange border on focus, 🔍 icon left

Product grid: 3 columns
12 product cards per page (see card design in Frame 3)

FRAME 2 — FILTER SIDEBAR MOBILE (bottom sheet / drawer, 375px):
Slide-up panel from bottom covering 80% height
Same filter options but mobile-optimized (larger tap targets)
[TERAPKAN] button sticked at bottom of sheet

FRAME 3 — PRODUCT CARD (detailed, hover/default/mobile states):
Default state (dark card #16213E):
- Corner badge top-left: "TERLARIS" red | "PROMO" gold | "BARU" green
- Product image: 1:1 ratio, white bg
- Wishlist ♡ icon top-right (grey, filled orange when favorited)
- Product name: 2 lines max, white, DM Sans SemiBold
- Compatibility tag: "Honda Beat / Vario / PCX" (grey small chip)
- Original price strikethrough + discount price (orange bold, larger)
- Rating: ★★★★☆ 4.8 + "(245 terjual)"
- Buttons row: [🛒 Keranjang] outline + [Beli →] filled

Hover state: orange border glow, subtle scale up 1.02

Out of stock state: image overlay "STOK HABIS" + buttons disabled grey

FRAME 4 — CATALOG PAGE (mobile, 375px):
- Search bar full width at top
- [Filter ▼] + [Urutkan ▼] buttons below search
- Product grid: 2 columns
- Smaller card padding, price visible, no compatibility tag

FRAME 5 — EMPTY STATE (no products found):
Centered illustration (empty box icon)
"Tidak ada produk yang sesuai filter kamu"
[Reset Filter] orange button

FRAME 6 — PAGINATION:
Bottom center: < 1 2 3 4 5 ... 12 >
Active page: orange filled circle
Hover: orange text
"Halaman 1 dari 12" text below

FRAME 7 — LIST VIEW (alternate layout):
Horizontal cards: image left (100px) | info right
Show more detail: full description 1 line, weight, full specs
```

---

## 📌 PROMPT 3 — HALAMAN DETAIL PRODUK (FULL FRAMES)

```
[SYSTEM CONTEXT — FITS MOTOR]

Design the complete product detail page with ALL frames and states:

FRAME 1 — PRODUCT DETAIL PAGE (desktop, 1440px):
Breadcrumb: Beranda > Produk > Aksesori > Lampu LED Projector

TWO-COLUMN LAYOUT:

LEFT — IMAGE GALLERY:
- Large main image (white bg card, shadow, centered product)
- Badge top-left: "DISKON 20%" (red pill)
- Badge top-right: "STOK: 48 unit" (green small)
- Row of 4 thumbnail images below (active = orange 2px border)
- Zoom icon on main image hover
- [< >] arrow navigation on mobile

RIGHT — PRODUCT INFO:
- Brand tag: "HONDA GENUINE PARTS" (gold small caps, letter-spaced)
- Product name: "Lampu LED Projector Honda Beat 2020-2024" (Bebas Neue 36px white)
- Rating row: ★★★★☆ 4.8 | (342 ulasan) | 1.2k terjual | [Share 🔗]
- Price section (dark card bg):
  ~~Rp 285.000~~ (grey strikethrough, small)
  Rp 228.000 (orange, Bebas Neue 42px, bold)
  Savings badge: "Hemat Rp 57.000" (green pill)
- Horizontal divider
- COMPATIBILITY SELECTOR:
  Label: "Pilih Tipe Motor:" (white small)
  Pill buttons: [Honda Beat] [Honda Vario] [Honda PCX] [Honda CBR]
  Selected = orange filled, others = outlined white
- QUANTITY SELECTOR:
  [−] [2] [+] (dark bg, orange border)
  "Stok: 48 unit tersedia" (green small)
- ACTION BUTTONS (full width, stacked with gap):
  [🛒 TAMBAH KE KERANJANG] outlined orange, large
  [⚡ BELI SEKARANG] filled orange, large
  [💬 Tanya via WhatsApp] green #38A169, WhatsApp icon
- TRUST BADGES ROW:
  🛡️ Garansi Resmi | 🚚 Free Ongkir >300rb | ↩️ Retur 7 Hari
  (small icons, grey text, horizontal row)

FRAME 2 — PRODUCT DETAIL (mobile, 375px):
- Single column layout
- Image gallery: main image full width, thumbnails row below
- All info stacked vertically
- Sticky bottom bar: price left + [Beli Sekarang] right

FRAME 3 — TABS SECTION (below product info, full width):
Tab navigation: [Deskripsi] [Spesifikasi] [Cara Pemasangan] [Ulasan (342)]
Active tab: orange bottom border + white text
Inactive: grey text

DESKRIPSI tab content:
- 2-3 paragraph text in DM Sans, grey-white
- Bullet points of key features

SPESIFIKASI tab content:
- Table with alternating dark row colors
- Left: spec name (grey) | Right: value (white)
- E.g. Merek | Honda | Tipe | LED Projector | Voltase | 12V

CARA PEMASANGAN tab content:
- Numbered step list with icons
- Video thumbnail embed placeholder

FRAME 4 — ULASAN (REVIEWS) TAB:
Overall rating section:
- Big "4.8" number (gold, 64px)
- Star display
- Breakdown bars: 5★ ████████░ 68%, 4★ ████░░░░ 24%, 3★ █░░░░░ 8%

3 review cards:
- Avatar circle | Name (white) | Date (grey) | Motor: Honda Beat (orange small)
- Star rating row
- Review text (DM Sans, grey-white)
- Photo thumbnail (if any)
- [Helpful 👍 12] interaction

[Tulis Ulasan] button (orange outline, only for logged-in customer who bought)
Filter row: [Semua] [Bintang 5] [Bintang 4] [Ada Foto]

FRAME 5 — RELATED PRODUCTS:
Section: "PRODUK TERKAIT"
Horizontal scroll of 5 product cards (same card design as catalog)
Left/right scroll arrows (desktop)

FRAME 6 — ADD TO CART SUCCESS STATE:
Slide-in toast notification top-right:
"✅ Produk berhasil ditambahkan ke keranjang" + [Lihat Keranjang →] link
```

---

## 📌 PROMPT 4 — HALAMAN KERANJANG & CHECKOUT (FULL FRAMES)

```
[SYSTEM CONTEXT — FITS MOTOR]

Design the complete cart and checkout experience with ALL frames:

FRAME 1 — CART PAGE (desktop, 1440px):
Two-column layout: left (items) + right (summary, sticky)

LEFT — CART ITEMS:
Title: "KERANJANG BELANJA 🛒" (Bebas Neue, white)
Subtitle: "3 produk"

Item rows (dark card each):
[Product image 80px] | [Name + variant] | [Price] | [Qty: − 2 +] | [Subtotal] | [🗑️]
- Product image: small square, white bg
- Name: white bold | Variant: "Honda Beat" grey small
- Price per item: grey
- Qty control: [−] [2] [+] dark buttons, orange border
- Subtotal: orange bold
- Trash icon: red hover

Bottom of items: [🗑️ Hapus yang dipilih] | [🔄 Update keranjang] (small links)

RIGHT — CART SUMMARY (sticky card):
Title: "Ringkasan"
- Subtotal: Rp 456.000
- Ongkos kirim: Belum dihitung
- Diskon: -Rp 0
---
- TOTAL: Rp 456.000 (large orange)
[LANJUT KE CHECKOUT →] full width orange button (large)
[← Lanjut Belanja] small link below
Voucher input: [Kode voucher] [PAKAI] button

FRAME 2 — CART EMPTY STATE:
Centered: empty cart illustration + "Keranjang kamu masih kosong"
[Mulai Belanja →] orange button

FRAME 3 — CART (mobile, 375px):
- Single column: items stacked, summary at bottom
- Each item: image 60px | info | qty | subtotal in compact row
- [Checkout] sticky bottom bar

FRAME 4 — CHECKOUT STEP 1 — ALAMAT PENGIRIMAN (desktop):
Progress bar top: [1 Alamat ✓] → [2 Pengiriman] → [3 Pembayaran]
Active: orange circle, completed: green checkmark, upcoming: grey

Form card (dark, orange top border):
Header: "📍 Alamat Pengiriman"

If logged in: show saved addresses as radio cards
[○ Rumah — Jl. Merdeka No.5, Banjarnegara] (default address with "UTAMA" badge)
[○ Kantor — Jl. Sudirman No.10...]
[+ Tambah Alamat Baru] link (orange)

If new address form opened / guest:
Fields (dark bg inputs, orange focus border):
- Nama Penerima * [text input]
- Nomor HP/WA * [+62 prefix flag | number input]
- Provinsi * [dropdown with search, e.g. "Jawa Tengah"]
- Kota/Kabupaten * [loads after province, e.g. "Banjarnegara"]
- Kecamatan * [text input]
- Alamat Lengkap * [textarea, 3 rows]
- Kode Pos [text input, 5 digit]
- ☐ Simpan sebagai alamat utama

[LANJUT →] button (orange, full width)

Right: sticky order summary same as cart

FRAME 5 — CHECKOUT STEP 2 — PILIH KURIR (desktop):
Form card: "🚚 Pilih Pengiriman"

Courier selector tabs (horizontal pills):
[JNE] [SiCepat] [J&T] [Anteraja] [POS]
Active courier: orange bg pill

After selecting courier, service options as radio cards:
┌────────────────────────────────────────────┐
│ ○  JNE REG     Estimasi 3-4 hari   Rp 18.000  │
│ ○  JNE YES     Estimasi 1-2 hari   Rp 35.000  │  
│ ○  JNE OKE     Estimasi 5-7 hari   Rp 12.000  │
└────────────────────────────────────────────┘
Selected row: orange left border + light orange bg (#FF4500/10)
Show loading skeleton while fetching rates

FRAME 6 — CHECKOUT STEP 3 — PEMBAYARAN (desktop):
Form card: "💳 Metode Pembayaran"

Grouped accordion sections:
▼ Transfer Bank (Virtual Account)
  Logo grid 3-column: [BRI VA] [BCA VA] [BNI VA] [Mandiri VA] [BSI VA]
  Each: bank logo + "Virtual Account" label, dark card
  Selected: orange 2px border + orange checkmark top-right

▼ QRIS
  [🔲 QRIS — Semua e-wallet & m-banking]

▼ Gerai Retail
  [Alfamart] [Indomaret]

Summary update with selected payment shown
[LANJUT BAYAR →] large orange button

FRAME 7 — PAYMENT INSTRUCTION PAGE:
Centered card (max 560px)

Payment pending state:
- Clock icon (large, orange, animated)
- "SELESAIKAN PEMBAYARAN" (Bebas Neue, white)
- Countdown: "Bayar sebelum: 23:59:00" (orange, monospace, large)

Payment info card (darker bg):
- BRI logo + "Bank BRI Virtual Account"
- "Nomor Virtual Account:"
- [1234 5678 9012 3456] (large monospace white) [📋 Salin]
- "Total Bayar: Rp 474.000" (orange bold)

Payment steps (numbered):
1. 📱 Buka m-banking / ATM BRI
2. 💳 Pilih Transfer Virtual Account
3. 🔢 Masukkan nomor VA di atas
4. ✅ Konfirmasi pembayaran

Buttons:
[CEK STATUS PESANAN] orange | [💬 Hubungi Kami] green

PAID state (success):
- Green checkmark animated
- "PEMBAYARAN BERHASIL! 🎉"
- Order details summary

FRAME 8 — CHECKOUT (mobile, 375px):
Single column, each step full screen
Step indicator: compact numbered dots (not full labels)
Sticky bottom: [Total: Rp 474.000] + [Lanjut →] button
```

---

## 📌 PROMPT 5 — HALAMAN BOOKING SERVIS (FULL FRAMES)

```
[SYSTEM CONTEXT — FITS MOTOR]

Design the complete multi-step booking service page with ALL frames:

FRAME 1 — BOOKING PAGE HEADER + STEP INDICATOR (desktop):
Page hero: dark bg, title "BOOKING SERVIS" (Bebas Neue 72px white)
Subtitle: "Jadwalkan kunjungan motor kamu, kami siapkan yang terbaik 🏍️"

Horizontal step progress (4 steps):
① Data Kamu → ② Data Motor → ③ Pilih Layanan → ④ Pilih Jadwal
Circles connected by horizontal line
- Active: orange filled circle + orange label
- Completed: green filled + ✓ checkmark
- Upcoming: grey outlined circle + grey label

FRAME 2 — STEP 1: DATA PELANGGAN:
Dark centered card (max-width 680px), orange top border accent
Header: "Step 1 — Data Kamu"

Fields:
- Nama Lengkap * [text input]
- Nomor HP/WhatsApp * [+62 | number input]
- Email [text input, optional, grey label "(opsional)"]

If logged in: pre-fill from account + show "Data diambil dari akun kamu ✓" info note (green)

[LANJUT →] orange full width button
Below: "Sudah punya booking? [Cek Status]" small link

FRAME 3 — STEP 2: DATA MOTOR:
Same card style

- Merek Motor * [dropdown: Honda | Yamaha | Suzuki | Kawasaki | TVS | Lainnya]
- Model/Tipe * [text input, placeholder: "contoh: Beat, Vario 150, NMAX"]
- Tahun Motor * [dropdown: 2010—2024]
- Nomor Plat * [text input, placeholder: "R 1234 AB"]
- Keluhan / Kebutuhan [textarea 4 rows, placeholder: "Ceritakan kondisi motor kamu, keluhan, atau yang ingin dimodifikasi..."]

[LANJUT →] orange | [← Kembali] outline button

FRAME 4 — STEP 3: PILIH LAYANAN:
Card with grid of service selection cards (2-column grid)

Title inside card: "Pilih layanan yang kamu butuhkan (bisa lebih dari satu)"

8 service cards, each:
Icon (emoji large) + Service name (white bold) + Price (orange) + short desc (grey)
Checkbox top-right corner
Selected state: orange 2px border + subtle orange overlay bg + ✓ checkbox

Service cards:
🔧 Servis Ringan — Rp 75.000 — Tune up, cek kelistrikan, oli
⚙️ Servis Besar — Rp 150.000 — Servis menyeluruh, karburator/injektor
🛢️ Ganti Oli — Rp 35.000 + harga oli — Oli mesin + filter oli
💉 Tune Up Injeksi — Rp 120.000 — Kalibrasi injektor, throttle cleaning
🔩 Pasang Aksesori — Harga menyesuaikan — Pasang part/aksesori yang dibeli
⚖️ Balancing & Spooring — Rp 50.000 — Balancing roda, pelurusan setang
🎨 Konsultasi Modifikasi — GRATIS — Diskusi desain & estimasi biaya
🔋 Cek Kelistrikan — Rp 45.000 — Aki, lampu, starter, speedometer

Sticky bottom summary bar (inside card):
"3 layanan dipilih | Estimasi: Rp 345.000" — [LANJUT →]

FRAME 5 — STEP 4: PILIH JADWAL:
Card two-column layout:

LEFT: Mini Calendar
- Month: "Januari 2025" + [< >] navigation arrows
- 7-column day grid (Sen Sel Rab Kam Jum Sab Min)
- Date cells:
  Past dates: very dim (#3a3a4a)
  Available dates: white text, hover = orange outline
  Full/unavailable: strikethrough grey, disabled
  Today: orange outline circle
  Selected: orange filled circle, white text
  Weekends: slightly different shade (optional: closed = red tint)

RIGHT: Time Slots (shown after date selected)
Title: "Pilih Waktu — Senin, 20 Jan 2025"
4 time slot radio cards:
[○ 08.00–10.00 | Tersedia (2 slot)] (white)
[○ 10.00–12.00 | PENUH ✗] (grey, strikethrough, disabled)
[○ 13.00–15.00 | Tersedia (3 slot)] (white)
[○ 15.00–17.00 | Tersedia (1 slot)] (white, warning: "hampir penuh" orange)

Selected time slot: orange border + orange bg fill

[LIHAT KONFIRMASI →] orange button

FRAME 6 — STEP 4 (mobile, 375px):
- Calendar full-width, large date cells (min 44px tap target)
- Time slots below calendar, full-width radio cards

FRAME 7 — KONFIRMASI BOOKING (final review):
Summary card before submit:

Title: "Konfirmasi Booking" (Bebas Neue)

Summary rows (label: value pairs):
👤 Nama: Budi Santoso
📱 WhatsApp: 0812-3456-7890
🏍️ Motor: Honda Beat 2022 | R 1234 AB
📋 Layanan: Servis Ringan, Ganti Oli (+2 lainnya)
📅 Tanggal: Senin, 20 Januari 2025
⏰ Waktu: 08.00–10.00 WIB
💰 Estimasi Biaya: Rp 110.000 (belum termasuk bahan)

Note: "Estimasi bisa berbeda tergantung kondisi motor"

Two action buttons:
[✅ KONFIRMASI BOOKING] — large orange filled
[💬 Booking via WhatsApp] — green (alternative)

FRAME 8 — BOOKING SUCCESS PAGE:
Centered card (max 560px)
- Large animated checkmark (green circle, draw animation)
- "BOOKING BERHASIL! 🎉" (Bebas Neue, white, large)
- "Konfirmasi dikirim ke WhatsApp kamu"
- Booking number: #BK-20250120-XY8Z (orange, large, copyable)
- Date + time reminder
- Location card: map thumbnail + alamat bengkel + [Buka Maps 📍]
- [← Kembali ke Beranda] and [Lihat Riwayat Booking] buttons
```

---

## 📌 PROMPT 6 — HALAMAN LOGIN & REGISTER CUSTOMER (FULL FRAMES)

```
[SYSTEM CONTEXT — FITS MOTOR]

Design the authentication pages for CUSTOMER with ALL frames:

FRAME 1 — LOGIN CUSTOMER PAGE (desktop, centered card):
Background: dark #1A1A2E with subtle gear/flame decorative pattern

Centered card (max 440px, #16213E, rounded, shadow):
- Top: FITS MOTOR logo (centered, large)
- Title: "MASUK KE AKUN" (Bebas Neue, white)
- Subtitle: "Akses riwayat pesanan & booking kamu"

Form fields:
- Email * [dark input, mail icon left, orange focus border]
- Password * [dark input, lock icon left, 👁️ toggle right]
- Row: [☐ Ingat saya] left + [Lupa Password?] right (orange link)
[MASUK] full width orange button

Divider: — atau —

[💬 Masuk dengan WhatsApp OTP] (grey outline button)

Bottom: "Belum punya akun? [Daftar Sekarang →]" (orange link)

FRAME 2 — LOGIN ERROR STATE:
Red alert banner: "⚠️ Email atau password salah. Coba lagi."
Input fields highlighted with red border

FRAME 3 — REGISTER CUSTOMER PAGE (desktop):
Same centered card style
- Title: "BUAT AKUN BARU" (Bebas Neue)
- Subtitle: "Daftar gratis, dapatkan notifikasi pesanan & booking"

Fields:
- Nama Lengkap * [text input]
- Email * [email input, validated inline]
- Nomor HP/WA * [+62 | phone input]
- Password * [with strength indicator bar below: Lemah/Sedang/Kuat]
- Konfirmasi Password *
- [☐ Saya setuju dengan Syarat & Ketentuan] (required)
[DAFTAR SEKARANG] orange full width button

Bottom: "Sudah punya akun? [Masuk →]"

FRAME 4 — REGISTER VALIDATION STATE:
Real-time field validation with inline messages:
- Email taken: "Email sudah terdaftar" (red small text under field)
- Password mismatch: "Password tidak cocok" (red)
- Strong password: "Password kuat ✓" (green)

FRAME 5 — LOGIN & REGISTER (mobile, 375px):
Full screen card (no floating card, full bg)
Same fields, touch-optimized (min 48px height inputs)

FRAME 6 — LUPA PASSWORD:
Small card: Email input + [KIRIM LINK RESET] button
Success state: "✅ Link reset dikirim ke email kamu"
```

---

## 📌 PROMPT 7 — CUSTOMER DASHBOARD & AKUN (FULL FRAMES)

```
[SYSTEM CONTEXT — FITS MOTOR]

Design the complete logged-in customer area with ALL frames:

FRAME 1 — CUSTOMER DASHBOARD (desktop):
Left sidebar navigation (250px, dark #16213E):
- Customer avatar circle + "Halo, Budi! 👋" (white)
- Email grey small
- Orange divider
Menu items (active = orange left border + light bg):
  🏠 Dashboard
  🛒 Pesanan Saya
  📅 Booking Saya
  📍 Alamat
  👤 Profil
  🔑 Ubah Password
  🚪 Keluar

Right content area:
3 stat cards top row:
- Total Pesanan: 8 (blue icon)
- Booking Aktif: 1 (orange icon)
- Belanja Total: Rp 1.250.000 (green icon)

Recent orders table:
Header: No. Pesanan | Tanggal | Total | Status | Aksi
3 rows with status badges:
[MENUNGGU BAYAR] orange badge | [DIKIRIM] blue | [SELESAI] green
[Lihat Detail] link per row

Recent bookings (2 cards):
Card: booking number + date + motor + services + status badge + [Detail] link

FRAME 2 — PESANAN SAYA (order list):
Full table view of all orders with pagination
Filters: [Semua] [Menunggu Bayar] [Diproses] [Dikirim] [Selesai] [Dibatalkan]
Each row: no | date | items summary | total | status badge | [Detail] [Lacak]

FRAME 3 — DETAIL PESANAN:
Order number prominent: #FM-20250120-4521 (orange, copyable)
Status progress bar: Pesanan Dibuat → Bayar → Dikemas → Dikirim → Selesai
(Current active step highlighted orange)

Items list: product image + name + qty + price each
Shipping info: kurir + resi + [Cek Resi →]
Payment info: method + status
Address display
Total breakdown: subtotal + ongkir + diskon + total

[💬 Chat Admin via WA] green button
[✍️ Tulis Ulasan] (if SELESAI status)

FRAME 4 — BOOKING SAYA (booking list):
Cards view of all bookings:
Each card: booking no | date | motor | services list | status badge | [Detail]

FRAME 5 — EDIT PROFIL:
Form card: Nama | Email (read-only) | No HP | Avatar upload
[SIMPAN] orange button

FRAME 6 — KELOLA ALAMAT:
List of saved addresses (cards)
Each: label (Rumah) | recipient name | full address | [UTAMA] green badge or [Set Utama] link | [Edit] [Hapus]
[+ Tambah Alamat] orange button at top

FRAME 7 — CUSTOMER DASHBOARD (mobile, 375px):
- Top: avatar + greeting
- Horizontal scroll stats row
- Sections stacked (no sidebar)
- Bottom nav bar: 🏠 | 🛒 | 📅 | 👤
```

---

## 📌 PROMPT 8 — ADMIN PANEL LENGKAP (FULL FRAMES)

```
[SYSTEM CONTEXT — FITS MOTOR]

Design the complete Admin Panel with ALL frames. This is a separate area accessed via /admin/ with different auth.

FRAME 1 — ADMIN LOGIN PAGE:
Dark page, centered card max 400px
FITS MOTOR logo + "ADMIN PANEL" label (smaller, grey)
Fields: Email Admin | Password
[MASUK SEBAGAI ADMIN] dark orange button
No register link (admin only via database)

FRAME 2 — ADMIN DASHBOARD (desktop, sidebar layout):
Left sidebar (240px, very dark #0F1724):
- FITS MOTOR logo + "Admin Panel" badge (orange pill)
- Admin name + "Administrator" role
- Orange divider
Menu groups:
  📊 OVERVIEW
    🏠 Dashboard
  📦 PRODUK
    📦 Semua Produk
    ➕ Tambah Produk
    📂 Kategori
    🏷️ Brand
  💳 TRANSAKSI
    🛒 Semua Pesanan
    📅 Booking Servis
  👥 PELANGGAN
    👥 Data Pelanggan
  📊 LAPORAN
    📈 Laporan Penjualan
  ⚙️ PENGATURAN
    ⚙️ Setting Toko

Right content:
Admin name + logout button top-right
Breadcrumb: Dashboard

6 stat cards (2×3 grid):
🛒 Pesanan Hari Ini: 12 (orange)
💰 Revenue Bulan Ini: Rp 12.500.000 (green)
📦 Pesanan Baru: 5 (orange badge — perlu aksi)
📅 Booking Pending: 3 (red badge)
👥 Total Customer: 248 (blue)
📦 Stok Hampir Habis: 7 (yellow)

Chart section: Bar chart penjualan 7 hari terakhir (Chart.js placeholder)

Recent orders table (5 rows): No | Customer | Total | Status | Aksi [Detail]
Upcoming bookings today (2-3 cards)

FRAME 3 — ADMIN: KELOLA PRODUK (list):
Top bar: "Semua Produk" title + [+ Tambah Produk] orange button (right)
Filter bar: search box | kategori dropdown | status dropdown | brand dropdown
Table columns: ☐ | Foto (40px) | Nama Produk | Kategori | Harga | Stok | Status | Aksi
- Status: toggle switch (aktif/nonaktif)
- Aksi: [✏️ Edit] [🗑️ Hapus] icon buttons
- Stok low warning: red badge "Stok: 2" 
Pagination below table

FRAME 4 — ADMIN: FORM TAMBAH/EDIT PRODUK:
Two-column form layout:

LEFT (wider):
- Nama Produk * [text]
- Slug * [text, auto-generate from name] [Preview URL grey small]
- Deskripsi [rich textarea]
- Spesifikasi [textarea]
- Kompatibilitas Motor [text, placeholder: "Honda Beat, Vario, PCX"]

RIGHT:
- Kategori * [dropdown]
- Brand * [dropdown]
- Harga Normal * [number, Rp prefix]
- Harga Diskon [number, Rp prefix]
- Stok * [number]
- Berat (gram) * [number, note: "Untuk kalkulasi ongkir"]
- Badge [select: Kosong | TERLARIS | PROMO | BARU]
- Status [toggle: Aktif / Nonaktif]

FULL WIDTH BELOW:
- Upload Foto Produk (drag & drop zone):
  Dashed border, upload icon, "Drag & drop foto atau klik untuk pilih"
  Support multi-upload (max 5 foto)
  Preview thumbnails with [×] remove + [★ Set Utama] per foto

[SIMPAN PRODUK] orange large + [Batal] grey link

FRAME 5 — ADMIN: KELOLA PESANAN (orders list):
Filter tabs: [Semua] [Baru] [Diproses] [Dikirim] [Selesai] [Dibatalkan]
Date range filter + search by order no / customer

Table: No. Pesanan | Customer | Total | Kurir | Payment | Order Status | Aksi
Status badge colors:
- BARU: orange | DIPROSES: blue | DIKIRIM: cyan | SELESAI: green | BATAL: red
Aksi: [Detail] button

FRAME 6 — ADMIN: DETAIL PESANAN:
Order header: No. #FM-20250120-4521 | Date | Status badge (dropdown to change)

Two column:
Left: Items list | Customer info | Alamat pengiriman
Right (card): Payment summary | Action card

Action card:
If status NEW + PAID:
  [📦 Proses Pesanan] orange button
  Dropdown status: [Pilih status baru]

If PROCESSING:
  Input resi: [Kurir: JNE] [Nomor Resi: ___________] [SIMPAN & KIRIM NOTIF WA] green button

If SHIPPED:
  Show resi + [Mark as Delivered] button

Admin notes textarea + [Simpan Catatan]

FRAME 7 — ADMIN: KELOLA BOOKING (list):
Table: No. Booking | Customer | Motor | Layanan | Tanggal | Waktu | Status | Aksi
Filter: Status tabs + date picker

FRAME 8 — ADMIN: DETAIL BOOKING:
Customer info card | Motor info card | Services list | Date & Time
Status update section:
[KONFIRMASI] green | [IN PROGRESS] orange | [SELESAI] green | [BATALKAN] red
Estimated cost editable field
Admin notes
[📱 Kirim Notifikasi WA ke Customer] green button (with message preview)

FRAME 9 — ADMIN: SETTING TOKO:
Form sections (tabbed or scrolled):
INFORMASI TOKO: Nama | Alamat | Telepon | Email | Jam Buka
WHATSAPP: Nomor WA Admin | Pesan default konsultasi | Pesan default booking
PENGIRIMAN: Kota asal (untuk RajaOngkir) | Berat default
PAYMENT: API key Tripay (masked) | Merchant code
SOSIAL MEDIA: Instagram | TikTok | Facebook | YouTube
[SIMPAN SETTING] orange button

FRAME 10 — ADMIN PANEL (mobile/tablet responsive):
- Sidebar collapses to hamburger
- Cards stack to single column
- Tables scroll horizontally
- Forms single-column
```

---

## 📌 PROMPT 9 — HALAMAN PROFIL PERUSAHAAN (FULL FRAMES)

```
[SYSTEM CONTEXT — FITS MOTOR]

Design the complete company profile / About Us page with ALL frames:

FRAME 1 — HERO BANNER:
Full-width dark hero, 400px height
Dramatic overlay gradient (dark left, transparent right)
Title: "TENTANG FITS MOTOR" (Bebas Neue 80px white)
Subtitle: "Lebih dari sekedar bengkel — kami adalah komunitas pecinta motor"
Breadcrumb: Beranda > Tentang Kami

FRAME 2 — OUR STORY (2-column):
Left: 
  Large: "Berdiri Sejak 2016" (Bebas Neue, orange accent line)
  2 paragraphs: story text (DM Sans, grey-white)
  4 small stat pills: 8 Tahun | 4 Mekanik | 2000+ Pelanggan | 500+ Produk
Right:
  Stylized photo frame (mock workshop image) with orange corner bracket accents
  Below: "📍 Banjarnegara, Jawa Tengah"

FRAME 3 — VISION & MISSION (3 dark cards):
Large icon (64px) + Title (Bebas Neue, white) + body text (grey)
🎯 Visi | ⚡ Misi 1 | 🤝 Misi 2
Cards: dark #16213E, subtle orange top border accent

FRAME 4 — TIMELINE (vertical, centered):
Year milestones alternating left/right:
2016 ● — Berdiri dengan 2 mekanik di ruko kecil
2018 ● — Pindah ke lokasi lebih besar, tambah 2 mekanik
2020 ● — Mulai jual aksesori online via marketplace  
2022 ● — 1000+ pelanggan terdaftar, buka servis injeksi
2024 ● — Grand opening toko + workshop baru

Orange circle dots connected by vertical line (center)
Year labels: gold #E8B800
Description: white DM Sans
Alternating sides: even left, odd right

FRAME 5 — OUR TEAM (mechanic profiles, 4-column grid):
Each mechanic card:
- Photo (rounded, 120px, mock avatar)
- Name (white bold)
- Title: "Kepala Mekanik" / "Spesialis Injeksi" (orange small)
- Experience: "8 Tahun Pengalaman"
- Certification badge: "AHM Certified ✓" (gold small pill)
Dark card, orange accent on hover

FRAME 6 — FACILITIES (alternating image + text):
3 facilities, alternating layout:
1. Photo left | Text right — "Area Servis Modern" 
2. Text left | Photo right — "Ruang Tunggu Nyaman"
3. Photo left | Text right — "Toko Aksesori Lengkap"
Each: title (Bebas Neue) + desc text + 3 bullet features

FRAME 7 — BRAND PARTNERS LOGO GRID:
"BRAND RESMI YANG KAMI JUAL" (centered title)
Logo grid 4-column: AHM | Yamalube | Shell | Federal | FDR | IRC | NGK | Bosch
Greyscale logos, color on hover

FRAME 8 — LOCATION CARD:
Two column: 
Left: Google Maps embed (mock dark map placeholder, 400px height)
Right dark card:
📍 Alamat: Jl. Raya Motor No. 123, Banjarnegara, Jawa Tengah
📞 Telepon: 0812-XXXX-XXXX
✉️ Email: admin@fitsmotor.com
⏰ Jam Buka: Senin–Sabtu, 08.00–17.00 WIB
[📍 Buka di Google Maps] orange button
[💬 Chat WhatsApp] green button

FRAME 9 — ABOUT PAGE (mobile, 375px):
All sections single column, timeline center-aligned, team 2-column grid
```

---

## 📌 PROMPT 10 — ORDER TRACKING & MOBILE APP (FULL FRAMES)

```
[SYSTEM CONTEXT — FITS MOTOR]

Design order tracking and mobile-optimized views with ALL frames:

FRAME 1 — ORDER TRACKING PAGE (desktop):
Header: "LACAK PESANAN" | Search: [No. Pesanan atau No. HP] [Cari]
Order header card:
No. #FM-20250120-4521 | Status badge | Date

Vertical timeline tracker (4-5 nodes, left-aligned):
✅ Pesanan Dikonfirmasi — 20 Jan, 10:30 WIB (green, line to next)
✅ Sedang Dikemas — 20 Jan, 14:00 WIB (green)
🔄 Dalam Pengiriman — 21 Jan (orange, pulsing dot = active)
⬜ Pesanan Tiba (grey, upcoming)
Each node: icon circle + status text + timestamp

Shipping info card:
- Kurir: JNE REG + logo
- No. Resi: JX1234567890 (copyable, orange monospace)
- [CEK RESI DI WEBSITE JNE →] external link button
- Estimasi tiba: 23-24 Januari 2025

Order items (collapsible):
[▼ Lihat Detail Produk] — toggle reveals item list

[💬 Ada masalah? Chat Admin] green WA button

FRAME 2 — TRACKING (mobile, 375px):
Full screen, timeline stacked, shipping card below
Back button top-left

FRAME 3 — MOBILE BOTTOM NAVIGATION (for logged-in customer):
Fixed bottom bar, 5 icons:
🏠 Beranda | 🛒 Produk | 📅 Booking | 📦 Pesanan | 👤 Akun
Active icon: orange color + small orange dot above
Dark bg, subtle top border

FRAME 4 — MOBILE SEARCH PAGE:
Full-screen search overlay
Large search input at top (autofocus)
Below: recent searches chips | popular categories
As user types: autocomplete results dropdown
Each result: product thumbnail + name + price
[🔍 Cari di semua produk untuk "lampu led"] bottom option

FRAME 5 — CONTACT PAGE:
Hero: "HUBUNGI KAMI" 
Two column:
Left: Contact form:
  Nama | Email | No HP | Topik [select] | Pesan [textarea]
  [KIRIM PESAN] orange
Right: contact info cards + embedded map
[💬 WhatsApp Langsung] large green button top of right column

FRAME 6 — 404 ERROR PAGE:
Dark bg, centered
Large "404" (Bebas Neue, orange, 200px)
"Halaman tidak ditemukan"
[← Kembali ke Beranda] orange button
```

---

## 📤 CARA EXPORT DARI STITCH (PHP NATIVE)

```
1. Setelah design selesai → klik "Export"
2. Pilih format: HTML/CSS (BUKAN React)
3. Copy kode HTML ke file PHP yang sesuai:
   - Hapus tag <html><head><body> → ganti dengan <?php require 'includes/header.php'; ?>
   - Ganti konten dinamis dengan variabel PHP: <?= $product['name'] ?>
   - Paste CSS ke assets/css/style.css
   - Paste JS ke assets/js/main.js
4. Atau gunakan sebagai referensi desain untuk implementasi manual
```

---

**Total: 10 Prompt — Semua Halaman Fits Motor dengan Frames Lengkap & Detail 🏍️🔥**
**PHP Native | Dua Role: Admin & Customer | Mobile Responsive**
