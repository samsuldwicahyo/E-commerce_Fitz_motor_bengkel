<?php
declare(strict_types=1);

require_once __DIR__ . '/../shared/render_page.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function appUrl(string $path = ''): string
{
    return fitsMotorUrl($path);
}

function redirectTo(string $path): never
{
    header('Location: ' . appUrl($path));
    exit;
}

function jsonResponse(array $payload, int $statusCode = 200): never
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function requestMethod(): string
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
}

function isPostRequest(): bool
{
    return requestMethod() === 'POST';
}

function readJsonInput(): array
{
    static $jsonInput;

    if ($jsonInput !== null) {
        return $jsonInput;
    }

    $rawInput = file_get_contents('php://input');

    if (!is_string($rawInput) || trim($rawInput) === '') {
        $jsonInput = [];
        return $jsonInput;
    }

    $decoded = json_decode($rawInput, true);
    $jsonInput = is_array($decoded) ? $decoded : [];

    return $jsonInput;
}

function requestInput(?string $key = null, mixed $default = null): mixed
{
    $payload = $_POST;

    if ($payload === []) {
        $payload = readJsonInput();
    }

    if ($key === null) {
        return $payload;
    }

    return $payload[$key] ?? $default;
}

function queryInput(string $key, mixed $default = null): mixed
{
    return $_GET[$key] ?? $default;
}

function redirectWithMessage(string $path, string $type, string $message): never
{
    setFlash($type, $message);
    redirectTo($path);
}

function setFlash(string $type, string $message): void
{
    $_SESSION['flash'][$type] = $message;
}

function getFlash(string $type): ?string
{
    if (!isset($_SESSION['flash'][$type])) {
        return null;
    }

    $message = (string) $_SESSION['flash'][$type];
    unset($_SESSION['flash'][$type]);

    return $message;
}

function setOldInput(array $data): void
{
    $_SESSION['old_input'] = $data;
}

function oldInput(string $key, string $default = ''): string
{
    return (string) ($_SESSION['old_input'][$key] ?? $default);
}

function clearOldInput(): void
{
    unset($_SESSION['old_input']);
}

function formatRupiah(int|float $amount): string
{
    return 'Rp ' . number_format((float) $amount, 0, ',', '.');
}

function formatIdrText(int|float $amount): string
{
    return 'IDR ' . number_format((float) $amount, 0, ',', '.');
}

function normalizePhoneNumber(string $phone): string
{
    $digits = preg_replace('/\D+/', '', $phone) ?? '';

    if ($digits === '') {
        return '';
    }

    if (str_starts_with($digits, '62')) {
        return '0' . substr($digits, 2);
    }

    if (!str_starts_with($digits, '0')) {
        return '0' . $digits;
    }

    return $digits;
}

function whatsappPhoneNumber(string $phone): string
{
    $normalized = normalizePhoneNumber($phone);

    if ($normalized === '') {
        return '';
    }

    return str_starts_with($normalized, '0') ? '62' . substr($normalized, 1) : $normalized;
}

function whatsappDirectUrl(string $phone, string $message = ''): string
{
    $number = whatsappPhoneNumber($phone);
    $query = $message !== '' ? '?text=' . rawurlencode($message) : '';

    return $number !== '' ? 'https://wa.me/' . $number . $query : '#';
}

function orderStatusLabel(string $status): string
{
    return match (strtoupper(trim($status))) {
        'NEW', 'PENDING' => 'Pesanan Baru',
        'PROCESSING', 'DIPROSES' => 'Diproses',
        'SHIPPED', 'SIAP_DIAMBIL' => 'Dikirim / Siap Diambil',
        'DELIVERED', 'SELESAI' => 'Selesai',
        'CANCELLED', 'DIBATALKAN' => 'Dibatalkan',
        default => trim($status) !== '' ? trim($status) : '-',
    };
}

