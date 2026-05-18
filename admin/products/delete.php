<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/bootstrap.php';

requireAdminLogin();

$productId = (int) queryInput('id', requestInput('id', 0));

if ($productId <= 0) {
    redirectWithMessage('/admin/products/index.php', 'error', 'Produk tidak valid.');
}

$deleted = deleteProductRecord($db, $productId);

if (!$deleted) {
    redirectWithMessage('/admin/products/index.php', 'error', 'Produk gagal dihapus.');
}

redirectWithMessage('/admin/products/index.php', 'success', 'Produk berhasil dihapus.');
