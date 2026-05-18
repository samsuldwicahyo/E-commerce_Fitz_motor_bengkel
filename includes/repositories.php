<?php
declare(strict_types=1);

function dbFetchAll(PDO $db, string $sql, array $params = []): array
{
    $statement = $db->prepare($sql);
    $statement->execute($params);

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function dbFetchOne(PDO $db, string $sql, array $params = []): ?array
{
    $statement = $db->prepare($sql);
    $statement->execute($params);
    $row = $statement->fetch(PDO::FETCH_ASSOC);

    return is_array($row) ? $row : null;
}

function dbExecute(PDO $db, string $sql, array $params = []): bool
{
    $statement = $db->prepare($sql);

    return $statement->execute($params);
}

function findUserById(?PDO $db, int $id): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    return dbFetchOne($db, 'SELECT * FROM users WHERE id = :id LIMIT 1', ['id' => $id]);
}

function findUserByEmail(?PDO $db, string $email): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    return dbFetchOne($db, 'SELECT * FROM users WHERE email = :email LIMIT 1', ['email' => $email]);
}

function createUser(?PDO $db, array $payload): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    dbExecute(
        $db,
        'INSERT INTO users (name, email, phone, password, role) VALUES (:name, :email, :phone, :password, :role)',
        [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'phone' => $payload['phone'],
            'password' => $payload['password'],
            'role' => $payload['role'] ?? 'customer',
        ]
    );

    return findUserById($db, (int) $db->lastInsertId());
}

function fetchActiveProducts(?PDO $db): array
{
    if (!$db instanceof PDO) {
        return [];
    }

    return dbFetchAll($db, 'SELECT * FROM products WHERE is_active = 1 ORDER BY id ASC');
}

function fetchStorefrontProducts(?PDO $db, array $filters = []): array
{
    if (!$db instanceof PDO) {
        return [];
    }

    $sql = 'SELECT products.*, categories.name AS category_name, categories.slug AS category_slug, brands.name AS brand_name
            FROM products
            LEFT JOIN categories ON categories.id = products.category_id
            LEFT JOIN brands ON brands.id = products.brand_id
            WHERE products.is_active = 1';
    $params = [];

    $search = trim((string) ($filters['search'] ?? ''));
    $category = trim((string) ($filters['category'] ?? ''));
    $brand = trim((string) ($filters['brand'] ?? ''));

    if ($search !== '') {
        $sql .= ' AND (products.name LIKE :search OR COALESCE(products.description, "") LIKE :search OR COALESCE(products.compatibility, "") LIKE :search)';
        $params['search'] = '%' . $search . '%';
    }

    if ($category !== '') {
        $sql .= ' AND (categories.slug = :category_slug OR categories.name = :category_name)';
        $params['category_slug'] = $category;
        $params['category_name'] = $category;
    }

    if ($brand !== '') {
        $sql .= ' AND brands.name = :brand_name';
        $params['brand_name'] = $brand;
    }

    $sql .= ' ORDER BY products.sold_count DESC, products.created_at DESC, products.id DESC';

    return dbFetchAll($db, $sql, $params);
}

function findProductById(?PDO $db, int $productId): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    return dbFetchOne($db, 'SELECT * FROM products WHERE id = :id LIMIT 1', ['id' => $productId]);
}

function findProductBySlug(?PDO $db, string $slug): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    return dbFetchOne(
        $db,
        'SELECT products.*, categories.name AS category_name, categories.slug AS category_slug, brands.name AS brand_name
         FROM products
         LEFT JOIN categories ON categories.id = products.category_id
         LEFT JOIN brands ON brands.id = products.brand_id
         WHERE products.slug = :slug AND products.is_active = 1
         LIMIT 1',
        ['slug' => trim($slug)]
    );
}

function fetchRelatedProducts(?PDO $db, array $product, int $limit = 4): array
{
    if (!$db instanceof PDO) {
        return [];
    }

    $limit = max(1, $limit);
    $productId = (int) ($product['id'] ?? 0);
    $categoryId = (int) ($product['category_id'] ?? 0);

    if ($categoryId > 0) {
        return dbFetchAll(
            $db,
            'SELECT products.*, categories.name AS category_name, categories.slug AS category_slug, brands.name AS brand_name
             FROM products
             LEFT JOIN categories ON categories.id = products.category_id
             LEFT JOIN brands ON brands.id = products.brand_id
             WHERE products.is_active = 1 AND products.id <> :product_id AND products.category_id = :category_id
             ORDER BY products.sold_count DESC, products.created_at DESC
             LIMIT ' . $limit,
            [
                'product_id' => $productId,
                'category_id' => $categoryId,
            ]
        );
    }

    return dbFetchAll(
        $db,
        'SELECT products.*, categories.name AS category_name, categories.slug AS category_slug, brands.name AS brand_name
         FROM products
         LEFT JOIN categories ON categories.id = products.category_id
         LEFT JOIN brands ON brands.id = products.brand_id
         WHERE products.is_active = 1 AND products.id <> :product_id
         ORDER BY products.sold_count DESC, products.created_at DESC
         LIMIT ' . $limit,
        ['product_id' => $productId]
    );
}

function fetchProductImages(?PDO $db, int $productId): array
{
    if (!$db instanceof PDO) {
        return [];
    }

    return dbFetchAll($db, 'SELECT * FROM product_images WHERE product_id = :product_id ORDER BY is_primary DESC, sort_order ASC, id ASC', ['product_id' => $productId]);
}

function findCategoryById(?PDO $db, int $categoryId): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    return dbFetchOne($db, 'SELECT * FROM categories WHERE id = :id LIMIT 1', ['id' => $categoryId]);
}

function findCategoryByName(?PDO $db, string $name): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    return dbFetchOne($db, 'SELECT * FROM categories WHERE name = :name LIMIT 1', ['name' => trim($name)]);
}

function findBrandById(?PDO $db, int $brandId): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    return dbFetchOne($db, 'SELECT * FROM brands WHERE id = :id LIMIT 1', ['id' => $brandId]);
}

function findBrandByName(?PDO $db, string $name): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    return dbFetchOne($db, 'SELECT * FROM brands WHERE name = :name LIMIT 1', ['name' => trim($name)]);
}