function paymentStatusLabel(string $status): string
{
    return match (strtoupper(trim($status))) {
        'PENDING', 'BELUM_BAYAR' => 'Menunggu Pembayaran',
        'PAID', 'SUDAH_BAYAR' => 'Sudah Dibayar',
        'FAILED' => 'Gagal',
        'EXPIRED' => 'Kedaluwarsa',
        default => trim($status) !== '' ? trim($status) : '-',
    };
}

function bookingStatusLabel(string $status): string
{
    return match (strtoupper(trim($status))) {
        'PENDING', 'MENUNGGU' => 'Menunggu',
        'CONFIRMED', 'DIKONFIRMASI' => 'Dikonfirmasi',
        'IN_PROGRESS', 'DALAM_PROSES' => 'Dalam Proses',
        'DONE', 'SELESAI' => 'Selesai',
        'CANCELLED', 'DIBATALKAN' => 'Dibatalkan',
        default => trim($status) !== '' ? trim($status) : '-',
    };
}

function currentUserId(): ?int
{
    return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
}

function generateReferenceCode(string $prefix): string
{
    return strtoupper($prefix) . '-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 4));
}

function slugify(string $value): string
{
    $value = trim(strtolower($value));
    $value = preg_replace('/[^a-z0-9]+/i', '-', $value) ?? $value;
    $value = trim($value, '-');

    return $value !== '' ? $value : 'item';
}

function ensureDirectoryExists(string $path): void
{
    if (!is_dir($path)) {
        @mkdir($path, 0775, true);
    }
}

function sendWhatsAppMessage(string $phone, string $message): bool
{
    if (FONNTE_TOKEN === '') {
        return false;
    }

    $payload = json_encode([
        'target' => $phone,
        'message' => $message,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    if (!is_string($payload)) {
        return false;
    }

    $curl = curl_init('https://api.fonnte.com/send');
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            'Authorization: ' . FONNTE_TOKEN,
            'Content-Type: application/json',
        ],
        CURLOPT_TIMEOUT => 15,
    ]);

    $response = curl_exec($curl);
    $statusCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    return is_string($response) && $statusCode >= 200 && $statusCode < 300;
}

function sendBookingWhatsApp(string $phone, string $bookingNumber, array $data): bool
{
    $message = "BOOKING KONFIRMASI - FITS MOTOR\n\n";
    $message .= "Halo " . ($data['customer_name'] ?? 'Pelanggan') . "!\n";
    $message .= "No. Booking: {$bookingNumber}\n";
    $message .= "Motor: " . trim((string) (($data['motor_brand'] ?? '') . ' ' . ($data['motor_model'] ?? $data['vehicle_type'] ?? ''))) . "\n";
    $message .= "Tanggal: " . ($data['booking_date'] ?? '-') . "\n";
    $message .= "Waktu: " . ($data['booking_time'] ?? '-') . "\n";

    return sendWhatsAppMessage($phone, $message);
}

function sendOrderWhatsApp(string $phone, string $orderNumber, int|float $total, string $paymentMethod): bool
{
    $message = "PESANAN DITERIMA - FITS MOTOR\n\n";
    $message .= "No. Pesanan: {$orderNumber}\n";
    $message .= "Total: " . formatRupiah($total) . "\n";
    $message .= "Metode: {$paymentMethod}\n";
    $message .= "Terima kasih telah berbelanja di FITS MOTOR.";

    return sendWhatsAppMessage($phone, $message);
}

function sendOrderStatusWhatsApp(string $phone, string $orderNumber, string $status, ?string $trackingNumber = null): bool
{
    $message = "UPDATE PESANAN - FITS MOTOR\n\n";
    $message .= "No. Pesanan: {$orderNumber}\n";
    $message .= "Status terbaru: " . orderStatusLabel($status) . "\n";

    if ($trackingNumber !== null && trim($trackingNumber) !== '') {
        $message .= "No. Resi: " . trim($trackingNumber) . "\n";
    }

    $message .= "\nTerima kasih telah berbelanja di FITS MOTOR.";

    return sendWhatsAppMessage($phone, $message);
}

