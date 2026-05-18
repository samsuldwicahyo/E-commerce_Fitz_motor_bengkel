<?php
declare(strict_types=1);

function fitsMotorSchemaTableExists(PDO $db, string $table): bool
{
    $statement = $db->prepare('SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table_name');
    $statement->execute(['table_name' => $table]);

    return (int) $statement->fetchColumn() > 0;
}

function fitsMotorSchemaColumnExists(PDO $db, string $table, string $column): bool
{
    $statement = $db->prepare('SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table_name AND COLUMN_NAME = :column_name');
    $statement->execute([
        'table_name' => $table,
        'column_name' => $column,
    ]);

    return (int) $statement->fetchColumn() > 0;
}

function fitsMotorSchemaEnsureColumn(PDO $db, string $table, string $column, string $definition): void
{
    if (!fitsMotorSchemaTableExists($db, $table) || fitsMotorSchemaColumnExists($db, $table, $column)) {
        return;
    }

    $db->exec(sprintf('ALTER TABLE `%s` ADD COLUMN `%s` %s', $table, $column, $definition));
}

function fitsMotorSchemaSlugify(string $value): string
{
    $slug = strtolower(trim($value));
    $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug) ?? $slug;
    $slug = trim($slug, '-');

    return $slug !== '' ? $slug : 'item';
}