function resolveProductByTemplateName(?PDO $db, string $templateName): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    $name = trim($templateName);

    if ($name === '') {
        return null;
    }

    $aliases = [
        'Keranjang Belanja' => 'Castrol Power1 Ultimate 10W-40 4T',
        'Lampu LED Projector' => 'Lampu LED Projector Honda Beat 2020-2024',
        'Knalpot Akrapovic Titanium' => 'Knalpot Akrapovic Titanium',
        'Shock Ohlins Racing Gold' => 'Shock Ohlins Racing Gold',
        'Brembo Caliper Racing 4P' => 'Brembo Caliper Racing 4P',
        'Engine Cover Carbon Fiber' => 'Engine Cover Carbon Fiber',
        'Kampas Rem Depan High Performance Sintered' => 'Kampas Rem Depan High Performance Sintered',
        'Full System Exhaust Carbon Performance Series' => 'Full System Exhaust Carbon Performance Series',
        'Full Face Racing Helmet - Black Edition' => 'Full Face Racing Helmet - Black Edition',
        'Racing Exhaust System X-500' => 'Racing Exhaust System X-500',
        'Carbon Pro Helmet V2' => 'Carbon Pro Helmet V2',
        'Brembo Sintered Brake Pads (Front)' => 'Brembo Sintered Brake Pads (Front)',
        'Castrol Power1 Ultimate 10W-40 4T' => 'Castrol Power1 Ultimate 10W-40 4T',
    ];

    $lookup = $aliases[$name] ?? $name;

    $product = dbFetchOne($db, 'SELECT * FROM products WHERE name = :name AND is_active = 1 LIMIT 1', ['name' => $lookup]);

    if ($product !== null) {
        return $product;
    }

    return dbFetchOne($db, 'SELECT * FROM products WHERE name LIKE :name AND is_active = 1 LIMIT 1', ['name' => '%' . $lookup . '%']);
}

function fetchActiveServices(?PDO $db): array
{
    if (!$db instanceof PDO) {
        return [];
    }

    return dbFetchAll($db, 'SELECT * FROM services WHERE is_active = 1 ORDER BY id ASC');
}

function resolveServiceByName(?PDO $db, string $name): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    $aliases = [
        'Servis Ringan' => 'Servis Ringan',
        'Ganti Oli' => 'Ganti Oli',
        'Ganti Ban' => 'Ganti Ban',
        'Servis Kelistrikan' => 'Servis Kelistrikan',
    ];

    $lookup = $aliases[trim($name)] ?? trim($name);

    return dbFetchOne($db, 'SELECT * FROM services WHERE name = :name AND is_active = 1 LIMIT 1', ['name' => $lookup]);
}

function addCartItem(?PDO $db, int $customerId, int $productId, int $quantity): bool
{
    if (!$db instanceof PDO) {
        return false;
    }

    $quantity = max(1, $quantity);
    $existing = dbFetchOne($db, 'SELECT id, quantity FROM cart WHERE customer_id = :customer_id AND product_id = :product_id LIMIT 1', [
        'customer_id' => $customerId,
        'product_id' => $productId,
    ]);

    if ($existing !== null) {
        return dbExecute($db, 'UPDATE cart SET quantity = :quantity WHERE id = :id', [
            'quantity' => (int) $existing['quantity'] + $quantity,
            'id' => $existing['id'],
        ]);
    }

    return dbExecute($db, 'INSERT INTO cart (customer_id, product_id, quantity) VALUES (:customer_id, :product_id, :quantity)', [
        'customer_id' => $customerId,
        'product_id' => $productId,
        'quantity' => $quantity,
    ]);
}

function updateCartItem(?PDO $db, int $customerId, int $productId, int $quantity): bool
{
    if (!$db instanceof PDO) {
        return false;
    }

    if ($quantity <= 0) {
        return removeCartItem($db, $customerId, $productId);
    }

    return dbExecute($db, 'UPDATE cart SET quantity = :quantity WHERE customer_id = :customer_id AND product_id = :product_id', [
        'quantity' => $quantity,
        'customer_id' => $customerId,
        'product_id' => $productId,
    ]);
}

function removeCartItem(?PDO $db, int $customerId, int $productId): bool
{
    if (!$db instanceof PDO) {
        return false;
    }

    return dbExecute($db, 'DELETE FROM cart WHERE customer_id = :customer_id AND product_id = :product_id', [
        'customer_id' => $customerId,
        'product_id' => $productId,
    ]);
}

function fetchCartItems(?PDO $db, int $customerId): array
{
    if (!$db instanceof PDO) {
        return [];
    }

    return dbFetchAll(
        $db,
        'SELECT cart.product_id, cart.quantity, products.name, products.slug, products.price, products.discount_price, products.image, products.stock, products.weight, products.category, products.brand
         FROM cart
         INNER JOIN products ON products.id = cart.product_id
         WHERE cart.customer_id = :customer_id
         ORDER BY cart.added_at DESC',
        ['customer_id' => $customerId]
    );
}

function fetchCartSummary(?PDO $db, int $customerId): array
{
    if (!$db instanceof PDO) {
        return [
            'item_count' => 0,
            'total_quantity' => 0,
            'subtotal' => 0,
            'total_weight' => 0,
        ];
    }

    $items = fetchCartItems($db, $customerId);
    $summary = [
        'item_count' => count($items),
        'total_quantity' => 0,
        'subtotal' => 0,
        'total_weight' => 0,
    ];

    foreach ($items as $item) {
        $quantity = (int) ($item['quantity'] ?? 0);
        $price = (float) (($item['discount_price'] ?? null) !== null ? $item['discount_price'] : ($item['price'] ?? 0));
        $weight = max(1, (int) ($item['weight'] ?? 1000));

        $summary['total_quantity'] += $quantity;
        $summary['subtotal'] += (int) round($price * $quantity);
        $summary['total_weight'] += $weight * $quantity;
    }

    return $summary;
}

function countCartItems(?PDO $db, int $customerId): int
{
    if (!$db instanceof PDO) {
        return 0;
    }

    $row = dbFetchOne($db, 'SELECT COALESCE(SUM(quantity), 0) AS total_items FROM cart WHERE customer_id = :customer_id', [
        'customer_id' => $customerId,
    ]);

    return (int) ($row['total_items'] ?? 0);
}

function clearCart(?PDO $db, int $customerId): bool
{
    if (!$db instanceof PDO) {
        return false;
    }

    return dbExecute($db, 'DELETE FROM cart WHERE customer_id = :customer_id', ['customer_id' => $customerId]);
}

