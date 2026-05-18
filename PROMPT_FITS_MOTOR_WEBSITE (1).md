# 🏍️ PROMPT AI — WEBSITE FITS MOTOR (PHP NATIVE)
## Full-Stack Website: Profil, E-Commerce, Booking & Konsultasi
## Stack: PHP Native + MySQL | Dua Role: Admin & Customer

---

## 🎯 KONTEKS PROYEK

Kamu adalah developer full-stack senior yang akan membangun website profesional untuk **FITS MOTOR** — bengkel spesialis modifikasi, perawatan, dan aksesori sepeda motor menggunakan **PHP Native** (tanpa framework) dan **MySQL** sebagai database.

Website ini berfungsi sebagai:
1. **Company Profile** — branding dan kepercayaan
2. **E-Commerce** — jual produk/part motor dengan payment gateway **Tripay**
3. **Kalkulasi Ongkir** — terintegrasi API **RajaOngkir**
4. **Booking Service** — pelanggan bisa jadwalkan servis/modifikasi
5. **Konsultasi WhatsApp** — tombol floating WA langsung ke admin
6. **Dua Role Pengguna** — **Admin** (kelola semua data) dan **Customer** (belanja, booking, riwayat)

---

## 🏗️ ARSITEKTUR WEBSITE

### Stack Teknologi:
- **Frontend**: PHP Native + HTML5 + CSS3 + JavaScript (Vanilla / Alpine.js opsional)
- **Backend**: PHP Native (OOP, PDO)
- **Database**: MySQL 8.x
- **Payment**: Tripay Payment Gateway
- **Ongkir**: RajaOngkir API
- **Session**: PHP Session + Cookie
- **Auth**: Custom PHP session-based authentication (dua role)
- **Upload**: PHP `move_uploaded_file()` ke folder `/uploads/`
- **Hosting**: Shared hosting / VPS (Apache/Nginx + PHP 8.x)

---

## 👥 SISTEM DUA ROLE

### ROLE 1 — CUSTOMER (Pelanggan)
Akses fitur:
- Register & login akun sendiri
- Lihat & beli produk
- Keranjang belanja
- Checkout + ongkir + pembayaran
- Riwayat pesanan & status order
- Booking servis motor
- Riwayat booking
- Edit profil & alamat
- Konsultasi via WhatsApp

### ROLE 2 — ADMIN (Pengelola Toko)
Akses fitur:
- Login ke dashboard admin terpisah
- Kelola produk (tambah, edit, hapus, stok)
- Kelola kategori & brand
- Kelola pesanan (konfirmasi, update status, input resi)
- Kelola booking servis (konfirmasi, update status)
- Kelola pelanggan
- Kelola banner & konten beranda
- Laporan penjualan
- Setting toko (info, WA, ongkos kirim)
- Upload gambar produk

---

## 📁 STRUKTUR FOLDER PROJECT (PHP NATIVE)