function ensureDatabaseSchema(?PDO $db): void
{
    if (!$db instanceof PDO) {
        return;
    }

    $statements = [
        <<<SQL
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','customer') NOT NULL DEFAULT 'customer',
    avatar VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL,
        <<<SQL
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    category VARCHAR(50) NOT NULL,
    brand VARCHAR(50) NULL,
    compatibility TEXT NULL,
    description TEXT NULL,
    price INT NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image VARCHAR(255) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL,
        <<<SQL
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NULL,
    price_min INT NOT NULL,
    price_max INT NOT NULL,
    duration_minutes INT NOT NULL DEFAULT 60,
    icon VARCHAR(50) NULL DEFAULT 'wrench',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL,
        <<<SQL
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_cart_customer_product (customer_id, product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL,
        <<<SQL
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_code VARCHAR(20) NOT NULL UNIQUE,
    customer_id INT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    total_amount INT NOT NULL,
    payment_method ENUM('transfer','cod') NOT NULL DEFAULT 'cod',
    delivery_method ENUM('pickup','delivery') NOT NULL DEFAULT 'pickup',
    delivery_address TEXT NULL,
    payment_status ENUM('belum_bayar','sudah_bayar') NOT NULL DEFAULT 'belum_bayar',
    status ENUM('pending','diproses','siap_diambil','selesai','dibatalkan') NOT NULL DEFAULT 'pending',
    notes TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL,
        <<<SQL
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(150) NOT NULL,
    price INT NOT NULL,
    quantity INT NOT NULL,
    subtotal INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL,
        <<<SQL
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_code VARCHAR(20) NOT NULL UNIQUE,
    customer_id INT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_email VARCHAR(100) NULL,
    service_id INT NOT NULL,
    vehicle_brand VARCHAR(50) NULL,
    vehicle_type VARCHAR(50) NULL,
    vehicle_year VARCHAR(10) NULL,
    vehicle_plate VARCHAR(20) NULL,
    complaint TEXT NULL,
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    status ENUM('menunggu','dikonfirmasi','dalam_proses','selesai','dibatalkan') NOT NULL DEFAULT 'menunggu',
    admin_notes TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL,
        <<<SQL
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL,
        <<<SQL
CREATE TABLE IF NOT EXISTS vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    brand VARCHAR(50) NOT NULL,
    type VARCHAR(50) NOT NULL,
    year VARCHAR(10) NULL,
    plate_number VARCHAR(20) NULL,
    notes TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL,
        <<<SQL
CREATE TABLE IF NOT EXISTS addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    label VARCHAR(50) NULL,
    recipient_name VARCHAR(100) NULL,
    phone VARCHAR(20) NULL,
    province_id VARCHAR(20) NULL,
    province_name VARCHAR(100) NULL,
    city_id VARCHAR(20) NULL,
    city_name VARCHAR(100) NULL,
    district VARCHAR(100) NULL,
    address TEXT NULL,
    postal_code VARCHAR(10) NULL,
    is_primary TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL,
        <<<SQL
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(50) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL,
        <<<SQL
CREATE TABLE IF NOT EXISTS brands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    logo VARCHAR(255) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL,
        <<<SQL
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    key_name VARCHAR(100) NOT NULL UNIQUE,
    value TEXT NULL,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL,
        <<<SQL
CREATE TABLE IF NOT EXISTS product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL,
        <<<SQL
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    order_id INT NULL,
    rating TINYINT NOT NULL DEFAULT 5,
    comment TEXT NULL,
    image_path VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL,
        <<<SQL
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NULL,
    motor_type VARCHAR(100) NULL,
    rating TINYINT NOT NULL DEFAULT 5,
    comment TEXT NULL,
    avatar VARCHAR(255) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL,
        <<<SQL
CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NULL,
    image_path VARCHAR(255) NOT NULL,
    category VARCHAR(100) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL,
        <<<SQL
CREATE TABLE IF NOT EXISTS banners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NULL,
    subtitle TEXT NULL,
    image_path VARCHAR(255) NULL,
    link_url VARCHAR(255) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL,
    ];

    foreach ($statements as $statement) {
        $db->exec($statement);
    }

    $columns = [
        'users' => [
            'is_active' => 'TINYINT(1) NOT NULL DEFAULT 1',
            'updated_at' => 'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ],
        'products' => [
            'slug' => 'VARCHAR(255) NULL',
            'specifications' => 'TEXT NULL',
            'discount_price' => 'DECIMAL(12,2) NULL',
            'weight' => 'INT NOT NULL DEFAULT 1000',
            'badge' => 'VARCHAR(20) NOT NULL DEFAULT \'\'',
            'sold_count' => 'INT NOT NULL DEFAULT 0',
            'category_id' => 'INT NULL',
            'brand_id' => 'INT NULL',
            'updated_at' => 'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ],
        'orders' => [
            'order_number' => 'VARCHAR(50) NULL',
            'customer_email' => 'VARCHAR(100) NULL',
            'recipient_name' => 'VARCHAR(100) NULL',
            'recipient_phone' => 'VARCHAR(20) NULL',
            'province_id' => 'VARCHAR(20) NULL',
            'province_name' => 'VARCHAR(100) NULL',
            'city_id' => 'VARCHAR(20) NULL',
            'city_name' => 'VARCHAR(100) NULL',
            'district' => 'VARCHAR(100) NULL',
            'postal_code' => 'VARCHAR(10) NULL',
            'courier' => 'VARCHAR(50) NULL',
            'courier_service' => 'VARCHAR(50) NULL',
            'shipping_cost' => 'DECIMAL(12,2) NOT NULL DEFAULT 0',
            'estimated_days' => 'VARCHAR(20) NULL',
            'tracking_number' => 'VARCHAR(100) NULL',
            'tripay_reference' => 'VARCHAR(100) NULL',
            'payment_url' => 'TEXT NULL',
            'paid_at' => 'DATETIME NULL',
            'subtotal' => 'DECIMAL(12,2) NULL',
            'voucher_discount' => 'DECIMAL(12,2) NOT NULL DEFAULT 0',
            'total' => 'DECIMAL(12,2) NULL',
            'order_status' => 'VARCHAR(20) NULL',
            'admin_notes' => 'TEXT NULL',
            'updated_at' => 'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ],
        'order_items' => [
            'product_image' => 'VARCHAR(255) NULL',
        ],
        'bookings' => [
            'booking_number' => 'VARCHAR(50) NULL',
            'user_id' => 'INT NULL',
            'motor_brand' => 'VARCHAR(100) NULL',
            'motor_model' => 'VARCHAR(100) NULL',
            'services_json' => 'LONGTEXT NULL',
            'estimated_cost' => 'DECIMAL(12,2) NULL',
            'notes' => 'TEXT NULL',
            'updated_at' => 'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ],
        'contacts' => [
            'email' => 'VARCHAR(100) NULL',
            'topic' => 'VARCHAR(100) NULL',
        ],
    ];

    foreach ($columns as $table => $tableColumns) {
        foreach ($tableColumns as $column => $definition) {
            fitsMotorSchemaEnsureColumn($db, $table, $column, $definition);
        }
    }

    $db->exec("UPDATE users SET is_active = 1 WHERE is_active IS NULL");
    $db->exec("UPDATE products SET slug = LOWER(REPLACE(REPLACE(name, ' ', '-'), '/', '-')) WHERE slug IS NULL OR slug = ''");
    $db->exec("UPDATE orders SET order_number = order_code WHERE order_number IS NULL OR order_number = ''");
    $db->exec("UPDATE orders SET recipient_name = customer_name WHERE recipient_name IS NULL OR recipient_name = ''");
    $db->exec("UPDATE orders SET recipient_phone = customer_phone WHERE recipient_phone IS NULL OR recipient_phone = ''");
    $db->exec("UPDATE orders SET subtotal = total_amount WHERE subtotal IS NULL");
    $db->exec("UPDATE orders SET total = total_amount WHERE total IS NULL");
    $db->exec("UPDATE orders SET order_status = CASE status WHEN 'pending' THEN 'NEW' WHEN 'diproses' THEN 'PROCESSING' WHEN 'siap_diambil' THEN 'SHIPPED' WHEN 'selesai' THEN 'DELIVERED' WHEN 'dibatalkan' THEN 'CANCELLED' ELSE 'NEW' END WHERE order_status IS NULL OR order_status = ''");
    $db->exec("UPDATE bookings SET booking_number = booking_code WHERE booking_number IS NULL OR booking_number = ''");
    $db->exec("UPDATE bookings SET user_id = customer_id WHERE user_id IS NULL AND customer_id IS NOT NULL");
    $db->exec("UPDATE bookings SET motor_brand = vehicle_brand WHERE motor_brand IS NULL OR motor_brand = ''");
    $db->exec("UPDATE bookings SET motor_model = vehicle_type WHERE motor_model IS NULL OR motor_model = ''");
}

function ensureSeedData(?PDO $db): void
{
    if (!$db instanceof PDO) {
        return;
    }

    $passwordAdmin = password_hash('admin123', PASSWORD_DEFAULT);
    $passwordCustomer = password_hash('customer123', PASSWORD_DEFAULT);

    $users = [
        [
            'name' => 'Admin FITS MOTOR',
            'email' => 'admin@fitsmotor.com',
            'phone' => '081234567890',
            'password' => $passwordAdmin,
            'role' => 'admin',
        ],
        [
            'name' => 'Customer Demo',
            'email' => 'customer@fitsmotor.com',
            'phone' => '081234567891',
            'password' => $passwordCustomer,
            'role' => 'customer',
        ],
    ];

    foreach ($users as $user) {
        $existing = dbFetchOne($db, 'SELECT id FROM users WHERE email = :email LIMIT 1', ['email' => $user['email']]);

        if ($existing !== null) {
            dbExecute(
                $db,
                'UPDATE users SET name = :name, phone = :phone, password = :password, role = :role, is_active = 1 WHERE email = :email',
                $user
            );
            continue;
        }

        dbExecute(
            $db,
            'INSERT INTO users (name, email, phone, password, role, is_active) VALUES (:name, :email, :phone, :password, :role, 1)',
            $user
        );
    }

    $services = [
        ['name' => 'Servis Ringan', 'description' => 'Pengecekan rutin dan tune up ringan.', 'price_min' => 150000, 'price_max' => 250000, 'duration_minutes' => 60, 'icon' => 'settings_suggest'],
        ['name' => 'Ganti Oli', 'description' => 'Penggantian oli mesin dan pemeriksaan filter.', 'price_min' => 50000, 'price_max' => 150000, 'duration_minutes' => 45, 'icon' => 'oil_barrel'],
        ['name' => 'Ganti Ban', 'description' => 'Pemasangan ban dan balancing roda.', 'price_min' => 80000, 'price_max' => 250000, 'duration_minutes' => 60, 'icon' => 'tire_repair'],
        ['name' => 'Servis Kelistrikan', 'description' => 'Pemeriksaan aki, kabel, dan sistem lampu.', 'price_min' => 100000, 'price_max' => 400000, 'duration_minutes' => 90, 'icon' => 'bolt'],
        ['name' => 'Tune Up', 'description' => 'Tune up menyeluruh untuk performa mesin.', 'price_min' => 150000, 'price_max' => 350000, 'duration_minutes' => 90, 'icon' => 'build'],
        ['name' => 'Servis Rem', 'description' => 'Perawatan sistem pengereman depan dan belakang.', 'price_min' => 75000, 'price_max' => 200000, 'duration_minutes' => 60, 'icon' => 'precision_manufacturing'],
    ];

    foreach ($services as $service) {
        $existing = dbFetchOne($db, 'SELECT id FROM services WHERE name = :name LIMIT 1', ['name' => $service['name']]);

        if ($existing !== null) {
            dbExecute(
                $db,
                'UPDATE services SET description = :description, price_min = :price_min, price_max = :price_max, duration_minutes = :duration_minutes, icon = :icon, is_active = 1 WHERE name = :name',
                $service
            );
            continue;
        }

        dbExecute(
            $db,
            'INSERT INTO services (name, description, price_min, price_max, duration_minutes, icon, is_active) VALUES (:name, :description, :price_min, :price_max, :duration_minutes, :icon, 1)',
            $service
        );
    }

    $categories = [
        ['name' => 'Knalpot', 'icon' => 'local_fire_department'],
        ['name' => 'Suspensi', 'icon' => 'settings'],
        ['name' => 'Rem', 'icon' => 'disc_full'],
        ['name' => 'Aksesoris', 'icon' => 'construction'],
        ['name' => 'Lampu', 'icon' => 'lightbulb'],
        ['name' => 'Oli', 'icon' => 'oil_barrel'],
        ['name' => 'Kampas Rem', 'icon' => 'precision_manufacturing'],
        ['name' => 'Helm', 'icon' => 'shield'],
    ];

    foreach ($categories as $category) {
        $slug = fitsMotorSchemaSlugify($category['name']);
        $existing = dbFetchOne($db, 'SELECT id FROM categories WHERE slug = :slug LIMIT 1', ['slug' => $slug]);

        if ($existing !== null) {
            dbExecute(
                $db,
                'UPDATE categories SET name = :name, icon = :icon, is_active = 1 WHERE id = :id',
                [
                    'id' => $existing['id'],
                    'name' => $category['name'],
                    'icon' => $category['icon'],
                ]
            );
            continue;
        }

        dbExecute(
            $db,
            'INSERT INTO categories (name, slug, icon, is_active) VALUES (:name, :slug, :icon, 1)',
            [
                'name' => $category['name'],
                'slug' => $slug,
                'icon' => $category['icon'],
            ]
        );
    }

    $brands = [
        'Akrapovic',
        'Ohlins',
        'Brembo',
        'Carbon Tech',
        'Honda Genuine Parts',
        'Castrol',
        'X Series',
        'Carbon Pro',
        'Nissin',
        'Performance Series',
        'Black Edition',
    ];

    foreach ($brands as $brandName) {
        $existing = dbFetchOne($db, 'SELECT id FROM brands WHERE name = :name LIMIT 1', ['name' => $brandName]);

        if ($existing !== null) {
            dbExecute($db, 'UPDATE brands SET is_active = 1 WHERE id = :id', ['id' => $existing['id']]);
            continue;
        }

        dbExecute(
            $db,
            'INSERT INTO brands (name, logo, is_active) VALUES (:name, NULL, 1)',
            ['name' => $brandName]
        );
    }

    $settings = [
        'shop_name' => 'FITS MOTOR',
        'shop_phone' => WA_NUMBER,
        'shop_address' => 'Jl. Raya Motor No. 123, Banjarnegara, Jawa Tengah',
        'shop_email' => 'admin@fitsmotor.com',
        'shop_hours' => 'Senin - Sabtu: 08.00 - 17.00 WIB',
        'rajaongkir_origin_city_id' => RAJAONGKIR_ORIGIN_CITY,
        'wa_default_message' => WA_MESSAGE,
        'tripay_mode' => TRIPAY_BASE_URL,
    ];

    foreach ($settings as $keyName => $value) {
        $existing = dbFetchOne($db, 'SELECT id FROM settings WHERE key_name = :key_name LIMIT 1', ['key_name' => $keyName]);

        if ($existing !== null) {
            dbExecute($db, 'UPDATE settings SET value = :value WHERE key_name = :key_name', [
                'key_name' => $keyName,
                'value' => $value,
            ]);
            continue;
        }

        dbExecute($db, 'INSERT INTO settings (key_name, value) VALUES (:key_name, :value)', [
            'key_name' => $keyName,
            'value' => $value,
        ]);
    }

    $products = [
        ['name' => 'Knalpot Akrapovic Titanium', 'category' => 'Knalpot', 'brand' => 'Akrapovic', 'compatibility' => 'HONDA CBR', 'description' => 'Knalpot performa tinggi dengan material titanium premium.', 'price' => 12500000, 'stock' => 8, 'image' => null, 'weight' => 3500, 'badge' => 'TERLARIS'],
        ['name' => 'Shock Ohlins Racing Gold', 'category' => 'Suspensi', 'brand' => 'Ohlins', 'compatibility' => 'YAMAHA R25', 'description' => 'Suspensi balap premium untuk handling maksimal.', 'price' => 8200000, 'stock' => 5, 'image' => null, 'weight' => 4200, 'badge' => 'PROMO'],
        ['name' => 'Brembo Caliper Racing 4P', 'category' => 'Rem', 'brand' => 'Brembo', 'compatibility' => 'UNIVERSAL', 'description' => 'Kaliper rem performa tinggi untuk motor sport.', 'price' => 4800000, 'stock' => 9, 'image' => null, 'weight' => 2100, 'badge' => 'BARU'],
        ['name' => 'Engine Cover Carbon Fiber', 'category' => 'Aksesoris', 'brand' => 'Carbon Tech', 'compatibility' => 'KAWASAKI ZX25R', 'description' => 'Cover mesin karbon ringan dengan tampilan premium.', 'price' => 2450000, 'stock' => 4, 'image' => null, 'weight' => 900, 'badge' => ''],
        ['name' => 'Lampu LED Projector Honda Beat 2020-2024', 'category' => 'Lampu', 'brand' => 'Honda Genuine Parts', 'compatibility' => 'Honda Beat / Honda Vario / Honda PCX / Honda CBR', 'description' => 'Lampu projector LED dengan output terang dan fokus presisi.', 'price' => 228000, 'stock' => 48, 'image' => null, 'weight' => 1000, 'badge' => 'TERLARIS'],
        ['name' => 'Castrol Power1 Ultimate 10W-40 4T', 'category' => 'Oli', 'brand' => 'Castrol', 'compatibility' => 'Universal', 'description' => 'Oli mesin premium untuk performa optimal.', 'price' => 125000, 'stock' => 24, 'image' => null, 'weight' => 1000, 'badge' => 'PROMO'],
        ['name' => 'Brembo Sintered Brake Pads (Front)', 'category' => 'Kampas Rem', 'brand' => 'Brembo', 'compatibility' => 'Universal', 'description' => 'Kampas rem sintered premium untuk pengereman responsif.', 'price' => 675000, 'stock' => 12, 'image' => null, 'weight' => 500, 'badge' => 'BARU'],
        ['name' => 'Racing Exhaust System X-500', 'category' => 'Knalpot', 'brand' => 'X Series', 'compatibility' => 'Universal Fit', 'description' => 'Exhaust racing dengan karakter suara agresif.', 'price' => 4500000, 'stock' => 6, 'image' => null, 'weight' => 3300, 'badge' => ''],
        ['name' => 'Carbon Pro Helmet V2', 'category' => 'Helm', 'brand' => 'Carbon Pro', 'compatibility' => 'Large', 'description' => 'Helm full face carbon premium.', 'price' => 7200000, 'stock' => 3, 'image' => null, 'weight' => 1800, 'badge' => 'PROMO'],
        ['name' => 'Kampas Rem Depan High Performance Sintered', 'category' => 'Kampas Rem', 'brand' => 'Nissin', 'compatibility' => 'Honda Beat / Vario / PCX', 'description' => 'Kampas rem depan performa tinggi untuk pemakaian harian dan touring.', 'price' => 212500, 'stock' => 15, 'image' => null, 'weight' => 400, 'badge' => ''],
        ['name' => 'Full System Exhaust Carbon Performance Series', 'category' => 'Knalpot', 'brand' => 'Performance Series', 'compatibility' => 'Yamaha R15 / MT-15', 'description' => 'Knalpot full system karbon dengan aliran gas buang optimal.', 'price' => 2975000, 'stock' => 7, 'image' => null, 'weight' => 3100, 'badge' => 'TERLARIS'],
        ['name' => 'Full Face Racing Helmet - Black Edition', 'category' => 'Helm', 'brand' => 'Black Edition', 'compatibility' => 'Safety Gear', 'description' => 'Helm full face racing dengan desain agresif.', 'price' => 1500000, 'stock' => 11, 'image' => null, 'weight' => 1700, 'badge' => 'BARU'],
    ];

    foreach ($products as $product) {
        $existing = dbFetchOne($db, 'SELECT id FROM products WHERE name = :name LIMIT 1', ['name' => $product['name']]);
        $slug = fitsMotorSchemaSlugify($product['name']);
        $category = dbFetchOne($db, 'SELECT id FROM categories WHERE name = :name LIMIT 1', ['name' => $product['category']]);
        $brand = dbFetchOne($db, 'SELECT id FROM brands WHERE name = :name LIMIT 1', ['name' => $product['brand']]);

        $params = [
            'name' => $product['name'],
            'category' => $product['category'],
            'brand' => $product['brand'],
            'compatibility' => $product['compatibility'],
            'description' => $product['description'],
            'price' => $product['price'],
            'stock' => $product['stock'],
            'image' => $product['image'],
            'slug' => $slug,
            'weight' => $product['weight'],
            'badge' => $product['badge'],
            'category_id' => $category['id'] ?? null,
            'brand_id' => $brand['id'] ?? null,
        ];

        if ($existing !== null) {
            dbExecute(
                $db,
                'UPDATE products
                 SET category = :category, brand = :brand, compatibility = :compatibility, description = :description, price = :price, stock = :stock, image = :image, is_active = 1, slug = :slug, weight = :weight, badge = :badge, category_id = :category_id, brand_id = :brand_id
                 WHERE name = :name',
                $params
            );
            continue;
        }

        dbExecute(
            $db,
            'INSERT INTO products (name, category, brand, compatibility, description, price, stock, image, is_active, slug, weight, badge, category_id, brand_id)
             VALUES (:name, :category, :brand, :compatibility, :description, :price, :stock, :image, 1, :slug, :weight, :badge, :category_id, :brand_id)',
            $params
        );
    }

    $customer = dbFetchOne($db, 'SELECT id, name, phone FROM users WHERE email = :email LIMIT 1', ['email' => 'customer@fitsmotor.com']);

    if ($customer !== null) {
        $existingAddress = dbFetchOne($db, 'SELECT id FROM addresses WHERE user_id = :user_id AND label = :label LIMIT 1', [
            'user_id' => $customer['id'],
            'label' => 'Rumah',
        ]);

        $addressPayload = [
            'user_id' => $customer['id'],
            'label' => 'Rumah',
            'recipient_name' => $customer['name'],
            'phone' => $customer['phone'],
            'province_id' => 'jateng',
            'province_name' => 'Jawa Tengah',
            'city_id' => 'banjarnegara',
            'city_name' => 'Banjarnegara',
            'district' => 'Banjarnegara',
            'address' => 'Jl. Raya Motor No. 123',
            'postal_code' => '53415',
            'is_primary' => 1,
        ];

        if ($existingAddress !== null) {
            dbExecute(
                $db,
                'UPDATE addresses
                 SET recipient_name = :recipient_name, phone = :phone, province_id = :province_id, province_name = :province_name, city_id = :city_id, city_name = :city_name, district = :district, address = :address, postal_code = :postal_code, is_primary = :is_primary
                 WHERE id = :id',
                [
                    'id' => $existingAddress['id'],
                    'recipient_name' => $addressPayload['recipient_name'],
                    'phone' => $addressPayload['phone'],
                    'province_id' => $addressPayload['province_id'],
                    'province_name' => $addressPayload['province_name'],
                    'city_id' => $addressPayload['city_id'],
                    'city_name' => $addressPayload['city_name'],
                    'district' => $addressPayload['district'],
                    'address' => $addressPayload['address'],
                    'postal_code' => $addressPayload['postal_code'],
                    'is_primary' => $addressPayload['is_primary'],
                ]
            );
        } else {
            dbExecute(
                $db,
                'INSERT INTO addresses (user_id, label, recipient_name, phone, province_id, province_name, city_id, city_name, district, address, postal_code, is_primary)
                 VALUES (:user_id, :label, :recipient_name, :phone, :province_id, :province_name, :city_id, :city_name, :district, :address, :postal_code, :is_primary)',
                $addressPayload
            );
        }
    }
}