function createOrderFromCart(?PDO $db, array $customer, array $payload = []): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    $customerId = (int) $customer['id'];
    $cartItems = fetchCartItems($db, $customerId);

    if ($cartItems === []) {
        return null;
    }

    $subtotal = 0;

    foreach ($cartItems as $item) {
        $unitPrice = (float) (($item['discount_price'] ?? null) !== null ? $item['discount_price'] : ($item['price'] ?? 0));
        $subtotal += (int) round($unitPrice * (int) $item['quantity']);
    }

    $orderCode = generateReferenceCode('ORD');
    $paymentMethod = in_array(($payload['payment_method'] ?? 'transfer'), ['transfer', 'cod'], true) ? $payload['payment_method'] : 'transfer';
    $deliveryMethod = in_array(($payload['delivery_method'] ?? 'delivery'), ['pickup', 'delivery'], true) ? $payload['delivery_method'] : 'delivery';
    $deliveryAddress = trim((string) ($payload['delivery_address'] ?? ''));
    $primaryAddress = dbFetchOne($db, 'SELECT * FROM addresses WHERE user_id = :user_id AND is_primary = 1 ORDER BY id DESC LIMIT 1', ['user_id' => $customerId]);
    $recipientName = trim((string) ($payload['recipient_name'] ?? ($primaryAddress['recipient_name'] ?? $customer['name'])));
    $recipientPhone = normalizePhoneNumber((string) ($payload['recipient_phone'] ?? ($primaryAddress['phone'] ?? $customer['phone'])));
    $provinceId = (string) ($payload['province_id'] ?? ($primaryAddress['province_id'] ?? ''));
    $provinceName = trim((string) ($payload['province_name'] ?? ($primaryAddress['province_name'] ?? '')));
    $cityId = (string) ($payload['city_id'] ?? ($primaryAddress['city_id'] ?? ''));
    $cityName = trim((string) ($payload['city_name'] ?? ($primaryAddress['city_name'] ?? '')));
    $district = trim((string) ($payload['district'] ?? ($primaryAddress['district'] ?? '')));
    $postalCode = trim((string) ($payload['postal_code'] ?? ($primaryAddress['postal_code'] ?? '')));
    $courier = trim((string) ($payload['courier'] ?? ''));
    $courierService = trim((string) ($payload['courier_service'] ?? ''));
    $shippingCost = max(0, (float) ($payload['shipping_cost'] ?? 0));
    $estimatedDays = trim((string) ($payload['estimated_days'] ?? ''));
    $totalAmount = (int) round($subtotal + $shippingCost - max(0, (float) ($payload['voucher_discount'] ?? 0)));
    $voucherDiscount = max(0, (float) ($payload['voucher_discount'] ?? 0));

    $db->beginTransaction();

    try {
        dbExecute(
            $db,
            'INSERT INTO orders (order_code, order_number, customer_id, customer_name, customer_email, customer_phone, recipient_name, recipient_phone, province_id, province_name, city_id, city_name, district, delivery_address, postal_code, courier, courier_service, shipping_cost, estimated_days, total_amount, subtotal, voucher_discount, total, payment_method, delivery_method, payment_status, status, order_status, notes)
             VALUES (:order_code, :order_number, :customer_id, :customer_name, :customer_email, :customer_phone, :recipient_name, :recipient_phone, :province_id, :province_name, :city_id, :city_name, :district, :delivery_address, :postal_code, :courier, :courier_service, :shipping_cost, :estimated_days, :total_amount, :subtotal, :voucher_discount, :total, :payment_method, :delivery_method, :payment_status, :status, :order_status, :notes)',
            [
                'order_code' => $orderCode,
                'order_number' => $orderCode,
                'customer_id' => $customerId,
                'customer_name' => $customer['name'],
                'customer_email' => $customer['email'] ?? null,
                'customer_phone' => $customer['phone'],
                'recipient_name' => $recipientName,
                'recipient_phone' => $recipientPhone,
                'province_id' => $provinceId !== '' ? $provinceId : null,
                'province_name' => $provinceName !== '' ? $provinceName : null,
                'city_id' => $cityId !== '' ? $cityId : null,
                'city_name' => $cityName !== '' ? $cityName : null,
                'district' => $district !== '' ? $district : null,
                'postal_code' => $postalCode !== '' ? $postalCode : null,
                'courier' => $courier !== '' ? strtoupper($courier) : null,
                'courier_service' => $courierService !== '' ? strtoupper($courierService) : null,
                'shipping_cost' => $shippingCost,
                'estimated_days' => $estimatedDays !== '' ? $estimatedDays : null,
                'total_amount' => $totalAmount,
                'subtotal' => $subtotal,
                'voucher_discount' => $voucherDiscount,
                'total' => $totalAmount,
                'payment_method' => $paymentMethod,
                'delivery_method' => $deliveryMethod,
                'delivery_address' => $deliveryAddress !== '' ? $deliveryAddress : null,
                'payment_status' => $paymentMethod === 'transfer' ? 'belum_bayar' : 'sudah_bayar',
                'status' => 'pending',
                'order_status' => $paymentMethod === 'transfer' ? 'NEW' : 'PROCESSING',
                'notes' => trim((string) ($payload['notes'] ?? '')),
            ]
        );

        $orderId = (int) $db->lastInsertId();

        foreach ($cartItems as $item) {
            $unitPrice = (float) (($item['discount_price'] ?? null) !== null ? $item['discount_price'] : ($item['price'] ?? 0));
            $subtotalItem = (int) round($unitPrice * (int) $item['quantity']);

            dbExecute(
                $db,
                'INSERT INTO order_items (order_id, product_id, product_name, product_image, price, quantity, subtotal)
                 VALUES (:order_id, :product_id, :product_name, :product_image, :price, :quantity, :subtotal)',
                [
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'product_image' => $item['image'] ?? null,
                    'price' => $unitPrice,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotalItem,
                ]
            );

            dbExecute(
                $db,
                'UPDATE products SET stock = GREATEST(stock - :quantity, 0) WHERE id = :id',
                ['quantity' => $item['quantity'], 'id' => $item['product_id']]
            );
        }

        clearCart($db, $customerId);
        $db->commit();

        return dbFetchOne($db, 'SELECT * FROM orders WHERE id = :id LIMIT 1', ['id' => $orderId]);
    } catch (Throwable $exception) {
        $db->rollBack();
        return null;
    }
}

function rollbackFailedCheckoutOrder(?PDO $db, int $orderId): bool
{
    if (!$db instanceof PDO || $orderId <= 0) {
        return false;
    }

    $order = dbFetchOne($db, 'SELECT id, customer_id FROM orders WHERE id = :id LIMIT 1', ['id' => $orderId]);

    if ($order === null) {
        return false;
    }

    $items = dbFetchAll($db, 'SELECT product_id, quantity FROM order_items WHERE order_id = :order_id ORDER BY id ASC', ['order_id' => $orderId]);
    $customerId = (int) ($order['customer_id'] ?? 0);

    $db->beginTransaction();

    try {
        foreach ($items as $item) {
            $productId = (int) ($item['product_id'] ?? 0);
            $quantity = max(1, (int) ($item['quantity'] ?? 0));

            if ($productId <= 0) {
                continue;
            }

            dbExecute(
                $db,
                'UPDATE products SET stock = stock + :quantity WHERE id = :id',
                [
                    'quantity' => $quantity,
                    'id' => $productId,
                ]
            );

            if ($customerId > 0) {
                addCartItem($db, $customerId, $productId, $quantity);
            }
        }

        dbExecute($db, 'DELETE FROM order_items WHERE order_id = :order_id', ['order_id' => $orderId]);
        dbExecute($db, 'DELETE FROM orders WHERE id = :id', ['id' => $orderId]);
        $db->commit();

        return true;
    } catch (Throwable $exception) {
        $db->rollBack();
        return false;
    }
}