```
fits-motor/
│
├── index.php                    # Beranda / Landing Page
├── about.php                    # Profil Perusahaan
├── products.php                 # Katalog Produk
├── product-detail.php           # Detail Produk (GET: ?slug=xxx)
├── cart.php                     # Keranjang Belanja
├── checkout.php                 # Checkout
├── payment-instruction.php      # Instruksi Pembayaran
├── order-tracking.php           # Tracking Pesanan
├── booking.php                  # Form Booking Servis
├── booking-success.php          # Sukses Booking
├── contact.php                  # Halaman Kontak
│
├── auth/
│   ├── login.php                # Login Customer
│   ├── register.php             # Register Customer
│   ├── logout.php               # Logout
│   ├── forgot-password.php      # Lupa Password
│
├── customer/                    # Area Customer (harus login)
│   ├── dashboard.php            # Dashboard pelanggan
│   ├── profile.php              # Edit profil
│   ├── orders.php               # Riwayat pesanan
│   ├── order-detail.php         # Detail pesanan
│   ├── bookings.php             # Riwayat booking
│   ├── addresses.php            # Kelola alamat
│
├── admin/                       # Area Admin (harus login admin)
│   ├── login.php                # Login Admin
│   ├── logout.php               # Logout Admin
│   ├── dashboard.php            # Dashboard Admin
│   ├── products/
│   │   ├── index.php            # List produk
│   │   ├── create.php           # Tambah produk
│   │   ├── edit.php             # Edit produk
│   │   └── delete.php           # Hapus produk
│   ├── categories/
│   │   ├── index.php
│   │   ├── create.php
│   │   └── edit.php
│   ├── orders/
│   │   ├── index.php            # List semua order
│   │   ├── detail.php           # Detail & update status order
│   │   └── update-status.php
│   ├── bookings/
│   │   ├── index.php            # List semua booking
│   │   ├── detail.php
│   │   └── update-status.php
│   ├── customers/
│   │   └── index.php            # List pelanggan
│   ├── reports/
│   │   └── sales.php            # Laporan penjualan
│   └── settings/
│       └── index.php            # Setting toko
│
├── api/                         # API Endpoint (JSON response)
│   ├── shipping-provinces.php   # GET daftar provinsi RajaOngkir
│   ├── shipping-cities.php      # GET kota by provinsi
│   ├── shipping-cost.php        # POST hitung ongkir
│   ├── payment-create.php       # POST buat transaksi Tripay
│   ├── payment-callback.php     # POST webhook Tripay
│   ├── cart-add.php             # POST tambah ke keranjang
│   ├── cart-update.php          # POST update qty keranjang
│   ├── cart-remove.php          # POST hapus item keranjang
│   └── booking-slots.php        # GET slot waktu tersedia
│
├── includes/
│   ├── config.php               # Konfigurasi DB & API keys
│   ├── db.php                   # Koneksi PDO MySQL
│   ├── functions.php            # Helper functions
│   ├── auth.php                 # Fungsi login/session/role check
│   ├── header.php               # Header HTML (navbar)
│   ├── footer.php               # Footer HTML
│   ├── admin-header.php         # Header admin panel
│   └── admin-footer.php
│
├── assets/
│   ├── css/
│   │   ├── style.css            # Main CSS (public)
│   │   └── admin.css            # CSS admin panel
│   ├── js/
│   │   ├── main.js              # JS utama (public)
│   │   ├── checkout.js          # JS checkout + ongkir
│   │   └── admin.js             # JS admin
│   └── images/
│       ├── logo.png
│       └── placeholder.jpg
│
├── uploads/                     # Gambar upload (writable)
│   ├── products/
│   ├── banners/
│   └── team/
│
└── .htaccess                    # URL rewriting (Apache)
```

---

## 🔐 SISTEM AUTENTIKASI (PHP SESSION)

### Tabel Database Auth:

```sql
-- Tabel Users (Customer)
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  phone VARCHAR(20),
  password VARCHAR(255) NOT NULL,  -- password_hash()
  role ENUM('customer','admin') DEFAULT 'customer',
  is_active TINYINT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin
INSERT INTO users (name, email, password, role) VALUES 
('Admin Fits Motor', 'admin@fitsmotor.com', 
 '$2y$10$hashedpassword', 'admin');
```

### File: `includes/auth.php`

```php
<?php
session_start();

// Cek apakah sudah login sebagai customer
function isCustomerLoggedIn() {
    return isset($_SESSION['user_id']) && $_SESSION['role'] === 'customer';
}

// Cek apakah sudah login sebagai admin
function isAdminLoggedIn() {
    return isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin';
}

// Paksa login customer (redirect jika belum login)
function requireCustomerLogin() {
    if (!isCustomerLoggedIn()) {
        header('Location: /auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

// Paksa login admin
function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header('Location: /admin/login.php');
        exit;
    }
}

// Login proses
function loginUser($email, $password, $db) {
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        return $user['role']; // 'admin' atau 'customer'
    }
    return false;
}

// Logout
function logoutUser() {
    session_destroy();
    header('Location: /auth/login.php');
    exit;
}
```

### File: `auth/login.php` — Login Customer

