<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

if (!isCustomerLoggedIn()) {
    jsonResponse([
        'success' => false,
        'message' => 'Silakan login sebagai customer terlebih dahulu.',
        'redirect_url' => fitsMotorUrl('/auth/login.php'),
    ], 401);
}

$productId = (int) requestInput('product_id', 0);

if ($productId <= 0) {
    jsonResponse([
        'success' => false,
        'message' => 'Produk keranjang tidak valid.',
    ], 422);
}

$removed = removeCartItem($db, (int) currentUserId(), $productId);

jsonResponse([
    'success' => $removed,
    'message' => $removed ? 'Item keranjang berhasil dihapus.' : 'Item keranjang gagal dihapus.',
    'cart_count' => countCartItems($db, (int) currentUserId()),
], $removed ? 200 : 500);