function createBooking(?PDO $db, array $customer, array $payload): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    $service = resolveServiceByName($db, (string) ($payload['service_name'] ?? ''));

    if ($service === null) {
        return null;
    }

    $bookingCode = generateReferenceCode('BK');
    $estimatedCost = (float) ($payload['estimated_cost'] ?? ($service['price_min'] ?? 0));
    $servicesJson = json_encode([
        [
            'name' => $service['name'],
            'price_min' => $service['price_min'] ?? 0,
            'price_max' => $service['price_max'] ?? 0,
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    dbExecute(
        $db,
        'INSERT INTO bookings (booking_code, booking_number, customer_id, user_id, customer_name, customer_phone, customer_email, service_id, vehicle_brand, vehicle_type, vehicle_year, vehicle_plate, motor_brand, motor_model, complaint, services_json, booking_date, booking_time, estimated_cost, status, notes)
         VALUES (:booking_code, :booking_number, :customer_id, :user_id, :customer_name, :customer_phone, :customer_email, :service_id, :vehicle_brand, :vehicle_type, :vehicle_year, :vehicle_plate, :motor_brand, :motor_model, :complaint, :services_json, :booking_date, :booking_time, :estimated_cost, :status, :notes)',
        [
            'booking_code' => $bookingCode,
            'booking_number' => $bookingCode,
            'customer_id' => $customer['id'],
            'user_id' => $customer['id'],
            'customer_name' => $customer['name'],
            'customer_phone' => $customer['phone'],
            'customer_email' => $customer['email'],
            'service_id' => $service['id'],
            'vehicle_brand' => $payload['vehicle_brand'] ?? '',
            'vehicle_type' => $payload['vehicle_type'] ?? '',
            'vehicle_year' => $payload['vehicle_year'] ?? '',
            'vehicle_plate' => $payload['vehicle_plate'] ?? '',
            'motor_brand' => $payload['vehicle_brand'] ?? '',
            'motor_model' => $payload['vehicle_type'] ?? '',
            'complaint' => $payload['complaint'] ?? '',
            'services_json' => $servicesJson,
            'booking_date' => $payload['booking_date'],
            'booking_time' => $payload['booking_time'],
            'estimated_cost' => $estimatedCost > 0 ? $estimatedCost : null,
            'status' => 'menunggu',
            'notes' => trim((string) ($payload['notes'] ?? '')),
        ]
    );
    $bookingId = (int) $db->lastInsertId();

    if (trim((string) ($payload['vehicle_brand'] ?? '')) !== '' && trim((string) ($payload['vehicle_type'] ?? '')) !== '') {
        $existingVehicle = dbFetchOne(
            $db,
            'SELECT id FROM vehicles WHERE customer_id = :customer_id AND brand = :brand AND type = :type AND COALESCE(plate_number, "") = COALESCE(:plate_number, "") LIMIT 1',
            [
                'customer_id' => $customer['id'],
                'brand' => trim((string) ($payload['vehicle_brand'] ?? '')),
                'type' => trim((string) ($payload['vehicle_type'] ?? '')),
                'plate_number' => trim((string) ($payload['vehicle_plate'] ?? '')),
            ]
        );

        if ($existingVehicle === null) {
            dbExecute(
                $db,
                'INSERT INTO vehicles (customer_id, brand, type, year, plate_number, notes) VALUES (:customer_id, :brand, :type, :year, :plate_number, :notes)',
                [
                    'customer_id' => $customer['id'],
                    'brand' => trim((string) ($payload['vehicle_brand'] ?? '')),
                    'type' => trim((string) ($payload['vehicle_type'] ?? '')),
                    'year' => trim((string) ($payload['vehicle_year'] ?? '')),
                    'plate_number' => trim((string) ($payload['vehicle_plate'] ?? '')),
                    'notes' => trim((string) ($payload['complaint'] ?? '')),
                ]
            );
        }
    }

    sendBookingWhatsApp(
        normalizePhoneNumber((string) $customer['phone']),
        $bookingCode,
        [
            'customer_name' => $customer['name'],
            'motor_brand' => $payload['vehicle_brand'] ?? '',
            'motor_model' => $payload['vehicle_type'] ?? '',
            'booking_date' => $payload['booking_date'],
            'booking_time' => $payload['booking_time'],
        ]
    );

    return dbFetchOne($db, 'SELECT * FROM bookings WHERE id = :id LIMIT 1', ['id' => $bookingId]);
}

function fetchCustomerOrders(?PDO $db, int $customerId): array
{
    if (!$db instanceof PDO) {
        return [];
    }

    return dbFetchAll($db, 'SELECT * FROM orders WHERE customer_id = :customer_id ORDER BY created_at DESC', ['customer_id' => $customerId]);
}

function fetchCustomerBookings(?PDO $db, int $customerId): array
{
    if (!$db instanceof PDO) {
        return [];
    }

    return dbFetchAll($db, 'SELECT bookings.*, services.name AS service_name FROM bookings INNER JOIN services ON services.id = bookings.service_id WHERE customer_id = :customer_id ORDER BY booking_date DESC, booking_time DESC', ['customer_id' => $customerId]);
}

function fetchCustomerDashboardMetrics(?PDO $db, int $customerId): array
{
    if (!$db instanceof PDO) {
        return [
            'order_count' => 0,
            'active_booking_count' => 0,
            'total_spending' => 0,
        ];
    }

    $orderStats = dbFetchOne($db, 'SELECT COUNT(*) AS order_count, COALESCE(SUM(total_amount), 0) AS total_spending FROM orders WHERE customer_id = :customer_id', ['customer_id' => $customerId]);
    $bookingStats = dbFetchOne($db, "SELECT COUNT(*) AS active_booking_count FROM bookings WHERE customer_id = :customer_id AND status IN ('menunggu','dikonfirmasi','dalam_proses')", ['customer_id' => $customerId]);

    return [
        'order_count' => (int) ($orderStats['order_count'] ?? 0),
        'active_booking_count' => (int) ($bookingStats['active_booking_count'] ?? 0),
        'total_spending' => (int) ($orderStats['total_spending'] ?? 0),
    ];
}

function fetchLatestCustomerBooking(?PDO $db, int $customerId): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    return dbFetchOne($db, 'SELECT bookings.*, services.name AS service_name FROM bookings INNER JOIN services ON services.id = bookings.service_id WHERE customer_id = :customer_id ORDER BY booking_date DESC, booking_time DESC LIMIT 1', ['customer_id' => $customerId]);
}

function fetchLatestCustomerOrder(?PDO $db, int $customerId): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    return dbFetchOne($db, 'SELECT * FROM orders WHERE customer_id = :customer_id ORDER BY created_at DESC LIMIT 1', ['customer_id' => $customerId]);
}