```php
<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

$error = '';
$redirect = $_GET['redirect'] ?? '/customer/dashboard.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $role = loginUser($email, $password, $db);
    if ($role === 'customer') {
        header('Location: ' . $redirect);
        exit;
    } elseif ($role === 'admin') {
        // Admin mencoba login di form customer
        $error = 'Silakan gunakan halaman login admin.';
    } else {
        $error = 'Email atau password salah.';
    }
}
?>
<!-- HTML Form Login Customer (dark theme) -->
```

### File: `admin/login.php` — Login Admin (halaman terpisah)

```php
<?php
// Admin punya halaman login sendiri di /admin/login.php
if ($role === 'admin') {
    header('Location: /admin/dashboard.php');
    exit;
} elseif ($role === 'customer') {
    $error = 'Akun ini bukan akun admin.';
}
```

---

## 🗃️ SKEMA DATABASE LENGKAP (MySQL)

```sql
-- Database
CREATE DATABASE fits_motor CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fits_motor;

-- Users (Customer & Admin)
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  phone VARCHAR(20),
  password VARCHAR(255) NOT NULL,
  role ENUM('customer','admin') DEFAULT 'customer',
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Alamat Customer
CREATE TABLE addresses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  label VARCHAR(50),             -- "Rumah", "Kantor"
  recipient_name VARCHAR(100),
  phone VARCHAR(20),
  province_id INT,
  province_name VARCHAR(100),
  city_id INT,
  city_name VARCHAR(100),
  district VARCHAR(100),
  address TEXT,
  postal_code VARCHAR(10),
  is_primary TINYINT(1) DEFAULT 0,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Kategori Produk
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(100) UNIQUE NOT NULL,
  icon VARCHAR(50),
  is_active TINYINT(1) DEFAULT 1
);

-- Brand / Merek
CREATE TABLE brands (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  logo VARCHAR(255),
  is_active TINYINT(1) DEFAULT 1
);

-- Produk
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT,
  brand_id INT,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) UNIQUE NOT NULL,
  description TEXT,
  specifications TEXT,
  price DECIMAL(12,2) NOT NULL,
  discount_price DECIMAL(12,2) DEFAULT NULL,
  stock INT DEFAULT 0,
  weight INT NOT NULL,           -- gram (untuk RajaOngkir)
  compatibility TEXT,            -- kompatibel tipe motor
  badge ENUM('','TERLARIS','PROMO','BARU') DEFAULT '',
  is_active TINYINT(1) DEFAULT 1,
  sold_count INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id),
  FOREIGN KEY (brand_id) REFERENCES brands(id)
);

-- Gambar Produk
CREATE TABLE product_images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  is_primary TINYINT(1) DEFAULT 0,
  sort_order INT DEFAULT 0,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Reviews / Ulasan
CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  user_id INT NOT NULL,
  order_id INT,
  rating TINYINT NOT NULL,       -- 1-5
  comment TEXT,
  image_path VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Keranjang Belanja
CREATE TABLE cart (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  session_id VARCHAR(100),       -- untuk guest (belum login)
  product_id INT NOT NULL,
  quantity INT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Orders (Pesanan)
CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_number VARCHAR(50) UNIQUE NOT NULL,
  user_id INT,
  customer_name VARCHAR(100) NOT NULL,
  customer_email VARCHAR(100),
  customer_phone VARCHAR(20) NOT NULL,
  -- Alamat pengiriman
  recipient_name VARCHAR(100),
  recipient_phone VARCHAR(20),
  province_id INT,
  province_name VARCHAR(100),
  city_id INT,
  city_name VARCHAR(100),
  district VARCHAR(100),
  address TEXT,
  postal_code VARCHAR(10),
  -- Pengiriman
  courier VARCHAR(50),
  courier_service VARCHAR(50),
  shipping_cost DECIMAL(12,2) DEFAULT 0,
  estimated_days VARCHAR(20),
  tracking_number VARCHAR(100),
  -- Pembayaran
  payment_method VARCHAR(50),
  payment_status ENUM('PENDING','PAID','FAILED','EXPIRED') DEFAULT 'PENDING',
  tripay_reference VARCHAR(100),
  payment_url TEXT,
  paid_at TIMESTAMP NULL,
  -- Total
  subtotal DECIMAL(12,2) NOT NULL,
  voucher_discount DECIMAL(12,2) DEFAULT 0,
  total DECIMAL(12,2) NOT NULL,
  -- Status
  order_status ENUM('NEW','PROCESSING','SHIPPED','DELIVERED','CANCELLED') DEFAULT 'NEW',
  notes TEXT,
  admin_notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Item dalam Order
CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT,
  product_name VARCHAR(255) NOT NULL,
  product_image VARCHAR(255),
  price DECIMAL(12,2) NOT NULL,
  quantity INT NOT NULL,
  subtotal DECIMAL(12,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Booking Servis
CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  booking_number VARCHAR(50) UNIQUE NOT NULL,
  user_id INT,
  customer_name VARCHAR(100) NOT NULL,
  customer_phone VARCHAR(20) NOT NULL,
  customer_email VARCHAR(100),
  motor_brand VARCHAR(100),
  motor_model VARCHAR(100),
  motor_year INT,
  plate_number VARCHAR(20),
  complaint TEXT,
  services JSON,                 -- array layanan dipilih
  booking_date DATE NOT NULL,
  booking_time VARCHAR(20) NOT NULL,
  estimated_cost DECIMAL(12,2),
  status ENUM('PENDING','CONFIRMED','IN_PROGRESS','DONE','CANCELLED') DEFAULT 'PENDING',
  notes TEXT,
  admin_notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Testimonials
CREATE TABLE testimonials (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_name VARCHAR(100),
  motor_type VARCHAR(100),
  rating TINYINT DEFAULT 5,
  comment TEXT,
  avatar VARCHAR(255),
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Gallery Modifikasi
CREATE TABLE gallery (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255),
  image_path VARCHAR(255) NOT NULL,
  category VARCHAR(100),
  is_active TINYINT(1) DEFAULT 1,
  sort_order INT DEFAULT 0
);

-- Banners Beranda
CREATE TABLE banners (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255),
  subtitle TEXT,
  image_path VARCHAR(255),
  link_url VARCHAR(255),
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1
);

-- Setting Toko
CREATE TABLE settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  key_name VARCHAR(100) UNIQUE NOT NULL,
  value TEXT
);

INSERT INTO settings (key_name, value) VALUES
('shop_name', 'FITS MOTOR'),
('shop_phone', '628123456789'),
('shop_address', 'Jl. Raya Motor No. 123, Banjarnegara, Jawa Tengah'),
('shop_email', 'admin@fitsmotor.com'),
('shop_hours', 'Senin - Sabtu: 08.00 - 17.00 WIB'),
('rajaongkir_origin_city_id', '23'),
('wa_default_message', 'Halo Fits Motor! Saya ingin konsultasi.');
```

