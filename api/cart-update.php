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
$quantity = (int) requestInput('quantity', 1);

if ($productId <= 0) {
    jsonResponse([
        'success' => false,
        'message' => 'Produk keranjang tidak valid.',
    ], 422);
}

$updated = updateCartItem($db, (int) currentUserId(), $productId, $quantity);

jsonResponse([
    'success' => $updated,
    'message' => $updated ? 'Keranjang berhasil diperbarui.' : 'Keranjang gagal diperbarui.',
    'cart_count' => countCartItems($db, (int) currentUserId()),
], $updated ? 200 : 500);