function findOrderByCode(?PDO $db, string $orderCode): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    return dbFetchOne($db, 'SELECT * FROM orders WHERE order_code = :order_code OR order_number = :order_number LIMIT 1', [
        'order_code' => trim($orderCode),
        'order_number' => trim($orderCode),
    ]);
}

function findOrderById(?PDO $db, int $orderId): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    return dbFetchOne($db, 'SELECT * FROM orders WHERE id = :id LIMIT 1', ['id' => $orderId]);
}

function fetchOrderItems(?PDO $db, int $orderId): array
{
    if (!$db instanceof PDO) {
        return [];
    }

    return dbFetchAll($db, 'SELECT * FROM order_items WHERE order_id = :order_id ORDER BY id ASC', ['order_id' => $orderId]);
}

function searchTrackableOrder(?PDO $db, string $query): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    $query = trim($query);

    if ($query === '') {
        return null;
    }

    $normalizedCode = ltrim($query, '#');
    $normalizedPhone = normalizePhoneNumber($query);

    return dbFetchOne(
        $db,
        'SELECT * FROM orders WHERE order_code = :order_code OR order_number = :order_number OR customer_phone = :customer_phone ORDER BY created_at DESC LIMIT 1',
        [
            'order_code' => $normalizedCode,
            'order_number' => $normalizedCode,
            'customer_phone' => $normalizedPhone,
        ]
    );
}

function updateOrderStatus(?PDO $db, string $orderCode, string $status, ?string $paymentStatus = null, ?string $notes = null, ?string $trackingNumber = null, ?string $adminNotes = null): bool
{
    if (!$db instanceof PDO) {
        return false;
    }

    $allowedStatus = ['pending', 'diproses', 'siap_diambil', 'selesai', 'dibatalkan'];
    $allowedPaymentStatus = ['belum_bayar', 'sudah_bayar'];
    $status = in_array($status, $allowedStatus, true) ? $status : 'pending';
    $paymentStatus = in_array((string) $paymentStatus, $allowedPaymentStatus, true) ? $paymentStatus : null;

    $order = findOrderByCode($db, $orderCode);

    if ($order === null) {
        return false;
    }

    $updated = dbExecute(
        $db,
        'UPDATE orders
         SET status = :status, order_status = :order_status, payment_status = :payment_status, paid_at = :paid_at, notes = :notes, tracking_number = :tracking_number, admin_notes = :admin_notes
         WHERE order_code = :order_code OR order_number = :order_number',
        [
            'status' => $status,
            'order_status' => match ($status) {
                'pending' => 'NEW',
                'diproses' => 'PROCESSING',
                'siap_diambil' => 'SHIPPED',
                'selesai' => 'DELIVERED',
                'dibatalkan' => 'CANCELLED',
                default => 'NEW',
            },
            'payment_status' => $paymentStatus ?? $order['payment_status'],
            'paid_at' => ($paymentStatus ?? $order['payment_status']) === 'sudah_bayar' ? date('Y-m-d H:i:s') : null,
            'notes' => $notes !== null && trim($notes) !== '' ? trim($notes) : $order['notes'],
            'tracking_number' => $trackingNumber !== null && trim($trackingNumber) !== '' ? trim($trackingNumber) : ($order['tracking_number'] ?? null),
            'admin_notes' => $adminNotes !== null && trim($adminNotes) !== '' ? trim($adminNotes) : ($order['admin_notes'] ?? null),
            'order_code' => $orderCode,
            'order_number' => $orderCode,
        ]
    );

    if ($updated) {
        sendOrderStatusWhatsApp(
            normalizePhoneNumber((string) ($order['customer_phone'] ?? '')),
            (string) ($order['order_number'] ?? $order['order_code']),
            $status,
            $trackingNumber !== null && trim($trackingNumber) !== '' ? trim($trackingNumber) : (string) ($order['tracking_number'] ?? '')
        );
    }

    return $updated;
}

function findBookingByCode(?PDO $db, string $bookingCode): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    return dbFetchOne(
        $db,
        'SELECT bookings.*, services.name AS service_name, services.price_min, services.price_max
         FROM bookings
         INNER JOIN services ON services.id = bookings.service_id
         WHERE bookings.booking_code = :booking_code
         LIMIT 1',
        ['booking_code' => trim($bookingCode)]
    );
}

function findBookingById(?PDO $db, int $bookingId): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    return dbFetchOne(
        $db,
        'SELECT bookings.*, services.name AS service_name, services.price_min, services.price_max
         FROM bookings
         INNER JOIN services ON services.id = bookings.service_id
         WHERE bookings.id = :id
         LIMIT 1',
        ['id' => $bookingId]
    );
}

function updateBookingStatus(?PDO $db, string $bookingCode, string $status, ?string $adminNotes = null): bool
{
    if (!$db instanceof PDO) {
        return false;
    }

    $allowedStatus = ['menunggu', 'dikonfirmasi', 'dalam_proses', 'selesai', 'dibatalkan'];
    $status = in_array($status, $allowedStatus, true) ? $status : 'menunggu';
    $booking = findBookingByCode($db, $bookingCode);

    if ($booking === null) {
        return false;
    }

    $updated = dbExecute(
        $db,
        'UPDATE bookings SET status = :status, admin_notes = :admin_notes WHERE booking_code = :booking_code',
        [
            'status' => $status,
            'admin_notes' => $adminNotes !== null && trim($adminNotes) !== '' ? trim($adminNotes) : $booking['admin_notes'],
            'booking_code' => $bookingCode,
        ]
    );

    if ($updated) {
        sendBookingStatusWhatsApp(
            normalizePhoneNumber((string) ($booking['customer_phone'] ?? '')),
            (string) ($booking['booking_number'] ?? $booking['booking_code']),
            $status,
            $adminNotes !== null && trim($adminNotes) !== '' ? trim($adminNotes) : (string) ($booking['admin_notes'] ?? '')
        );
    }

    return $updated;
}