---

## ⚙️ KONFIGURASI (includes/config.php)

```php
<?php
// Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'fits_motor');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
define('DB_CHARSET', 'utf8mb4');

// Tripay
define('TRIPAY_API_KEY', 'DEV-xxxxxxxxxxxxxxxxxxxx');
define('TRIPAY_PRIVATE_KEY', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxx');
define('TRIPAY_MERCHANT_CODE', 'T12345');
define('TRIPAY_BASE_URL', 'https://tripay.co.id/api-sandbox'); // Ganti ke api saat produksi

// RajaOngkir
define('RAJAONGKIR_API_KEY', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
define('RAJAONGKIR_BASE_URL', 'https://api.rajaongkir.com/starter');
define('RAJAONGKIR_ORIGIN_CITY', '23'); // Kode kota asal pengiriman

// WhatsApp
define('WA_NUMBER', '628123456789');
define('WA_MESSAGE', 'Halo Fits Motor! Saya ingin konsultasi.');

// Base URL
define('BASE_URL', 'https://fitsmotor.com');
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('UPLOAD_URL', BASE_URL . '/uploads/');
```

---

## 🔗 KONEKSI DATABASE (includes/db.php)

```php
<?php
require_once __DIR__ . '/config.php';

try {
    $db = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die(json_encode(['error' => 'Database connection failed']));
}
```

