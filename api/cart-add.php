<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

[$productId, $productName, $quantity, $buyNow] = [
    (int) requestInput('product_id', 0),
    trim((string) requestInput('product_name', '')),
    max(1, (int) requestInput('quantity', 1)),
    (bool) requestInput('buy_now', false),
];

if (!isCustomerLoggedIn()) {
    $_SESSION['pending_cart_action'] = [
        'product_id' => $productId,
        'product_name' => $productName,
        'quantity' => $quantity,
        'buy_now' => $buyNow,
    ];

    jsonResponse([
        'success' => false,
        'message' => $buyNow
            ? 'Silakan login sebagai customer untuk melanjutkan pembelian.'
            : 'Silakan login sebagai customer untuk menambahkan produk ke keranjang.',
        'redirect_url' => fitsMotorUrl('/auth/login.php'),
    ], 401);
}

$customerId = (int) currentUserId();

$product = $productId > 0 ? dbFetchOne($db, 'SELECT * FROM products WHERE id = :id AND is_active = 1 LIMIT 1', ['id' => $productId]) : null;

if ($product === null && $productName !== '') {
    $product = resolveProductByTemplateName($db, $productName);
}

if ($product === null) {
    jsonResponse([
        'success' => false,
        'message' => 'Produk tidak ditemukan di database.',
    ], 404);
}

if ((int) $product['stock'] < 1) {
    jsonResponse([
        'success' => false,
        'message' => 'Stok produk sedang habis.',
    ], 422);
}

$saved = addCartItem($db, $customerId, (int) $product['id'], min($quantity, max(1, (int) $product['stock'])));

if (!$saved) {
    jsonResponse([
        'success' => false,
        'message' => 'Produk gagal dimasukkan ke keranjang.',
    ], 500);
}

jsonResponse([
    'success' => true,
    'message' => $buyNow ? 'Produk siap dibeli sekarang.' : 'Produk berhasil ditambahkan ke keranjang.',
    'cart_count' => countCartItems($db, $customerId),
]);