function createContact(?PDO $db, array $payload): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    $messageParts = [];
    $topic = trim((string) ($payload['topic'] ?? ''));
    $email = trim((string) ($payload['email'] ?? ''));
    $message = trim((string) ($payload['message'] ?? ''));

    if ($topic !== '') {
        $messageParts[] = 'Topik: ' . $topic;
    }

    if ($email !== '') {
        $messageParts[] = 'Email: ' . $email;
    }

    if ($message !== '') {
        $messageParts[] = $message;
    }

    dbExecute(
        $db,
        'INSERT INTO contacts (name, phone, email, topic, message) VALUES (:name, :phone, :email, :topic, :message)',
        [
            'name' => trim((string) ($payload['name'] ?? '')),
            'phone' => normalizePhoneNumber((string) ($payload['phone'] ?? '')),
            'email' => $email !== '' ? $email : null,
            'topic' => $topic !== '' ? $topic : null,
            'message' => implode("\n\n", $messageParts),
        ]
    );

    return dbFetchOne($db, 'SELECT * FROM contacts WHERE id = :id LIMIT 1', ['id' => (int) $db->lastInsertId()]);
}

function fetchCustomerVehicles(?PDO $db, int $customerId): array
{
    if (!$db instanceof PDO) {
        return [];
    }

    return dbFetchAll($db, 'SELECT * FROM vehicles WHERE customer_id = :customer_id ORDER BY id DESC', ['customer_id' => $customerId]);
}

function updateCustomerProfile(?PDO $db, int $customerId, array $payload): bool
{
    if (!$db instanceof PDO) {
        return false;
    }

    return dbExecute(
        $db,
        'UPDATE users SET name = :name, phone = :phone, avatar = :avatar WHERE id = :id',
        [
            'name' => trim((string) ($payload['name'] ?? '')),
            'phone' => normalizePhoneNumber((string) ($payload['phone'] ?? '')),
            'avatar' => trim((string) ($payload['avatar'] ?? '')) !== '' ? trim((string) ($payload['avatar'] ?? '')) : null,
            'id' => $customerId,
        ]
    );
}

function fetchCustomerAddresses(?PDO $db, int $customerId): array
{
    if (!$db instanceof PDO) {
        return [];
    }

    return dbFetchAll($db, 'SELECT * FROM addresses WHERE user_id = :user_id ORDER BY is_primary DESC, id DESC', ['user_id' => $customerId]);
}

function findAddressById(?PDO $db, int $addressId): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    return dbFetchOne($db, 'SELECT * FROM addresses WHERE id = :id LIMIT 1', ['id' => $addressId]);
}

function setPrimaryAddress(?PDO $db, int $customerId, int $addressId): bool
{
    if (!$db instanceof PDO) {
        return false;
    }

    $db->beginTransaction();

    try {
        dbExecute($db, 'UPDATE addresses SET is_primary = 0 WHERE user_id = :user_id', ['user_id' => $customerId]);
        dbExecute($db, 'UPDATE addresses SET is_primary = 1 WHERE id = :id AND user_id = :user_id', [
            'id' => $addressId,
            'user_id' => $customerId,
        ]);
        $db->commit();

        return true;
    } catch (Throwable $exception) {
        $db->rollBack();

        return false;
    }
}

function createAddressRecord(?PDO $db, int $customerId, array $payload): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    $isPrimary = !empty($payload['is_primary']);

    if ($isPrimary) {
        dbExecute($db, 'UPDATE addresses SET is_primary = 0 WHERE user_id = :user_id', ['user_id' => $customerId]);
    }

    dbExecute(
        $db,
        'INSERT INTO addresses (user_id, label, recipient_name, phone, province_id, province_name, city_id, city_name, district, address, postal_code, is_primary)
         VALUES (:user_id, :label, :recipient_name, :phone, :province_id, :province_name, :city_id, :city_name, :district, :address, :postal_code, :is_primary)',
        [
            'user_id' => $customerId,
            'label' => trim((string) ($payload['label'] ?? '')),
            'recipient_name' => trim((string) ($payload['recipient_name'] ?? '')),
            'phone' => normalizePhoneNumber((string) ($payload['phone'] ?? '')),
            'province_id' => trim((string) ($payload['province_id'] ?? '')),
            'province_name' => trim((string) ($payload['province_name'] ?? '')),
            'city_id' => trim((string) ($payload['city_id'] ?? '')),
            'city_name' => trim((string) ($payload['city_name'] ?? '')),
            'district' => trim((string) ($payload['district'] ?? '')),
            'address' => trim((string) ($payload['address'] ?? '')),
            'postal_code' => trim((string) ($payload['postal_code'] ?? '')),
            'is_primary' => $isPrimary ? 1 : 0,
        ]
    );

    return findAddressById($db, (int) $db->lastInsertId());
}

function updateAddressRecord(?PDO $db, int $customerId, int $addressId, array $payload): bool
{
    if (!$db instanceof PDO) {
        return false;
    }

    $isPrimary = !empty($payload['is_primary']);

    if ($isPrimary) {
        dbExecute($db, 'UPDATE addresses SET is_primary = 0 WHERE user_id = :user_id', ['user_id' => $customerId]);
    }

    return dbExecute(
        $db,
        'UPDATE addresses
         SET label = :label, recipient_name = :recipient_name, phone = :phone, province_id = :province_id, province_name = :province_name, city_id = :city_id, city_name = :city_name, district = :district, address = :address, postal_code = :postal_code, is_primary = :is_primary
         WHERE id = :id AND user_id = :user_id',
        [
            'id' => $addressId,
            'user_id' => $customerId,
            'label' => trim((string) ($payload['label'] ?? '')),
            'recipient_name' => trim((string) ($payload['recipient_name'] ?? '')),
            'phone' => normalizePhoneNumber((string) ($payload['phone'] ?? '')),
            'province_id' => trim((string) ($payload['province_id'] ?? '')),
            'province_name' => trim((string) ($payload['province_name'] ?? '')),
            'city_id' => trim((string) ($payload['city_id'] ?? '')),
            'city_name' => trim((string) ($payload['city_name'] ?? '')),
            'district' => trim((string) ($payload['district'] ?? '')),
            'address' => trim((string) ($payload['address'] ?? '')),
            'postal_code' => trim((string) ($payload['postal_code'] ?? '')),
            'is_primary' => $isPrimary ? 1 : 0,
        ]
    );
}

function deleteAddressRecord(?PDO $db, int $customerId, int $addressId): bool
{
    if (!$db instanceof PDO) {
        return false;
    }

    return dbExecute($db, 'DELETE FROM addresses WHERE id = :id AND user_id = :user_id', [
        'id' => $addressId,
        'user_id' => $customerId,
    ]);
}

function fetchAllProducts(?PDO $db): array
{
    if (!$db instanceof PDO) {
        return [];
    }

    return dbFetchAll($db, 'SELECT * FROM products ORDER BY created_at DESC, id DESC');
}