---

## 🚚 INTEGRASI RAJAONGKIR (PHP)

### File: `api/shipping-provinces.php`

```php
<?php
header('Content-Type: application/json');
require_once '../includes/config.php';

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => RAJAONGKIR_BASE_URL . '/province',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['key: ' . RAJAONGKIR_API_KEY]
]);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
echo json_encode($data['rajaongkir']['results']);
```

### File: `api/shipping-cities.php`

```php
<?php
header('Content-Type: application/json');
require_once '../includes/config.php';

$province_id = intval($_GET['province_id'] ?? 0);

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => RAJAONGKIR_BASE_URL . '/city?province=' . $province_id,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['key: ' . RAJAONGKIR_API_KEY]
]);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
echo json_encode($data['rajaongkir']['results']);
```

### File: `api/shipping-cost.php`

```php
<?php
header('Content-Type: application/json');
require_once '../includes/config.php';

$input = json_decode(file_get_contents('php://input'), true);
$destination = intval($input['city_id'] ?? 0);
$weight = intval($input['weight'] ?? 1000);
$courier = $input['courier'] ?? 'jne';

$postData = http_build_query([
    'origin'      => RAJAONGKIR_ORIGIN_CITY,
    'destination' => $destination,
    'weight'      => $weight,
    'courier'     => $courier
]);

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL            => RAJAONGKIR_BASE_URL . '/cost',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $postData,
    CURLOPT_HTTPHEADER     => [
        'key: ' . RAJAONGKIR_API_KEY,
        'content-type: application/x-www-form-urlencoded'
    ]
]);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
echo json_encode($data['rajaongkir']['results']);
```

---

## 💳 INTEGRASI TRIPAY PAYMENT GATEWAY (PHP)

### File: `api/payment-create.php`

```php
<?php
header('Content-Type: application/json');
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

session_start();

$input = json_decode(file_get_contents('php://input'), true);
$order_id = intval($input['order_id'] ?? 0);

// Ambil data order dari DB
$stmt = $db->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    echo json_encode(['success' => false, 'message' => 'Order tidak ditemukan']);
    exit;
}

$merchant_ref = $order['order_number'];
$amount       = (int)$order['total'];

// Signature Tripay
$signature = hash_hmac('sha256', 
    TRIPAY_MERCHANT_CODE . $merchant_ref . $amount, 
    TRIPAY_PRIVATE_KEY
);

// Ambil items order
$stmt2 = $db->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt2->execute([$order_id]);
$items = $stmt2->fetchAll();

$order_items = array_map(fn($item) => [
    'name'     => $item['product_name'],
    'price'    => (int)$item['price'],
    'quantity' => (int)$item['quantity'],
], $items);

$payload = [
    'method'         => $input['payment_method'],
    'merchant_ref'   => $merchant_ref,
    'amount'         => $amount,
    'customer_name'  => $order['customer_name'],
    'customer_email' => $order['customer_email'],
    'customer_phone' => $order['customer_phone'],
    'order_items'    => $order_items,
    'return_url'     => BASE_URL . '/payment-instruction.php?order=' . $merchant_ref,
    'expired_time'   => time() + (24 * 60 * 60), // 24 jam
    'signature'      => $signature
];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL            => TRIPAY_BASE_URL . '/transaction/create',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($payload),
    CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . TRIPAY_API_KEY,
        'Content-Type: application/json'
    ]
]);
$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

if ($result['success']) {
    // Update order di DB
    $stmt3 = $db->prepare("
        UPDATE orders SET 
            payment_method = ?,
            tripay_reference = ?,
            payment_url = ?
        WHERE id = ?
    ");
    $stmt3->execute([
        $input['payment_method'],
        $result['data']['reference'],
        $result['data']['checkout_url'],
        $order_id
    ]);
}

echo json_encode($result);
```

### File: `api/payment-callback.php` (Webhook Tripay)