function buildTripayOrderItems(array $items, array $order): array
{
    $tripayItems = [];

    foreach ($items as $item) {
        $name = trim((string) ($item['product_name'] ?? $item['name'] ?? 'Produk FITS MOTOR'));
        $unitPrice = max(1, (int) round((float) ($item['price'] ?? 0)));
        $quantity = max(1, (int) ($item['quantity'] ?? 1));

        for ($index = 0; $index < $quantity; $index++) {
            $tripayItems[] = [
                'name' => $name,
                'price' => $unitPrice,
                'quantity' => 1,
            ];
        }
    }

    $shippingCost = max(0, (int) round((float) ($order['shipping_cost'] ?? 0)));

    if ($shippingCost > 0) {
        $shippingLabel = 'Biaya Pengiriman';
        $courier = strtoupper(trim((string) ($order['courier'] ?? '')));
        $service = strtoupper(trim((string) ($order['courier_service'] ?? '')));

        if ($courier !== '' || $service !== '') {
            $shippingLabel .= ' ' . trim($courier . ' ' . $service);
        }

        $tripayItems[] = [
            'name' => trim($shippingLabel),
            'price' => $shippingCost,
            'quantity' => 1,
        ];
    }

    $targetTotal = max(0, (int) round((float) ($order['total'] ?? $order['total_amount'] ?? 0)));
    $currentTotal = 0;

    foreach ($tripayItems as $tripayItem) {
        $currentTotal += (int) $tripayItem['price'] * (int) $tripayItem['quantity'];
    }

    $difference = $targetTotal - $currentTotal;

    if ($difference > 0) {
        $tripayItems[] = [
            'name' => 'Penyesuaian Transaksi',
            'price' => $difference,
            'quantity' => 1,
        ];
    } elseif ($difference < 0) {
        for ($index = count($tripayItems) - 1; $index >= 0 && $difference < 0; $index--) {
            $currentPrice = (int) ($tripayItems[$index]['price'] ?? 0);
            $maxReduction = min(max(0, $currentPrice - 1), abs($difference));

            if ($maxReduction <= 0) {
                continue;
            }

            $tripayItems[$index]['price'] = $currentPrice - $maxReduction;
            $difference += $maxReduction;
        }
    }

    return array_values(array_filter(
        $tripayItems,
        static fn (array $tripayItem): bool => (int) ($tripayItem['price'] ?? 0) > 0 && (int) ($tripayItem['quantity'] ?? 0) > 0
    ));
}

function tripayIsConfigured(): bool
{
    return TRIPAY_API_KEY !== '' && TRIPAY_PRIVATE_KEY !== '' && TRIPAY_MERCHANT_CODE !== '' && TRIPAY_BASE_URL !== '';
}

function fetchTripayPaymentChannels(bool $onlyActive = true): array
{
    static $cache = [];
    $cacheKey = $onlyActive ? 'active' : 'all';

    if (array_key_exists($cacheKey, $cache)) {
        return $cache[$cacheKey];
    }

    if (!tripayIsConfigured()) {
        $cache[$cacheKey] = [];
        return $cache[$cacheKey];
    }

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => rtrim(TRIPAY_BASE_URL, '/') . '/merchant/payment-channel',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . TRIPAY_API_KEY,
        ],
        CURLOPT_TIMEOUT => 20,
    ]);
    $response = curl_exec($curl);
    $statusCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if (!is_string($response) || $statusCode < 200 || $statusCode >= 300) {
        $cache[$cacheKey] = [];
        return $cache[$cacheKey];
    }

    $decoded = json_decode($response, true);
    $channels = $decoded['data'] ?? null;

    if (!is_array($channels)) {
        $cache[$cacheKey] = [];
        return $cache[$cacheKey];
    }

    if ($onlyActive) {
        $channels = array_values(array_filter(
            $channels,
            static fn (array $channel): bool => (bool) ($channel['active'] ?? false)
        ));
    }

    $cache[$cacheKey] = $channels;

    return $cache[$cacheKey];
}