function createProductRecord(?PDO $db, array $payload): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    $category = findCategoryByName($db, (string) ($payload['category'] ?? ''));
    $brand = findBrandByName($db, (string) ($payload['brand'] ?? ''));

    dbExecute(
        $db,
        'INSERT INTO products (name, category, brand, compatibility, description, specifications, price, discount_price, stock, weight, badge, sold_count, image, slug, category_id, brand_id, is_active)
         VALUES (:name, :category, :brand, :compatibility, :description, :specifications, :price, :discount_price, :stock, :weight, :badge, :sold_count, :image, :slug, :category_id, :brand_id, :is_active)',
        [
            'name' => trim((string) $payload['name']),
            'category' => trim((string) $payload['category']),
            'brand' => trim((string) ($payload['brand'] ?? '')),
            'compatibility' => trim((string) ($payload['compatibility'] ?? '')),
            'description' => trim((string) ($payload['description'] ?? '')),
            'specifications' => trim((string) ($payload['specifications'] ?? '')) !== '' ? trim((string) ($payload['specifications'] ?? '')) : null,
            'price' => (int) $payload['price'],
            'discount_price' => !empty($payload['discount_price']) ? (float) $payload['discount_price'] : null,
            'stock' => (int) $payload['stock'],
            'weight' => max(1, (int) ($payload['weight'] ?? 1000)),
            'badge' => trim((string) ($payload['badge'] ?? '')),
            'sold_count' => max(0, (int) ($payload['sold_count'] ?? 0)),
            'image' => null,
            'slug' => slugify((string) $payload['name']),
            'category_id' => $category['id'] ?? null,
            'brand_id' => $brand['id'] ?? null,
            'is_active' => !empty($payload['is_active']) ? 1 : 0,
        ]
    );

    return findProductById($db, (int) $db->lastInsertId());
}

function updateProductRecord(?PDO $db, int $productId, array $payload): bool
{
    if (!$db instanceof PDO) {
        return false;
    }

    $category = findCategoryByName($db, (string) ($payload['category'] ?? ''));
    $brand = findBrandByName($db, (string) ($payload['brand'] ?? ''));

    return dbExecute(
        $db,
        'UPDATE products
         SET name = :name, category = :category, brand = :brand, compatibility = :compatibility, description = :description, specifications = :specifications, price = :price, discount_price = :discount_price, stock = :stock, weight = :weight, badge = :badge, slug = :slug, category_id = :category_id, brand_id = :brand_id, is_active = :is_active
         WHERE id = :id',
        [
            'id' => $productId,
            'name' => trim((string) $payload['name']),
            'category' => trim((string) $payload['category']),
            'brand' => trim((string) ($payload['brand'] ?? '')),
            'compatibility' => trim((string) ($payload['compatibility'] ?? '')),
            'description' => trim((string) ($payload['description'] ?? '')),
            'specifications' => trim((string) ($payload['specifications'] ?? '')) !== '' ? trim((string) ($payload['specifications'] ?? '')) : null,
            'price' => (int) $payload['price'],
            'discount_price' => !empty($payload['discount_price']) ? (float) $payload['discount_price'] : null,
            'stock' => (int) $payload['stock'],
            'weight' => max(1, (int) ($payload['weight'] ?? 1000)),
            'badge' => trim((string) ($payload['badge'] ?? '')),
            'slug' => slugify((string) $payload['name']),
            'category_id' => $category['id'] ?? null,
            'brand_id' => $brand['id'] ?? null,
            'is_active' => !empty($payload['is_active']) ? 1 : 0,
        ]
    );
}

function deleteProductRecord(?PDO $db, int $productId): bool
{
    if (!$db instanceof PDO) {
        return false;
    }

    return dbExecute($db, 'DELETE FROM products WHERE id = :id', ['id' => $productId]);
}

function fetchAllCategories(?PDO $db): array
{
    if (!$db instanceof PDO) {
        return [];
    }

    return dbFetchAll($db, 'SELECT * FROM categories ORDER BY name ASC');
}

function fetchAllBrands(?PDO $db): array
{
    if (!$db instanceof PDO) {
        return [];
    }

    return dbFetchAll($db, 'SELECT * FROM brands ORDER BY name ASC');
}

function createCategoryRecord(?PDO $db, array $payload): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    dbExecute(
        $db,
        'INSERT INTO categories (name, slug, icon, is_active) VALUES (:name, :slug, :icon, :is_active)',
        [
            'name' => trim((string) $payload['name']),
            'slug' => trim((string) ($payload['slug'] ?? '')) !== '' ? trim((string) $payload['slug']) : slugify((string) $payload['name']),
            'icon' => trim((string) ($payload['icon'] ?? '')) !== '' ? trim((string) $payload['icon']) : null,
            'is_active' => !empty($payload['is_active']) ? 1 : 0,
        ]
    );

    return findCategoryById($db, (int) $db->lastInsertId());
}

function updateCategoryRecord(?PDO $db, int $categoryId, array $payload): bool
{
    if (!$db instanceof PDO) {
        return false;
    }

    return dbExecute(
        $db,
        'UPDATE categories SET name = :name, slug = :slug, icon = :icon, is_active = :is_active WHERE id = :id',
        [
            'id' => $categoryId,
            'name' => trim((string) $payload['name']),
            'slug' => trim((string) ($payload['slug'] ?? '')) !== '' ? trim((string) $payload['slug']) : slugify((string) $payload['name']),
            'icon' => trim((string) ($payload['icon'] ?? '')) !== '' ? trim((string) $payload['icon']) : null,
            'is_active' => !empty($payload['is_active']) ? 1 : 0,
        ]
    );
}

function deleteCategoryRecord(?PDO $db, int $categoryId): bool
{
    if (!$db instanceof PDO) {
        return false;
    }

    return dbExecute($db, 'DELETE FROM categories WHERE id = :id', ['id' => $categoryId]);
}

function createBrandRecord(?PDO $db, array $payload): ?array
{
    if (!$db instanceof PDO) {
        return null;
    }

    dbExecute(
        $db,
        'INSERT INTO brands (name, logo, is_active) VALUES (:name, :logo, :is_active)',
        [
            'name' => trim((string) $payload['name']),
            'logo' => trim((string) ($payload['logo'] ?? '')) !== '' ? trim((string) $payload['logo']) : null,
            'is_active' => !empty($payload['is_active']) ? 1 : 0,
        ]
    );

    return findBrandById($db, (int) $db->lastInsertId());
}