```php
<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

// Verifikasi signature dari Tripay
$json      = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_CALLBACK_SIGNATURE'] ?? '';
$event     = $_SERVER['HTTP_X_CALLBACK_EVENT'] ?? '';

$expected = hash_hmac('sha256', $json, TRIPAY_PRIVATE_KEY);
if (!hash_equals($expected, $signature)) {
    http_response_code(400);
    die(json_encode(['message' => 'Invalid signature']));
}

$data = json_decode($json, true);

if ($event === 'payment_status') {
    $merchant_ref    = $data['merchant_ref'];
    $status          = $data['status']; // PAID, FAILED, EXPIRED

    $payment_status = match($status) {
        'PAID'    => 'PAID',
        'FAILED'  => 'FAILED',
        'EXPIRED' => 'EXPIRED',
        default   => 'PENDING'
    };

    $order_status = $status === 'PAID' ? 'PROCESSING' : null;

    if ($order_status) {
        $stmt = $db->prepare("
            UPDATE orders 
            SET payment_status = ?, order_status = ?, paid_at = NOW()
            WHERE order_number = ?
        ");
        $stmt->execute([$payment_status, $order_status, $merchant_ref]);
    } else {
        $stmt = $db->prepare("
            UPDATE orders SET payment_status = ? WHERE order_number = ?
        ");
        $stmt->execute([$payment_status, $merchant_ref]);
    }
}

echo json_encode(['success' => true]);
```

---

## 📅 SISTEM BOOKING SERVIS (PHP)

### File: `api/booking-slots.php`

```php
<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$date = $_GET['date'] ?? date('Y-m-d');
$max_per_slot = 3; // Maksimum booking per slot waktu

$slots = ['08:00-10:00', '10:00-12:00', '13:00-15:00', '15:00-17:00'];
$result = [];

foreach ($slots as $slot) {
    $stmt = $db->prepare("
        SELECT COUNT(*) as count FROM bookings 
        WHERE booking_date = ? AND booking_time = ? 
        AND status NOT IN ('CANCELLED')
    ");
    $stmt->execute([$date, $slot]);
    $count = $stmt->fetch()['count'];
    
    $result[] = [
        'time'      => $slot,
        'available' => $count < $max_per_slot,
        'remaining' => max(0, $max_per_slot - $count)
    ];
}

echo json_encode($result);
```

### File: `booking.php` (Multi-Step Form)

```php
<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Proses submit booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_number = 'BK-' . date('Ymd') . '-' . strtoupper(uniqid());
    
    $services = $_POST['services'] ?? [];
    
    $stmt = $db->prepare("
        INSERT INTO bookings 
        (booking_number, user_id, customer_name, customer_phone, customer_email,
         motor_brand, motor_model, motor_year, plate_number, complaint,
         services, booking_date, booking_time, estimated_cost)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $booking_number,
        $_SESSION['user_id'] ?? null,
        $_POST['customer_name'],
        $_POST['customer_phone'],
        $_POST['customer_email'] ?? null,
        $_POST['motor_brand'],
        $_POST['motor_model'],
        $_POST['motor_year'],
        $_POST['plate_number'],
        $_POST['complaint'] ?? null,
        json_encode($services),
        $_POST['booking_date'],
        $_POST['booking_time'],
        $_POST['estimated_cost'] ?? null,
    ]);
    
    // Kirim notifikasi WA (opsional via Fonnte)
    sendBookingWhatsApp($_POST['customer_phone'], $booking_number, $_POST);
    
    header('Location: /booking-success.php?no=' . $booking_number);
    exit;
}

require_once 'includes/header.php';
?>
<!-- HTML Form Booking Multi-Step -->
```

---

## 👤 CUSTOMER DASHBOARD

### File: `customer/dashboard.php`