function findTripayPaymentChannel(string $code): ?array
{
    $normalizedCode = strtoupper(trim($code));

    if ($normalizedCode === '') {
        return null;
    }

    foreach (fetchTripayPaymentChannels(true) as $channel) {
        if (strtoupper((string) ($channel['code'] ?? '')) === $normalizedCode) {
            return $channel;
        }
    }

    return null;
}

function createTripayTransaction(array $order, array $items, string $method): array
{
    if (!tripayIsConfigured()) {
        return [
            'success' => false,
            'message' => 'Konfigurasi Tripay belum lengkap.',
            'status_code' => 500,
        ];
    }

    $channel = findTripayPaymentChannel($method);

    if ($channel === null) {
        return [
            'success' => false,
            'message' => 'Metode pembayaran yang dipilih sedang tidak tersedia.',
            'status_code' => 422,
        ];
    }

    $merchantRef = (string) ($order['order_number'] ?? $order['order_code'] ?? '');
    $amount = (int) round((float) ($order['total'] ?? $order['total_amount'] ?? 0));
    $signature = hash_hmac('sha256', TRIPAY_MERCHANT_CODE . $merchantRef . $amount, TRIPAY_PRIVATE_KEY);
    $tripayItems = buildTripayOrderItems($items, $order);

    $payload = [
        'method' => (string) $channel['code'],
        'merchant_ref' => $merchantRef,
        'amount' => $amount,
        'customer_name' => (string) ($order['customer_name'] ?? ''),
        'customer_email' => (string) ($order['customer_email'] ?? ''),
        'customer_phone' => (string) ($order['customer_phone'] ?? ''),
        'order_items' => $tripayItems,
        'callback_url' => TRIPAY_CALLBACK_URL,
        'return_url' => fitsMotorAbsoluteUrl('/payment-instruction.php?order_code=' . urlencode((string) ($order['order_code'] ?? $merchantRef))),
        'expired_time' => time() + (24 * 60 * 60),
        'signature' => $signature,
    ];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => rtrim(TRIPAY_BASE_URL, '/') . '/transaction/create',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . TRIPAY_API_KEY,
            'Content-Type: application/json',
        ],
        CURLOPT_TIMEOUT => 20,
    ]);
    $response = curl_exec($curl);
    $statusCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curlError = curl_error($curl);
    curl_close($curl);

    if (!is_string($response) || $response === '') {
        return [
            'success' => false,
            'message' => $curlError !== '' ? $curlError : 'Tidak dapat terhubung ke Tripay.',
            'status_code' => 502,
        ];
    }

    $decoded = json_decode($response, true);
    $tripayData = $decoded['data'] ?? null;

    if ($statusCode >= 200 && $statusCode < 300 && is_array($tripayData) && isset($tripayData['reference'], $tripayData['checkout_url'])) {
        return [
            'success' => true,
            'data' => $tripayData,
            'channel' => $channel,
            'status_code' => $statusCode,
        ];
    }

    return [
        'success' => false,
        'message' => trim((string) ($decoded['message'] ?? 'Tripay gagal membuat transaksi pembayaran.')),
        'status_code' => $statusCode >= 400 ? $statusCode : 502,
        'response' => $decoded,
    ];
}

function sendBookingStatusWhatsApp(string $phone, string $bookingNumber, string $status, ?string $adminNotes = null): bool
{
    $message = "UPDATE BOOKING - FITS MOTOR\n\n";
    $message .= "No. Booking: {$bookingNumber}\n";
    $message .= "Status terbaru: " . bookingStatusLabel($status) . "\n";

    if ($adminNotes !== null && trim($adminNotes) !== '') {
        $message .= "Catatan: " . trim($adminNotes) . "\n";
    }

    $message .= "\nJika ada pertanyaan, balas pesan ini.";

    return sendWhatsAppMessage($phone, $message);
}