function updateBrandRecord(?PDO $db, int $brandId, array $payload): bool
{
    if (!$db instanceof PDO) {
        return false;
    }

    return dbExecute(
        $db,
        'UPDATE brands SET name = :name, logo = :logo, is_active = :is_active WHERE id = :id',
        [
            'id' => $brandId,
            'name' => trim((string) $payload['name']),
            'logo' => trim((string) ($payload['logo'] ?? '')) !== '' ? trim((string) $payload['logo']) : null,
            'is_active' => !empty($payload['is_active']) ? 1 : 0,
        ]
    );
}

function deleteBrandRecord(?PDO $db, int $brandId): bool
{
    if (!$db instanceof PDO) {
        return false;
    }

    return dbExecute($db, 'DELETE FROM brands WHERE id = :id', ['id' => $brandId]);
}

function fetchAllSettings(?PDO $db): array
{
    if (!$db instanceof PDO) {
        return [];
    }

    $rows = dbFetchAll($db, 'SELECT key_name, value FROM settings ORDER BY key_name ASC');
    $settings = [];

    foreach ($rows as $row) {
        $settings[(string) $row['key_name']] = (string) ($row['value'] ?? '');
    }

    return $settings;
}

function getSettingValue(?PDO $db, string $keyName, string $default = ''): string
{
    if (!$db instanceof PDO) {
        return $default;
    }

    $row = dbFetchOne($db, 'SELECT value FROM settings WHERE key_name = :key_name LIMIT 1', ['key_name' => $keyName]);

    return $row !== null ? (string) ($row['value'] ?? $default) : $default;
}

function saveSettings(?PDO $db, array $payload): bool
{
    if (!$db instanceof PDO) {
        return false;
    }

    $db->beginTransaction();

    try {
        foreach ($payload as $keyName => $value) {
            $existing = dbFetchOne($db, 'SELECT id FROM settings WHERE key_name = :key_name LIMIT 1', ['key_name' => $keyName]);

            if ($existing !== null) {
                dbExecute($db, 'UPDATE settings SET value = :value WHERE key_name = :key_name', [
                    'key_name' => $keyName,
                    'value' => (string) $value,
                ]);
                continue;
            }

            dbExecute($db, 'INSERT INTO settings (key_name, value) VALUES (:key_name, :value)', [
                'key_name' => $keyName,
                'value' => (string) $value,
            ]);
        }

        $db->commit();

        return true;
    } catch (Throwable $exception) {
        $db->rollBack();

        return false;
    }
}

function fetchAllOrders(?PDO $db, ?string $status = null): array
{
    if (!$db instanceof PDO) {
        return [];
    }

    if ($status !== null && $status !== '') {
        return dbFetchAll($db, 'SELECT * FROM orders WHERE status = :status ORDER BY created_at DESC, id DESC', ['status' => $status]);
    }

    return dbFetchAll($db, 'SELECT * FROM orders ORDER BY created_at DESC, id DESC');
}

function fetchAllBookings(?PDO $db, ?string $status = null): array
{
    if (!$db instanceof PDO) {
        return [];
    }

    $sql = 'SELECT bookings.*, services.name AS service_name
            FROM bookings
            INNER JOIN services ON services.id = bookings.service_id';
    $params = [];

    if ($status !== null && $status !== '') {
        $sql .= ' WHERE bookings.status = :status';
        $params['status'] = $status;
    }

    $sql .= ' ORDER BY bookings.booking_date DESC, bookings.booking_time DESC, bookings.id DESC';

    return dbFetchAll($db, $sql, $params);
}

function fetchAllCustomers(?PDO $db): array
{
    if (!$db instanceof PDO) {
        return [];
    }

    return dbFetchAll($db, "SELECT * FROM users WHERE role = 'customer' ORDER BY created_at DESC, id DESC");
}

function fetchAdminDashboardMetrics(?PDO $db): array
{
    if (!$db instanceof PDO) {
        return [
            'total_orders' => 0,
            'orders_today' => 0,
            'revenue_month' => 0,
            'pending_orders' => 0,
            'pending_bookings' => 0,
            'total_products' => 0,
            'customer_count' => 0,
            'low_stock_count' => 0,
        ];
    }

    $totalOrders = dbFetchOne($db, 'SELECT COUNT(*) AS total FROM orders');
    $ordersToday = dbFetchOne($db, 'SELECT COUNT(*) AS total FROM orders WHERE DATE(created_at) = CURRENT_DATE()');
    $revenueMonth = dbFetchOne($db, 'SELECT COALESCE(SUM(COALESCE(total, total_amount)), 0) AS total FROM orders WHERE YEAR(created_at) = YEAR(CURRENT_DATE()) AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND status <> "dibatalkan"');
    $pendingOrders = dbFetchOne($db, "SELECT COUNT(*) AS total FROM orders WHERE status IN ('pending','diproses')");
    $pendingBookings = dbFetchOne($db, "SELECT COUNT(*) AS total FROM bookings WHERE status IN ('menunggu','dikonfirmasi','dalam_proses')");
    $totalProducts = dbFetchOne($db, 'SELECT COUNT(*) AS total FROM products WHERE is_active = 1');
    $customerCount = dbFetchOne($db, "SELECT COUNT(*) AS total FROM users WHERE role = 'customer'");
    $lowStockCount = dbFetchOne($db, 'SELECT COUNT(*) AS total FROM products WHERE stock <= 5');

    return [
        'total_orders' => (int) ($totalOrders['total'] ?? 0),
        'orders_today' => (int) ($ordersToday['total'] ?? 0),
        'revenue_month' => (int) ($revenueMonth['total'] ?? 0),
        'pending_orders' => (int) ($pendingOrders['total'] ?? 0),
        'pending_bookings' => (int) ($pendingBookings['total'] ?? 0),
        'total_products' => (int) ($totalProducts['total'] ?? 0),
        'customer_count' => (int) ($customerCount['total'] ?? 0),
        'low_stock_count' => (int) ($lowStockCount['total'] ?? 0),
    ];
}

function fetchAdminRecentOrders(?PDO $db, int $limit = 5): array
{
    if (!$db instanceof PDO) {
        return [];
    }

    $limit = max(1, $limit);

    return dbFetchAll($db, 'SELECT * FROM orders ORDER BY created_at DESC, id DESC LIMIT ' . $limit);
}

function fetchAdminUpcomingBookings(?PDO $db, int $limit = 5): array
{
    if (!$db instanceof PDO) {
        return [];
    }

    $limit = max(1, $limit);

    return dbFetchAll(
        $db,
        'SELECT bookings.*, services.name AS service_name
         FROM bookings
         INNER JOIN services ON services.id = bookings.service_id
         WHERE bookings.booking_date >= CURRENT_DATE()
         ORDER BY bookings.booking_date ASC, bookings.booking_time ASC, bookings.id ASC
         LIMIT ' . $limit
    );
}