```php
<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireCustomerLogin();

$user_id = $_SESSION['user_id'];

// Statistik customer
$stmt = $db->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
$stmt->execute([$user_id]);
$total_orders = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ?");
$stmt->execute([$user_id]);
$total_bookings = $stmt->fetchColumn();

// Order terbaru
$stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$user_id]);
$recent_orders = $stmt->fetchAll();

// Booking terbaru
$stmt = $db->prepare("SELECT * FROM bookings WHERE user_id = ? ORDER BY created_at DESC LIMIT 3");
$stmt->execute([$user_id]);
$recent_bookings = $stmt->fetchAll();
?>
<!-- 
TAMPILKAN:
1. Greeting: "Halo, [Nama Customer]! 👋"
2. Statistik card: Total Pesanan | Total Booking | Poin Reward
3. Tabel order terbaru dengan status badge berwarna
4. Card booking terbaru
5. Shortcut: [Belanja Lagi] [Booking Servis] [Edit Profil]
-->
```

---

## 🛡️ ADMIN DASHBOARD

### File: `admin/dashboard.php`

```php
<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireAdminLogin();

// Statistik
$stats = [];
$queries = [
    'total_orders'    => "SELECT COUNT(*) FROM orders",
    'pending_orders'  => "SELECT COUNT(*) FROM orders WHERE payment_status = 'PAID' AND order_status = 'NEW'",
    'total_revenue'   => "SELECT COALESCE(SUM(total),0) FROM orders WHERE payment_status = 'PAID'",
    'total_products'  => "SELECT COUNT(*) FROM products WHERE is_active = 1",
    'total_customers' => "SELECT COUNT(*) FROM users WHERE role = 'customer'",
    'pending_bookings'=> "SELECT COUNT(*) FROM bookings WHERE status = 'PENDING'",
];

foreach ($queries as $key => $sql) {
    $stats[$key] = $db->query($sql)->fetchColumn();
}
?>
<!--
TAMPILKAN:
1. Header "ADMIN PANEL — FITS MOTOR" + nama admin + tombol logout
2. Sidebar menu:
   🏠 Dashboard
   📦 Produk
   📂 Kategori
   🛒 Pesanan
   📅 Booking
   👥 Pelanggan
   📊 Laporan
   ⚙️ Setting
3. Stat cards dengan warna:
   - Total Pesanan (biru)
   - Pesanan Baru (oranye, perlu aksi)
   - Revenue Bulan Ini (hijau)
   - Total Produk (ungu)
   - Total Customer (kuning)
   - Booking Pending (merah)
4. Tabel pesanan terbaru
5. Chart penjualan (opsional, Chart.js)
6. Tabel booking hari ini
-->
```

### File: `admin/orders/index.php`

```php
<?php
// Admin kelola semua pesanan
// Filter: status, tanggal, kurir
// Actions: lihat detail, update status, input nomor resi
// Export ke CSV opsional
?>
```

### File: `admin/bookings/index.php`

```php
<?php
// Admin kelola semua booking servis
// Filter: status, tanggal, layanan
// Actions: konfirmasi, mulai pengerjaan, selesai, batal
// Kirim notifikasi WA ke customer saat status berubah
?>
```

---

## 📱 NOTIFIKASI WHATSAPP (via Fonnte)

```php
<?php
// includes/functions.php

function sendBookingWhatsApp($phone, $booking_number, $data) {
    $message = "✅ *BOOKING KONFIRMASI — FITS MOTOR*\n\n";
    $message .= "Halo {$data['customer_name']}! Booking kamu sudah kami terima 🏍️\n\n";
    $message .= "📋 *Detail Booking:*\n";
    $message .= "• No. Booking: {$booking_number}\n";
    $message .= "• Motor: {$data['motor_brand']} {$data['motor_model']}\n";
    $message .= "• Tanggal: {$data['booking_date']}\n";
    $message .= "• Waktu: {$data['booking_time']}\n\n";
    $message .= "📍 Lokasi: Fits Motor, Banjarnegara\n";
    $message .= "Balas pesan ini jika ada pertanyaan 😊";

    $ch = curl_init('https://api.fonnte.com/send');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode([
            'target'  => $phone,
            'message' => $message,
        ]),
        CURLOPT_HTTPHEADER => [
            'Authorization: ' . FONNTE_TOKEN,
            'Content-Type: application/json'
        ]
    ]);
    curl_exec($ch);
    curl_close($ch);
}

function sendOrderWhatsApp($phone, $order_number, $total, $payment_method) {
    $message = "🛒 *PESANAN DITERIMA — FITS MOTOR*\n\n";
    $message .= "No. Pesanan: {$order_number}\n";
    $message .= "Total: Rp " . number_format($total, 0, ',', '.') . "\n";
    $message .= "Metode: {$payment_method}\n\n";
    $message .= "Segera lakukan pembayaran agar pesanan diproses. Terima kasih! 🙏";

    // kirim via Fonnte (sama seperti di atas)
}
```

