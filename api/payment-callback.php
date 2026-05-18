<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

if (TRIPAY_PRIVATE_KEY !== '' && isset($_SERVER['HTTP_X_CALLBACK_SIGNATURE'])) {
    $json = file_get_contents('php://input');
    $signature = (string) ($_SERVER['HTTP_X_CALLBACK_SIGNATURE'] ?? '');
    $event = (string) ($_SERVER['HTTP_X_CALLBACK_EVENT'] ?? '');
    $expected = hash_hmac('sha256', (string) $json, TRIPAY_PRIVATE_KEY);

    if (!hash_equals($expected, $signature)) {
        jsonResponse([
            'success' => false,
            'message' => 'Invalid signature.',
        ], 400);
    }

    $payload = json_decode((string) $json, true);
    $orderCode = trim((string) ($payload['merchant_ref'] ?? ''));
    $tripayStatus = strtoupper(trim((string) ($payload['status'] ?? '')));

    if ($event === 'payment_status' && $orderCode !== '') {
        $mappedPaymentStatus = $tripayStatus === 'PAID' ? 'sudah_bayar' : 'belum_bayar';
        $mappedOrderStatus = $tripayStatus === 'PAID' ? 'diproses' : 'pending';
        $updated = updateOrderStatus($db, $orderCode, $mappedOrderStatus, $mappedPaymentStatus, 'Tripay callback: ' . $tripayStatus);

        jsonResponse([
            'success' => $updated,
            'message' => $updated ? 'Tripay callback diproses.' : 'Pesanan tidak ditemukan.',
        ], $updated ? 200 : 404);
    }
}

$orderCode = trim((string) requestInput('order_code', queryInput('order_code', '')));
$status = trim((string) requestInput('status', queryInput('status', 'sudah_bayar')));

if ($orderCode === '') {
    jsonResponse([
        'success' => false,
        'message' => 'order_code wajib dikirim.',
    ], 422);
}

$updated = updateOrderStatus(
    $db,
    $orderCode,
    $status === 'sudah_bayar' ? 'diproses' : 'pending',
    $status === 'sudah_bayar' ? 'sudah_bayar' : 'belum_bayar',
    'Callback pembayaran simulasi diterima pada ' . date('Y-m-d H:i:s')
);

jsonResponse([
    'success' => $updated,
    'message' => $updated ? 'Status pembayaran berhasil diperbarui.' : 'Pesanan tidak ditemukan.',
], $updated ? 200 : 404);
