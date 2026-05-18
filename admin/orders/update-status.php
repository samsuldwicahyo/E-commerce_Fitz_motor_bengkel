<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/bootstrap.php';

requireAdminLogin();

$orderCode = trim((string) requestInput('order_code', queryInput('order_code', '')));
$status = trim((string) requestInput('status', queryInput('status', 'diproses')));
$paymentStatus = trim((string) requestInput('payment_status', queryInput('payment_status', '')));
$trackingNumber = trim((string) requestInput('tracking_number', queryInput('tracking_number', '')));
$adminNotes = trim((string) requestInput('admin_notes', queryInput('admin_notes', '')));

if ($orderCode === '') {
    $latest = fetchAdminRecentOrders($db, 1);
    $orderCode = (string) ($latest[0]['order_code'] ?? '');
}

if ($orderCode === '') {
    redirectWithMessage('/admin/orders/index.php', 'error', 'Tidak ada pesanan yang bisa diperbarui.');
}

$updated = updateOrderStatus(
    $db,
    $orderCode,
    $status,
    $paymentStatus !== '' ? $paymentStatus : null,
    null,
    $trackingNumber !== '' ? $trackingNumber : null,
    $adminNotes !== '' ? $adminNotes : null
);

if (!$updated) {
    redirectWithMessage('/admin/orders/detail.php?order_code=' . urlencode($orderCode), 'error', 'Status pesanan gagal diperbarui.');
}

redirectWithMessage('/admin/orders/detail.php?order_code=' . urlencode($orderCode), 'success', 'Status pesanan berhasil diperbarui.');