---

## 🎨 DESIGN SYSTEM (CSS)

```css
/* assets/css/style.css */
:root {
  --primary:       #FF4500;
  --primary-dark:  #CC3700;
  --secondary:     #1A1A2E;
  --accent:        #E8B800;
  --surface:       #16213E;
  --surface-light: #0F3460;
  --text-primary:  #FFFFFF;
  --text-secondary:#A0AEC0;
  --success:       #38A169;
  --danger:        #E53E3E;
  --border:        rgba(255,255,255,0.1);
}

/* Role badges */
.badge-admin    { background: #7B2FBE; color: #fff; }
.badge-customer { background: var(--primary); color: #fff; }
.badge-pending  { background: #DD6B20; color: #fff; }
.badge-paid     { background: #38A169; color: #fff; }
.badge-shipped  { background: #3182CE; color: #fff; }
.badge-done     { background: #38A169; color: #fff; }
.badge-cancelled{ background: #E53E3E; color: #fff; }
```

---

## ✅ CHECKLIST FITUR LENGKAP

### Public (Semua Pengunjung):
- [x] Landing page profesional + dark theme
- [x] Profil perusahaan + timeline
- [x] Katalog produk dengan filter & search
- [x] Detail produk + galeri foto
- [x] Floating WhatsApp button

### Customer (Harus Login):
- [x] Register & login akun
- [x] Keranjang belanja
- [x] Checkout + input alamat pengiriman
- [x] Pilih kurir (RajaOngkir)
- [x] Pilih metode bayar (Tripay)
- [x] Instruksi pembayaran + countdown
- [x] Riwayat & tracking pesanan
- [x] Booking servis multi-step
- [x] Riwayat booking
- [x] Edit profil & kelola alamat
- [x] Notifikasi WA konfirmasi order & booking

### Admin:
- [x] Login admin terpisah (/admin/login.php)
- [x] Dashboard statistik lengkap
- [x] CRUD produk + upload multi-foto
- [x] Kelola kategori & brand
- [x] Proses pesanan + update status + input resi
- [x] Kelola booking + konfirmasi jadwal
- [x] Lihat daftar customer
- [x] Webhook Tripay (konfirmasi bayar otomatis)
- [x] Setting toko (nama, alamat, WA, jam buka)
- [x] Notifikasi WA ke customer saat status berubah

---

## 🚀 CARA INSTALL (Shared Hosting / VPS)

```bash
# 1. Upload semua file ke public_html (atau folder website)

# 2. Import database
mysql -u user -p fits_motor < database.sql

# 3. Edit konfigurasi
nano includes/config.php
# Isi: DB_HOST, DB_NAME, DB_USER, DB_PASS, API keys

# 4. Set permission folder upload
chmod 755 uploads/
chmod 755 uploads/products/

# 5. Aktifkan mod_rewrite Apache (untuk .htaccess)
# Pastikan AllowOverride All di httpd.conf

# 6. Akses website
# Public: https://fitsmotor.com
# Admin:  https://fitsmotor.com/admin/
```

### File: `.htaccess`

```apache
RewriteEngine On
RewriteBase /

# Blokir akses langsung ke includes
RewriteRule ^includes/ - [F,L]

# Redirect admin ke login jika belum ada session
# (Ditangani di PHP, bukan .htaccess)

Options -Indexes
```

---

**Website FITS MOTOR — PHP Native, Dua Role (Admin & Customer), Siap Produksi! 🏍️🔥**
