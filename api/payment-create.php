<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

if (!isCustomerLoggedIn()) {
    jsonResponse([
        'success' => false,
        'message' => 'Silakan login sebagai customer terlebih dahulu.',
        'redirect_url' => fitsMotorUrl('/auth/login.php?redirect=' . urlencode(fitsMotorUrl('/checkout.php'))),
    ], 401);
}

$customer = findUserById($db, (int) currentUserId());

if ($customer === null) {
    jsonResponse([
        'success' => false,
        'message' => 'Data customer tidak ditemukan.',
    ], 404);
}

$paymentMethod = trim((string) requestInput('payment_method', 'transfer'));
$tripayMethod = strtoupper(trim((string) requestInput('tripay_method', '')));
$payload = [
    'payment_method' => $paymentMethod,
    'delivery_method' => trim((string) requestInput('delivery_method', 'delivery')),
    'delivery_address' => trim((string) requestInput('delivery_address', '')),
    'recipient_name' => trim((string) requestInput('recipient_name', '')),
    'recipient_phone' => trim((string) requestInput('recipient_phone', '')),
    'province_id' => trim((string) requestInput('province_id', '')),
    'province_name' => trim((string) requestInput('province_name', '')),
    'city_id' => trim((string) requestInput('city_id', '')),
    'city_name' => trim((string) requestInput('city_name', '')),
    'district' => trim((string) requestInput('district', '')),
    'postal_code' => trim((string) requestInput('postal_code', '')),
    'courier' => trim((string) requestInput('courier', '')),
    'courier_service' => trim((string) requestInput('courier_service', '')),
    'shipping_cost' => (float) requestInput('shipping_cost', 0),
    'estimated_days' => trim((string) requestInput('estimated_days', '')),
    'voucher_discount' => (float) requestInput('voucher_discount', 0),
    'notes' => 'Checkout dari website FITS MOTOR',
];

if ($payload['recipient_name'] === '' || $payload['recipient_phone'] === '' || $payload['delivery_address'] === '') {
    jsonResponse([
        'success' => false,
        'message' => 'Alamat pengiriman belum lengkap.',
    ], 422);
}

if ($payload['courier_service'] === '') {
    jsonResponse([
        'success' => false,
        'message' => 'Pilih layanan pengiriman terlebih dahulu.',
    ], 422);
}

if ($paymentMethod === 'transfer' && $tripayMethod === '') {
    jsonResponse([
        'success' => false,
        'message' => 'Pilih metode pembayaran terlebih dahulu.',
    ], 422);
}

if ($paymentMethod === 'transfer' && tripayIsConfigured() && findTripayPaymentChannel($tripayMethod) === null) {
    jsonResponse([
        'success' => false,
        'message' => 'Metode pembayaran yang dipilih sedang tidak aktif.',
    ], 422);
}

$order = createOrderFromCart($db, $customer, $payload);

if ($order === null) {
    jsonResponse([
        'success' => false,
        'message' => 'Checkout gagal diproses. Pastikan keranjang berisi produk.',
    ], 422);
}

$_SESSION['last_order_code'] = $order['order_code'];

$tripayConfigured = tripayIsConfigured();

if ($tripayConfigured && $paymentMethod === 'transfer') {
    $items = fetchOrderItems($db, (int) $order['id']);
    $tripayResult = createTripayTransaction($order, $items, $tripayMethod);

    if ($tripayResult['success'] ?? false) {
        $tripayData = $tripayResult['data'];
        dbExecute(
            $db,
            'UPDATE orders SET tripay_reference = :tripay_reference, payment_url = :payment_url, payment_method = :payment_method WHERE id = :id',
            [
                'tripay_reference' => (string) $tripayData['reference'],
                'payment_url' => (string) $tripayData['checkout_url'],
                'payment_method' => 'transfer',
                'id' => $order['id'],
            ]
        );

        sendOrderWhatsApp(
            normalizePhoneNumber((string) $customer['phone']),
            (string) ($order['order_number'] ?? $order['order_code']),
            (int) ($order['total'] ?? $order['total_amount']),
            (string) $order['payment_method']
        );

        jsonResponse([
            'success' => true,
            'message' => 'Checkout berhasil dibuat dan diarahkan ke Tripay.',
            'order_code' => $order['order_code'],
            'payment_url' => $tripayData['checkout_url'],
            'redirect_url' => $tripayData['checkout_url'],
            'source' => 'tripay',
        ]);
    }

    rollbackFailedCheckoutOrder($db, (int) $order['id']);

    jsonResponse([
        'success' => false,
        'message' => (string) ($tripayResult['message'] ?? 'Tripay gagal membuat transaksi pembayaran.'),
    ], (int) ($tripayResult['status_code'] ?? 502));
}

sendOrderWhatsApp(
    normalizePhoneNumber((string) $customer['phone']),
    (string) ($order['order_number'] ?? $order['order_code']),
    (int) ($order['total'] ?? $order['total_amount']),
    (string) $order['payment_method']
);

jsonResponse([
    'success' => true,
    'message' => 'Checkout berhasil dibuat.',
    'order_code' => $order['order_code'],
    'redirect_url' => fitsMotorUrl('/payment-instruction.php?order_code=' . urlencode((string) $order['order_code'])),
    'source' => 'local',
]);
