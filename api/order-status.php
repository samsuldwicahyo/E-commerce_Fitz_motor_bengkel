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

$orderCode = trim((string) requestInput('order_code', queryInput('order_code', '')));

if ($orderCode === '') {
    jsonResponse([
        'success' => false,
        'message' => 'order_code wajib dikirim.',
    ], 422);
}

$order = findOrderByCode($db, $orderCode);
$customerId = (int) currentUserId();

if ($order === null || (int) ($order['customer_id'] ?? 0) !== $customerId) {
    jsonResponse([
        'success' => false,
        'message' => 'Pesanan tidak ditemukan.',
    ], 404);
}

$isPaid = (string) ($order['payment_status'] ?? '') === 'sudah_bayar';

jsonResponse([
    'success' => true,
    'order_code' => (string) ($order['order_code'] ?? ''),
    'order_number' => (string) ($order['order_number'] ?? $order['order_code'] ?? ''),
    'payment_status' => (string) ($order['payment_status'] ?? ''),
    'payment_status_label' => paymentStatusLabel((string) ($order['payment_status'] ?? '')),
    'order_status' => (string) ($order['order_status'] ?? $order['status'] ?? ''),
    'order_status_label' => orderStatusLabel((string) ($order['order_status'] ?? $order['status'] ?? '')),
    'paid_at' => $order['paid_at'] ?? null,
    'redirect_url' => $isPaid ? fitsMotorUrl('/customer/order-detail.php?order_code=' . urlencode((string) ($order['order_code'] ?? ''))) : null,
]);
