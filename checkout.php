<?php
declare(strict_types=1);

require_once __DIR__ . '/shared/page_presets.php';

requireCustomerLogin();

$customerId = (int) currentUserId();

if (countCartItems($db, $customerId) <= 0) {
    redirectWithMessage('/cart.php', 'error', 'Keranjang masih kosong.');
}

$customer = findUserById($db, $customerId);

if ($customer === null) {
    redirectWithMessage('/auth/login.php', 'error', 'Data customer tidak ditemukan.');
}

$paymentChannels = fetchTripayPaymentChannels(true);
$paymentChannelMap = [];

foreach ($paymentChannels as $channel) {
    $paymentChannelMap[strtoupper((string) ($channel['code'] ?? ''))] = $channel;
}

$pickChannelsByCodes = static function (array $codes) use ($paymentChannelMap): array {
    $picked = [];

    foreach ($codes as $code) {
        $normalizedCode = strtoupper(trim((string) $code));

        if ($normalizedCode !== '' && isset($paymentChannelMap[$normalizedCode])) {
            $picked[] = $paymentChannelMap[$normalizedCode];
        }
    }

    return $picked;
};

$virtualAccountChannels = $pickChannelsByCodes(['BRIVA', 'BCAVA', 'MANDIRIVA', 'BNIVA', 'PERMATAVA']);
$qrisChannels = $pickChannelsByCodes(['QRIS']);
$retailChannels = $pickChannelsByCodes(['ALFAMART', 'INDOMARET']);
$otherChannels = array_values(array_filter(
    $paymentChannels,
    static fn (array $channel): bool => !in_array(
        strtoupper((string) ($channel['code'] ?? '')),
        ['BRIVA', 'BCAVA', 'MANDIRIVA', 'BNIVA', 'PERMATAVA', 'QRIS', 'ALFAMART', 'INDOMARET'],
        true
    )
));

$defaultTripayMethod = strtoupper((string) (
    ($virtualAccountChannels[0]['code'] ?? '')
    ?: ($qrisChannels[0]['code'] ?? '')
    ?: ($retailChannels[0]['code'] ?? '')
    ?: ($otherChannels[0]['code'] ?? '')
));

if (isPostRequest()) {
    $payload = [
        'payment_method' => trim((string) requestInput('payment_method', 'transfer')),
        'tripay_method' => strtoupper(trim((string) requestInput('tripay_method', $defaultTripayMethod))),
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
        'courier' => trim((string) requestInput('courier', 'JNE')),
        'courier_service' => trim((string) requestInput('courier_service', '')),
        'shipping_cost' => (float) requestInput('shipping_cost', 0),
        'estimated_days' => trim((string) requestInput('estimated_days', '')),
        'voucher_discount' => (float) requestInput('voucher_discount', 0),
        'notes' => trim((string) requestInput('notes', 'Checkout dari website FITS MOTOR')),
    ];

    if ($payload['recipient_name'] === '' || $payload['recipient_phone'] === '' || $payload['delivery_address'] === '') {
        redirectWithMessage('/checkout.php', 'error', 'Alamat pengiriman belum lengkap.');
    }

    if ($payload['courier_service'] === '') {
        redirectWithMessage('/checkout.php', 'error', 'Pilih layanan pengiriman terlebih dahulu.');
    }

    if ($payload['payment_method'] === 'transfer' && $payload['tripay_method'] === '') {
        redirectWithMessage('/checkout.php', 'error', 'Pilih metode pembayaran terlebih dahulu.');
    }

    if ($payload['payment_method'] === 'transfer' && tripayIsConfigured() && findTripayPaymentChannel($payload['tripay_method']) === null) {
        redirectWithMessage('/checkout.php', 'error', 'Metode pembayaran yang dipilih sedang tidak aktif.');
    }

    $order = createOrderFromCart($db, $customer, $payload);

    if ($order === null) {
        redirectWithMessage('/checkout.php', 'error', 'Checkout gagal diproses. Pastikan keranjang berisi produk.');
    }

    $_SESSION['last_order_code'] = $order['order_code'];

    if (tripayIsConfigured() && $payload['payment_method'] === 'transfer') {
        $items = fetchOrderItems($db, (int) $order['id']);
        $tripayResult = createTripayTransaction($order, $items, $payload['tripay_method']);

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

            header('Location: ' . (string) $tripayData['checkout_url']);
            exit;
        }

        rollbackFailedCheckoutOrder($db, (int) $order['id']);
        redirectWithMessage('/checkout.php', 'error', (string) ($tripayResult['message'] ?? 'Tripay gagal membuat transaksi pembayaran.'));
    }

    sendOrderWhatsApp(
        normalizePhoneNumber((string) $customer['phone']),
        (string) ($order['order_number'] ?? $order['order_code']),
        (int) ($order['total'] ?? $order['total_amount']),
        (string) $order['payment_method']
    );

    redirectTo('/payment-instruction.php?order_code=' . urlencode((string) $order['order_code']));
}

$cartItems = fetchCartItems($db, $customerId);
$summary = fetchCartSummary($db, $customerId);
$addresses = fetchCustomerAddresses($db, $customerId);
$primaryAddress = $addresses[0] ?? null;
$flashError = getFlash('error');
$flashSuccess = getFlash('success');
$messageText = (string) ($flashError ?? $flashSuccess ?? '');

$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
$formatAddressSummary = static function (?array $address): string {
    if ($address === null) {
        return 'Belum memilih alamat pengiriman.';
    }

    $parts = array_filter([
        trim((string) ($address['address'] ?? '')),
        trim((string) ($address['district'] ?? '')),
        trim((string) ($address['city_name'] ?? '')),
        trim((string) ($address['province_name'] ?? '')),
        trim((string) ($address['postal_code'] ?? '')),
    ], static fn (string $part): bool => $part !== '');

    $phone = trim((string) ($address['phone'] ?? ''));
    $recipient = trim((string) ($address['recipient_name'] ?? ''));
    $summaryText = implode(', ', $parts);

    if ($recipient !== '') {
        $summaryText = $recipient . ($summaryText !== '' ? ' - ' . $summaryText : '');
    }

    if ($phone !== '') {
        $summaryText .= ($summaryText !== '' ? ' (' . $phone . ')' : $phone);
    }

    return $summaryText !== '' ? $summaryText : 'Belum memilih alamat pengiriman.';
};

$noticeHtml = $messageText !== ''
    ? '<div class="max-w-[1440px] mx-auto px-8 pt-6"><div class="border ' . ($flashSuccess !== null ? 'border-[#38A169]/40 bg-[#38A169]/10' : 'border-danger-red/40 bg-danger-red/10') . ' text-white px-5 py-4 rounded-lg">' . $escape($messageText) . '</div></div>'
    : '';

$addressCardsHtml = '';

foreach ($addresses as $index => $address) {
    $isPrimary = $index === 0;
    $addressCardsHtml .= '<button type="button" data-address-card data-address-id="' . (int) $address['id'] . '" class="' . ($isPrimary
        ? 'border-2 border-primary-container bg-surface-accent p-4 rounded-lg relative text-left w-full transition-all'
        : 'border border-white/10 p-4 rounded-lg opacity-70 hover:opacity-100 transition-all text-left w-full') . '">'
        . ($isPrimary ? '<div class="absolute top-4 right-4"><span class="bg-primary-container text-white text-[10px] px-2 py-1 font-bold rounded">UTAMA</span></div>' : '')
        . '<p class="font-bold text-white mb-1">' . $escape((string) ($address['label'] ?: $address['recipient_name'])) . ' (' . $escape((string) $address['recipient_name']) . ')</p>'
        . '<p class="text-on-surface-variant text-sm leading-relaxed">' . $escape($formatAddressSummary($address)) . '</p>'
        . '</button>';
}

if ($addressCardsHtml === '') {
    $addressCardsHtml = '<div class="border border-white/10 p-4 rounded-lg text-on-surface-variant">Alamat customer belum tersedia. Tambahkan alamat dulu sebelum checkout.</div>';
}

$summaryItemsHtml = '';

foreach ($cartItems as $item) {
    $unitPrice = (float) (($item['discount_price'] ?? null) !== null ? $item['discount_price'] : $item['price']);
    $summaryItemsHtml .= '<li class="flex items-start justify-between gap-4 border-b border-white/5 pb-3">'
        . '<div><p class="text-white font-semibold">' . $escape((string) $item['name']) . '</p><p class="text-white/60 text-sm">' . (int) $item['quantity'] . ' x ' . $escape(formatIdrText($unitPrice)) . '</p></div>'
        . '<span class="text-primary font-semibold">' . $escape(formatIdrText($unitPrice * (int) $item['quantity'])) . '</span>'
        . '</li>';
}

$summaryItemsHtml = $summaryItemsHtml !== '' ? $summaryItemsHtml : '<li class="text-text-secondary">Belum ada item di keranjang.</li>';
$summarySubtotalText = $escape(formatIdrText((float) ($summary['subtotal'] ?? 0)));
$summaryItemCount = max(0, (int) ($summary['total_quantity'] ?? 0));
$summaryAddressText = $escape($formatAddressSummary($primaryAddress));
$defaultShippingCost = 0;
$defaultCourierService = '';
$defaultEstimatedDays = '';

$paymentBadgeMap = [
    'BRIVA' => 'BRI',
    'BCAVA' => 'BCA',
    'MANDIRIVA' => 'MANDIRI',
    'BNIVA' => 'BNI',
    'PERMATAVA' => 'PERMATA',
    'QRIS' => 'QRIS',
    'ALFAMART' => 'ALFAMART',
    'INDOMARET' => 'INDOMARET',
];

$buildPaymentDescription = static function (array $channel): string {
    $code = strtoupper((string) ($channel['code'] ?? ''));
    $minimumAmount = max(0, (int) ($channel['minimum_amount'] ?? 0));
    $flatFee = max(0, (int) ($channel['total_fee']['flat'] ?? 0));
    $percentFee = trim((string) ($channel['total_fee']['percent'] ?? '0'));

    $parts = [];
    $parts[] = $code === 'QRIS'
        ? 'Scan QRIS universal'
        : 'Pembayaran otomatis via ' . trim((string) ($channel['name'] ?? $code));

    if ($minimumAmount > 0) {
        $parts[] = 'Min ' . formatIdrText($minimumAmount);
    }

    if ($flatFee > 0 || ($percentFee !== '' && $percentFee !== '0' && $percentFee !== '0.00')) {
        $feeText = [];

        if ($flatFee > 0) {
            $feeText[] = formatIdrText($flatFee);
        }

        if ($percentFee !== '' && $percentFee !== '0' && $percentFee !== '0.00') {
            $feeText[] = str_replace('.', ',', $percentFee) . '%';
        }

        $parts[] = 'Admin ' . implode(' + ', $feeText);
    }

    return implode(' • ', $parts);
};

$buildPaymentGuidance = static function (array $channel): string {
    $code = strtoupper((string) ($channel['code'] ?? ''));

    return match ($code) {
        'QRIS' => 'Setelah konfirmasi, Anda akan diarahkan ke halaman QRIS Tripay. Scan kode QR menggunakan mobile banking atau e-wallet yang mendukung QRIS.',
        'ALFAMART', 'INDOMARET' => 'Setelah konfirmasi, kode pembayaran retail akan dibuat dan dibayar di kasir gerai yang dipilih.',
        default => 'Setelah konfirmasi, nomor Virtual Account akan dibuat otomatis dan instruksi pembayaran tampil di halaman Tripay.',
    };
};

$buildPaymentGroupHtml = static function (string $title, string $icon, string $description, array $channels, string $emptyMessage) use ($escape, $paymentBadgeMap, $defaultTripayMethod, $buildPaymentDescription, $buildPaymentGuidance): string {
    ob_start();
    ?>
    <section class="border border-white/5 rounded-xl bg-surface-container/70 overflow-hidden">
        <div class="px-6 py-5 border-b border-white/5 bg-surface-card/90">
            <div class="flex items-center gap-3 mb-2">
                <span class="material-symbols-outlined text-primary-container"><?= $escape($icon) ?></span>
                <h3 class="text-white font-bold uppercase tracking-wider"><?= $escape($title) ?></h3>
            </div>
            <p class="text-text-secondary text-sm"><?= $escape($description) ?></p>
        </div>
        <div class="p-6">
            <?php if ($channels === []): ?>
                <div class="border border-dashed border-white/10 rounded-lg p-4 text-sm text-text-secondary">
                    <?= $escape($emptyMessage) ?>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($channels as $channel): ?>
                        <?php
                        $code = strtoupper((string) ($channel['code'] ?? ''));
                        $selected = $code === $defaultTripayMethod;
                        $badge = $paymentBadgeMap[$code] ?? $code;
                        $name = trim((string) ($channel['name'] ?? $code));
                        $detail = $buildPaymentDescription($channel);
                        $guidance = $buildPaymentGuidance($channel);
                        ?>
                        <button
                            type="button"
                            data-payment-option
                            data-payment-method="transfer"
                            data-tripay-method="<?= $escape($code) ?>"
                            data-payment-name="<?= $escape($name) ?>"
                            data-payment-detail="<?= $escape($detail) ?>"
                            data-payment-guidance="<?= $escape($guidance) ?>"
                            class="<?= $selected
                                ? 'payment-option-card border-2 border-primary-container bg-surface-accent text-white rounded-xl p-4 text-left transition-all shadow-lg'
                                : 'payment-option-card border border-white/10 bg-surface-container text-white rounded-xl p-4 text-left transition-all hover:border-primary-container' ?>"
                        >
                            <div class="flex items-start justify-between gap-4 mb-4">
                                <div>
                                    <span class="inline-flex items-center justify-center min-w-[74px] px-3 py-2 rounded bg-white/10 text-xs font-black uppercase tracking-[0.18em]"><?= $escape($badge) ?></span>
                                    <p class="mt-3 text-base font-semibold"><?= $escape($name) ?></p>
                                </div>
                                <span class="material-symbols-outlined <?= $selected ? 'text-primary-container' : 'text-white/40' ?>" data-payment-check>check_circle</span>
                            </div>
                            <p class="<?= $selected ? 'text-white/80' : 'text-text-secondary' ?> text-sm leading-relaxed" data-payment-copy><?= $escape($detail) ?></p>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php

    return (string) ob_get_clean();
};

$selectedPaymentChannel = $defaultTripayMethod !== '' ? findTripayPaymentChannel($defaultTripayMethod) : null;
$selectedPaymentName = $selectedPaymentChannel !== null ? trim((string) ($selectedPaymentChannel['name'] ?? $defaultTripayMethod)) : 'Belum memilih metode pembayaran.';
$selectedPaymentDetail = $selectedPaymentChannel !== null ? $buildPaymentDescription($selectedPaymentChannel) : 'Pilih metode pembayaran pada langkah ketiga.';
$selectedPaymentGuidance = $selectedPaymentChannel !== null ? $buildPaymentGuidance($selectedPaymentChannel) : 'Pilih metode pembayaran terlebih dahulu untuk melanjutkan ke Tripay.';

$shippingCostUrl = fitsMotorUrl('/api/shipping-cost.php');
$paymentCreateUrl = fitsMotorUrl('/api/payment-create.php');
$savedAddressesJson = json_encode($addresses, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$savedAddressesJson = is_string($savedAddressesJson) ? $savedAddressesJson : '[]';
$subtotal = (int) ($summary['subtotal'] ?? 0);
$totalWeight = max(1, (int) ($summary['total_weight'] ?? 1));

ob_start();
?>
<main class="pt-24 pb-20 max-w-[1440px] mx-auto px-8">
    <?= $noticeHtml ?>

    <section class="max-w-5xl mx-auto mb-12">
        <div class="relative">
            <div class="absolute top-6 left-0 w-full h-[2px] bg-white/10"></div>
            <div id="checkout-progress-bar" class="absolute top-6 left-0 h-[2px] bg-primary-container transition-all duration-300" style="width:0%"></div>
            <div class="grid grid-cols-3 gap-4 relative z-10">
                <?php foreach ([1 => 'Alamat', 2 => 'Pengiriman', 3 => 'Pembayaran'] as $stepNumber => $stepLabel): ?>
                    <div data-checkout-step="<?= $stepNumber ?>" class="flex flex-col items-center text-center">
                        <div data-step-circle class="<?= $stepNumber === 1 ? 'w-12 h-12 rounded-full bg-primary-container border-2 border-primary-container text-white flex items-center justify-center font-bold transition-all' : 'w-12 h-12 rounded-full bg-surface-container-high border-2 border-white/10 text-text-secondary flex items-center justify-center font-bold transition-all' ?>">
                            <?= $stepNumber ?>
                        </div>
                        <span data-step-label class="<?= $stepNumber === 1 ? 'text-primary' : 'text-text-secondary' ?> text-xs mt-3 uppercase font-bold tracking-[0.2em] transition-all">
                            <?= $escape($stepLabel) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-[minmax(0,1.2fr)_420px] gap-8 items-start">
        <div class="space-y-6">
            <section data-step-panel="1" class="bg-surface-card p-8 border border-white/5 rounded-xl shadow-xl">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-8">
                    <div>
                        <p class="text-primary-container uppercase tracking-[0.25em] text-xs font-bold mb-3">Langkah 1</p>
                        <h1 class="font-headline-md text-headline-md text-white bebas mb-2">Alamat Pengiriman</h1>
                        <p class="text-text-secondary max-w-2xl">Pilih alamat tujuan terlebih dahulu. Begitu alamat berubah, opsi ongkir juga ikut diperbarui.</p>
                    </div>
                    <a class="inline-flex items-center gap-2 text-primary-container text-sm font-bold" href="<?= $escape(fitsMotorUrl('/customer/addresses.php')) ?>">
                        <span class="material-symbols-outlined text-sm">add_circle</span>
                        TAMBAH / UBAH ALAMAT
                    </a>
                </div>
                <div data-address-list class="grid grid-cols-1 gap-4">
                    <?= $addressCardsHtml ?>
                </div>
                <div class="mt-8 pt-6 border-t border-white/5 flex flex-col sm:flex-row gap-4 sm:items-center sm:justify-between">
                    <p class="text-text-secondary text-sm">Alamat yang dipilih akan dipakai untuk menghitung ongkir dan pembuatan pesanan.</p>
                    <button type="button" data-step-next="2" class="px-6 py-3 bg-primary-container text-white rounded-lg font-bold uppercase tracking-wider">
                        Lanjut ke Pengiriman
                    </button>
                </div>
            </section>

            <section data-step-panel="2" class="hidden bg-surface-card p-8 border border-white/5 rounded-xl shadow-xl">
                <div class="mb-8">
                    <p class="text-primary-container uppercase tracking-[0.25em] text-xs font-bold mb-3">Langkah 2</p>
                    <h2 class="font-headline-md text-headline-md text-white bebas mb-2">Pilih Pengiriman</h2>
                    <p class="text-text-secondary">Pilih kurir, lalu tentukan layanan yang paling sesuai. Halaman pembayaran baru dibuka setelah layanan pengiriman dipilih.</p>
                </div>

                <div class="mb-6">
                    <p class="text-white font-semibold mb-4">Kurir Tersedia</p>
                    <div class="flex gap-4 overflow-x-auto pb-2">
                        <button type="button" data-courier-btn data-courier="JNE" class="px-6 py-3 bg-primary-container text-white bebas text-lg rounded-lg whitespace-nowrap">JNE EXPRESS</button>
                        <button type="button" data-courier-btn data-courier="SICEPAT" class="px-6 py-3 bg-surface-container text-on-surface-variant bebas text-lg rounded-lg border border-white/5 whitespace-nowrap hover:border-primary-container">SICEPAT</button>
                        <button type="button" data-courier-btn data-courier="ANTERAJA" class="px-6 py-3 bg-surface-container text-on-surface-variant bebas text-lg rounded-lg border border-white/5 whitespace-nowrap hover:border-primary-container">ANTERAJA</button>
                    </div>
                </div>

                <div class="border border-white/5 rounded-xl bg-surface-container/70 p-5 mb-6">
                    <div class="flex items-center justify-between gap-4 mb-3">
                        <h3 class="text-white font-semibold">Layanan Pengiriman</h3>
                        <span id="shipping-source-chip" class="text-[11px] uppercase tracking-[0.2em] text-text-secondary">Realtime</span>
                    </div>
                    <div id="shipping-service-list" class="space-y-3">
                        <div class="p-4 bg-surface-container border border-white/5 rounded-lg text-text-secondary">Memuat layanan pengiriman...</div>
                    </div>
                </div>

                <div class="border border-white/5 rounded-xl bg-surface-container/70 p-5">
                    <h3 class="text-white font-semibold mb-3">Pengiriman Terpilih</h3>
                    <p id="shipping-selection-preview" class="text-text-secondary text-sm leading-relaxed">Belum ada layanan pengiriman yang dipilih.</p>
                </div>

                <div class="mt-8 pt-6 border-t border-white/5 flex flex-col sm:flex-row gap-4 sm:items-center sm:justify-between">
                    <button type="button" data-step-prev="1" class="px-6 py-3 border border-white/10 text-white rounded-lg font-bold uppercase tracking-wider hover:border-primary-container">
                        Kembali ke Alamat
                    </button>
                    <button type="button" data-step-next="3" class="px-6 py-3 bg-primary-container text-white rounded-lg font-bold uppercase tracking-wider">
                        Lanjut ke Pembayaran
                    </button>
                </div>
            </section>

            <section data-step-panel="3" class="hidden bg-surface-card p-8 border border-white/5 rounded-xl shadow-xl">
                <div class="mb-8">
                    <p class="text-primary-container uppercase tracking-[0.25em] text-xs font-bold mb-3">Langkah 3</p>
                    <h2 class="font-headline-md text-headline-md text-white bebas mb-2">Metode Pembayaran</h2>
                    <p class="text-text-secondary">Pilih kanal pembayaran yang aktif di merchant Anda. QRIS kini memakai channel Tripay yang benar dan akan mengarah ke halaman scan QR resmi.</p>
                </div>

                <div class="space-y-6">
                    <?= $buildPaymentGroupHtml(
                        'Transfer Virtual Account',
                        'account_balance',
                        'Nomor Virtual Account dibuat otomatis setelah pesanan dikonfirmasi.',
                        $virtualAccountChannels,
                        'Belum ada Virtual Account aktif di merchant Tripay ini.'
                    ) ?>

                    <?= $buildPaymentGroupHtml(
                        'QRIS & E-Wallet',
                        'qr_code_2',
                        'Scan QRIS dari mobile banking atau e-wallet yang mendukung standar QRIS nasional.',
                        $qrisChannels,
                        'Channel QRIS belum aktif di merchant Tripay ini. Aktifkan QRIS di dashboard merchant jika ingin dipakai.'
                    ) ?>

                    <?= $buildPaymentGroupHtml(
                        'Gerai Retail',
                        'storefront',
                        'Pembayaran melalui kasir retail jika channel retail aktif di merchant Anda.',
                        $retailChannels,
                        'Belum ada channel retail aktif di merchant sandbox ini.'
                    ) ?>

                    <?php if ($otherChannels !== []): ?>
                        <?= $buildPaymentGroupHtml(
                            'Metode Lainnya',
                            'payments',
                            'Metode tambahan yang aktif di merchant Tripay Anda.',
                            $otherChannels,
                            'Tidak ada metode tambahan yang aktif.'
                        ) ?>
                    <?php endif; ?>
                </div>

                <div class="mt-6 border border-primary-container/30 bg-surface-accent rounded-xl p-5">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary-container">info</span>
                        <div>
                            <p class="text-white font-semibold mb-1">Petunjuk Pembayaran</p>
                            <p id="payment-guidance" class="text-white/75 text-sm leading-relaxed"><?= $escape($selectedPaymentGuidance) ?></p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-white/5 flex flex-col lg:flex-row gap-4 lg:items-center lg:justify-between">
                    <button type="button" data-step-prev="2" class="px-6 py-3 border border-white/10 text-white rounded-lg font-bold uppercase tracking-wider hover:border-primary-container">
                        Kembali ke Pengiriman
                    </button>
                    <div class="w-full lg:w-auto lg:min-w-[320px]">
                        <button id="checkout-submit" type="submit" form="checkout-form" class="w-full bg-primary-container text-white py-4 rounded-lg bebas text-2xl shadow-lg hover:shadow-primary/20 transition-all">
                            KONFIRMASI &amp; BAYAR SEKARANG
                        </button>
                        <p class="text-[11px] text-text-secondary mt-3 text-center">Pesanan baru dibuat jika alamat, kurir, dan metode pembayaran sudah valid.</p>
                    </div>
                </div>
            </section>
        </div>

        <aside class="bg-surface-card p-8 border border-white/5 rounded-xl shadow-xl sticky top-28">
            <div class="flex items-center justify-between gap-4 mb-6">
                <h2 class="font-headline-md text-headline-md text-white bebas">Ringkasan Pesanan</h2>
                <span class="text-text-secondary text-sm"><?= $summaryItemCount ?> item</span>
            </div>

            <ul class="space-y-3 mb-8">
                <?= $summaryItemsHtml ?>
            </ul>

            <div class="space-y-5 mb-8">
                <div class="border border-white/5 rounded-lg p-4 bg-surface-container/70">
                    <p class="text-[11px] uppercase tracking-[0.22em] text-text-secondary mb-2">Alamat</p>
                    <p id="summary-address" class="text-white text-sm leading-relaxed"><?= $summaryAddressText ?></p>
                </div>
                <div class="border border-white/5 rounded-lg p-4 bg-surface-container/70">
                    <p class="text-[11px] uppercase tracking-[0.22em] text-text-secondary mb-2">Pengiriman</p>
                    <p id="summary-shipping-detail" class="text-white text-sm leading-relaxed">Belum memilih layanan pengiriman.</p>
                </div>
                <div class="border border-white/5 rounded-lg p-4 bg-surface-container/70">
                    <p class="text-[11px] uppercase tracking-[0.22em] text-text-secondary mb-2">Pembayaran</p>
                    <p id="summary-payment-name" class="text-white font-semibold"><?= $escape($selectedPaymentName) ?></p>
                    <p id="summary-payment-detail" class="text-text-secondary text-sm leading-relaxed mt-1"><?= $escape($selectedPaymentDetail) ?></p>
                </div>
            </div>

            <div class="space-y-3 text-white/80">
                <div class="flex items-center justify-between">
                    <span>Subtotal</span>
                    <strong id="summary-subtotal" class="text-white"><?= $summarySubtotalText ?></strong>
                </div>
                <div class="flex items-center justify-between">
                    <span>Ongkir</span>
                    <strong id="summary-shipping" class="text-white"><?= $escape(formatIdrText(0)) ?></strong>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-white/5">
                    <span class="text-white font-bold">Total</span>
                    <strong id="summary-total" class="text-primary-container text-2xl bebas"><?= $summarySubtotalText ?></strong>
                </div>
            </div>
        </aside>
    </section>

    <form id="checkout-form" class="hidden" method="post" action="<?= $escape(fitsMotorUrl('/checkout.php')) ?>">
        <input type="hidden" name="recipient_name" id="recipient-name" value="<?= $escape((string) ($primaryAddress['recipient_name'] ?? $customer['name'] ?? '')) ?>"/>
        <input type="hidden" name="recipient_phone" id="recipient-phone" value="<?= $escape((string) ($primaryAddress['phone'] ?? $customer['phone'] ?? '')) ?>"/>
        <input type="hidden" name="payment_method" id="payment-method" value="transfer"/>
        <input type="hidden" name="tripay_method" id="tripay-method" value="<?= $escape($defaultTripayMethod) ?>"/>
        <input type="hidden" name="delivery_method" id="delivery-method" value="delivery"/>
        <input type="hidden" name="province_id" id="province-id" value="<?= $escape((string) ($primaryAddress['province_id'] ?? '')) ?>"/>
        <input type="hidden" name="province_name" id="province-name" value="<?= $escape((string) ($primaryAddress['province_name'] ?? '')) ?>"/>
        <input type="hidden" name="city_id" id="city-id" value="<?= $escape((string) ($primaryAddress['city_id'] ?? '')) ?>"/>
        <input type="hidden" name="city_name" id="city-name" value="<?= $escape((string) ($primaryAddress['city_name'] ?? '')) ?>"/>
        <input type="hidden" name="district" id="district" value="<?= $escape((string) ($primaryAddress['district'] ?? '')) ?>"/>
        <input type="hidden" name="postal_code" id="postal-code" value="<?= $escape((string) ($primaryAddress['postal_code'] ?? '')) ?>"/>
        <input type="hidden" name="delivery_address" id="delivery-address" value="<?= $escape((string) ($primaryAddress['address'] ?? '')) ?>"/>
        <input type="hidden" name="courier" id="courier" value="JNE"/>
        <input type="hidden" name="courier_service" id="courier-service" value="<?= $escape($defaultCourierService) ?>"/>
        <input type="hidden" name="shipping_cost" id="shipping-cost" value="<?= $defaultShippingCost ?>"/>
        <input type="hidden" name="estimated_days" id="estimated-days" value="<?= $escape($defaultEstimatedDays) ?>"/>
        <input type="hidden" name="voucher_discount" value="0"/>
        <input type="hidden" name="notes" id="notes" value="Checkout dari website FITS MOTOR"/>
    </form>
</main>
<?php
$checkoutMainHtml = (string) ob_get_clean();

$checkoutScript = <<<HTML
<script>
document.addEventListener('DOMContentLoaded', function () {
    const savedAddresses = {$savedAddressesJson};
    const shippingCostUrl = '{$shippingCostUrl}';
    const paymentCreateUrl = '{$paymentCreateUrl}';
    const subtotal = {$subtotal};
    const totalWeight = {$totalWeight};
    const maxStep = 3;
    const addressCards = Array.from(document.querySelectorAll('[data-address-card]'));
    const courierButtons = Array.from(document.querySelectorAll('[data-courier-btn]'));
    const paymentButtons = Array.from(document.querySelectorAll('[data-payment-option]'));
    const stepPanels = Array.from(document.querySelectorAll('[data-step-panel]'));
    const stepIndicators = Array.from(document.querySelectorAll('[data-checkout-step]'));
    const nextButtons = Array.from(document.querySelectorAll('[data-step-next]'));
    const prevButtons = Array.from(document.querySelectorAll('[data-step-prev]'));
    const serviceList = document.getElementById('shipping-service-list');
    const progressBar = document.getElementById('checkout-progress-bar');
    const shippingSourceChip = document.getElementById('shipping-source-chip');
    const shippingPreview = document.getElementById('shipping-selection-preview');
    const paymentGuidance = document.getElementById('payment-guidance');
    const submitButton = document.getElementById('checkout-submit');
    const checkoutForm = document.getElementById('checkout-form');
    const summaryAddress = document.getElementById('summary-address');
    const summaryShipping = document.getElementById('summary-shipping');
    const summaryShippingDetail = document.getElementById('summary-shipping-detail');
    const summaryTotal = document.getElementById('summary-total');
    const summaryPaymentName = document.getElementById('summary-payment-name');
    const summaryPaymentDetail = document.getElementById('summary-payment-detail');
    const state = {
        currentStep: 1,
        address: savedAddresses[0] || null,
        courier: 'JNE',
        shippingService: null,
        paymentMethod: 'transfer',
        tripayMethod: document.getElementById('tripay-method') ? document.getElementById('tripay-method').value : '',
        paymentName: document.getElementById('summary-payment-name') ? document.getElementById('summary-payment-name').textContent : 'Belum memilih metode pembayaran.',
        paymentDetail: document.getElementById('summary-payment-detail') ? document.getElementById('summary-payment-detail').textContent : 'Pilih metode pembayaran pada langkah ketiga.'
    };

    const ids = {
        recipientName: document.getElementById('recipient-name'),
        recipientPhone: document.getElementById('recipient-phone'),
        paymentMethod: document.getElementById('payment-method'),
        tripayMethod: document.getElementById('tripay-method'),
        provinceId: document.getElementById('province-id'),
        provinceName: document.getElementById('province-name'),
        cityId: document.getElementById('city-id'),
        cityName: document.getElementById('city-name'),
        district: document.getElementById('district'),
        postalCode: document.getElementById('postal-code'),
        deliveryAddress: document.getElementById('delivery-address'),
        courier: document.getElementById('courier'),
        courierService: document.getElementById('courier-service'),
        shippingCost: document.getElementById('shipping-cost'),
        estimatedDays: document.getElementById('estimated-days'),
        notes: document.getElementById('notes')
    };

    const classes = {
        addressActive: 'border-2 border-primary-container bg-surface-accent p-4 rounded-lg relative text-left w-full transition-all',
        addressIdle: 'border border-white/10 p-4 rounded-lg opacity-70 hover:opacity-100 transition-all text-left w-full',
        courierActive: 'px-6 py-3 bg-primary-container text-white bebas text-lg rounded-lg whitespace-nowrap',
        courierIdle: 'px-6 py-3 bg-surface-container text-on-surface-variant bebas text-lg rounded-lg border border-white/5 whitespace-nowrap hover:border-primary-container',
        serviceActive: 'flex items-center justify-between p-4 bg-surface-container border border-primary-container rounded-lg cursor-pointer',
        serviceIdle: 'flex items-center justify-between p-4 bg-surface-container border border-white/5 rounded-lg hover:border-white/20 transition-all cursor-pointer',
        paymentActive: 'payment-option-card border-2 border-primary-container bg-surface-accent text-white rounded-xl p-4 text-left transition-all shadow-lg',
        paymentIdle: 'payment-option-card border border-white/10 bg-surface-container text-white rounded-xl p-4 text-left transition-all hover:border-primary-container',
        stepCircleActive: 'w-12 h-12 rounded-full bg-primary-container border-2 border-primary-container text-white flex items-center justify-center font-bold transition-all',
        stepCircleIdle: 'w-12 h-12 rounded-full bg-surface-container-high border-2 border-white/10 text-text-secondary flex items-center justify-center font-bold transition-all',
        stepLabelActive: 'text-primary text-xs mt-3 uppercase font-bold tracking-[0.2em] transition-all',
        stepLabelIdle: 'text-text-secondary text-xs mt-3 uppercase font-bold tracking-[0.2em] transition-all'
    };

    const formatIdr = function (value) {
        return 'IDR ' + new Intl.NumberFormat('id-ID').format(value || 0);
    };

    const notify = function (message, tone) {
        if (window.fitsMotorNotify) {
            window.fitsMotorNotify(message, tone);
            return;
        }

        window.alert(message);
    };

    const postForm = async function (url, payload) {
        const body = new URLSearchParams();

        Object.keys(payload || {}).forEach(function (key) {
            const value = payload[key];

            if (value === null || typeof value === 'undefined') {
                return;
            }

            body.append(key, String(value));
        });

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                'Accept': 'application/json'
            },
            body: body.toString()
        });

        let result = {};

        try {
            result = await response.json();
        } catch (error) {
            result = {};
        }

        result.__ok = response.ok;

        return result;
    };

    const formatAddress = function (address) {
        if (!address) {
            return 'Belum memilih alamat pengiriman.';
        }

        const parts = [
            address.address || '',
            address.district || '',
            address.city_name || '',
            address.province_name || '',
            address.postal_code || ''
        ].filter(Boolean);
        const recipient = address.recipient_name || '';
        const phone = address.phone || '';
        let text = parts.join(', ');

        if (recipient) {
            text = recipient + (text ? ' - ' + text : '');
        }

        if (phone) {
            text += (text ? ' (' + phone + ')' : phone);
        }

        return text || 'Belum memilih alamat pengiriman.';
    };

    const updateStepUi = function () {
        stepPanels.forEach(function (panel) {
            const panelStep = Number(panel.dataset.stepPanel || '1');
            panel.classList.toggle('hidden', panelStep !== state.currentStep);
        });

        stepIndicators.forEach(function (indicator) {
            const step = Number(indicator.dataset.checkoutStep || '1');
            const circle = indicator.querySelector('[data-step-circle]');
            const label = indicator.querySelector('[data-step-label]');
            const active = step === state.currentStep;
            const completed = step < state.currentStep;

            if (circle) {
                circle.className = active || completed ? classes.stepCircleActive : classes.stepCircleIdle;
                circle.textContent = completed ? '✓' : String(step);
            }

            if (label) {
                label.className = active || completed ? classes.stepLabelActive : classes.stepLabelIdle;
            }
        });

        if (progressBar) {
            progressBar.style.width = String(((state.currentStep - 1) / (maxStep - 1)) * 100) + '%';
        }
    };

    const syncAddress = function () {
        if (!state.address) {
            return;
        }

        ids.recipientName.value = state.address.recipient_name || '';
        ids.recipientPhone.value = state.address.phone || '';
        ids.provinceId.value = state.address.province_id || '';
        ids.provinceName.value = state.address.province_name || '';
        ids.cityId.value = state.address.city_id || '';
        ids.cityName.value = state.address.city_name || '';
        ids.district.value = state.address.district || '';
        ids.postalCode.value = state.address.postal_code || '';
        ids.deliveryAddress.value = state.address.address || '';

        if (summaryAddress) {
            summaryAddress.textContent = formatAddress(state.address);
        }
    };

    const updateSummaryTotals = function () {
        const shippingCost = parseInt(ids.shippingCost.value || '0', 10) || 0;

        if (summaryShipping) {
            summaryShipping.textContent = formatIdr(shippingCost);
        }

        if (summaryTotal) {
            summaryTotal.textContent = formatIdr(subtotal + shippingCost);
        }
    };

    const resetShippingSelection = function (message) {
        state.shippingService = null;
        ids.courierService.value = '';
        ids.shippingCost.value = '0';
        ids.estimatedDays.value = '';

        if (summaryShippingDetail) {
            summaryShippingDetail.textContent = message || 'Belum memilih layanan pengiriman.';
        }

        if (shippingPreview) {
            shippingPreview.textContent = message || 'Belum ada layanan pengiriman yang dipilih.';
        }

        updateSummaryTotals();
    };

    const updatePaymentSummary = function () {
        if (summaryPaymentName) {
            summaryPaymentName.textContent = state.paymentName || 'Belum memilih metode pembayaran.';
        }

        if (summaryPaymentDetail) {
            summaryPaymentDetail.textContent = state.paymentDetail || 'Pilih metode pembayaran pada langkah ketiga.';
        }
    };

    const validateStep = function (step) {
        if (step === 1 && !state.address) {
            notify('Pilih alamat pengiriman terlebih dahulu.');
            return false;
        }

        if (step === 2 && !ids.courierService.value) {
            notify('Pilih layanan pengiriman terlebih dahulu.');
            return false;
        }

        if (step === 3 && !ids.tripayMethod.value) {
            notify('Pilih metode pembayaran terlebih dahulu.');
            return false;
        }

        return true;
    };

    const goToStep = function (step) {
        state.currentStep = Math.min(Math.max(step, 1), maxStep);
        updateStepUi();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    const setActiveAddress = function (addressId) {
        state.address = savedAddresses.find(function (entry) {
            return String(entry.id) === String(addressId);
        }) || savedAddresses[0] || null;

        addressCards.forEach(function (card) {
            const active = card.dataset.addressId === String(addressId);
            card.className = active ? classes.addressActive : classes.addressIdle;
        });

        syncAddress();
        resetShippingSelection('Alamat berubah. Pilih ulang layanan pengiriman untuk alamat ini.');
        loadServices();
    };

    const setActiveCourier = function (courier) {
        state.courier = courier;
        ids.courier.value = courier;
        resetShippingSelection('Kurir berubah. Pilih salah satu layanan yang tersedia.');

        courierButtons.forEach(function (button) {
            const active = button.dataset.courier === courier;
            button.className = active ? classes.courierActive : classes.courierIdle;
        });

        loadServices();
    };

    const setActivePayment = function (button) {
        state.paymentMethod = button.dataset.paymentMethod || 'transfer';
        state.tripayMethod = button.dataset.tripayMethod || '';
        state.paymentName = button.dataset.paymentName || 'Belum memilih metode pembayaran.';
        state.paymentDetail = button.dataset.paymentDetail || 'Pilih metode pembayaran pada langkah ketiga.';
        ids.paymentMethod.value = state.paymentMethod;
        ids.tripayMethod.value = state.tripayMethod;

        paymentButtons.forEach(function (entry) {
            const active = entry === button;
            entry.className = active ? classes.paymentActive : classes.paymentIdle;

            const icon = entry.querySelector('[data-payment-check]');
            if (icon) {
                icon.className = 'material-symbols-outlined ' + (active ? 'text-primary-container' : 'text-white/40');
            }

            const copy = entry.querySelector('[data-payment-copy]');
            if (copy) {
                copy.className = (active ? 'text-white/80' : 'text-text-secondary') + ' text-sm leading-relaxed';
            }
        });

        if (paymentGuidance) {
            paymentGuidance.textContent = button.dataset.paymentGuidance || 'Pilih metode pembayaran terlebih dahulu untuk melanjutkan ke Tripay.';
        }

        updatePaymentSummary();
    };

    const selectService = function (service) {
        state.shippingService = service;
        ids.courierService.value = service.service || '';
        ids.shippingCost.value = String(service.cost || 0);
        ids.estimatedDays.value = service.etd || '';

        Array.from(serviceList.querySelectorAll('[data-service-option]')).forEach(function (node) {
            const active = node.dataset.service === service.service;
            node.className = active ? classes.serviceActive : classes.serviceIdle;

            const circle = node.querySelector('[data-service-circle]');
            if (circle) {
                circle.className = active
                    ? 'w-5 h-5 rounded-full border-4 border-primary-container'
                    : 'w-5 h-5 rounded-full border-2 border-white/20';
            }

            const price = node.querySelector('[data-service-price]');
            if (price) {
                price.className = active ? 'font-data-mono text-primary-container' : 'font-data-mono text-white';
            }
        });

        const shippingText = (state.courier + ' ' + (service.service || '') + ' - Estimasi ' + (service.etd || '-') + ' - ' + formatIdr(service.cost || 0)).trim();

        if (summaryShippingDetail) {
            summaryShippingDetail.textContent = shippingText;
        }

        if (shippingPreview) {
            shippingPreview.textContent = 'Kurir ' + state.courier + ' dengan layanan ' + (service.service || '-') + '. Estimasi tiba ' + (service.etd || '-') + ' dan biaya ' + formatIdr(service.cost || 0) + '.';
        }

        updateSummaryTotals();
    };

    async function loadServices() {
        if (!state.address || !state.address.city_id) {
            serviceList.innerHTML = '<div class="p-4 bg-surface-container border border-white/5 rounded-lg text-text-secondary">Pilih alamat tujuan terlebih dahulu.</div>';
            resetShippingSelection('Pilih alamat tujuan terlebih dahulu.');
            return;
        }

        serviceList.innerHTML = '<div class="p-4 bg-surface-container border border-white/5 rounded-lg text-text-secondary">Memuat layanan pengiriman...</div>';

        try {
            const response = await fetch(shippingCostUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    courier: state.courier,
                    city_id: state.address.city_id,
                    city_name: state.address.city_name || '',
                    province_name: state.address.province_name || '',
                    district: state.address.district || '',
                    postal_code: state.address.postal_code || '',
                    weight: totalWeight
                })
            });
            const result = await response.json();
            const services = Array.isArray(result.services) ? result.services : [];

            if (shippingSourceChip) {
                shippingSourceChip.textContent = result.source === 'rajaongkir-v2' ? 'RajaOngkir Live' : 'Fallback';
            }

            if (services.length === 0) {
                serviceList.innerHTML = '<div class="p-4 bg-surface-container border border-white/5 rounded-lg text-text-secondary">Layanan pengiriman tidak tersedia untuk alamat ini.</div>';
                resetShippingSelection('Layanan pengiriman tidak tersedia untuk alamat ini.');
                return;
            }

            serviceList.innerHTML = services.map(function (service, index) {
                return '<button type="button" data-service-option data-service="' + String(service.service || '') + '" class="' + (index === 0 ? classes.serviceActive : classes.serviceIdle) + '">'
                    + '<div class="flex items-center gap-3"><div data-service-circle class="' + (index === 0 ? 'w-5 h-5 rounded-full border-4 border-primary-container' : 'w-5 h-5 rounded-full border-2 border-white/20') + '"></div><div><p class="font-bold text-white">' + String(service.service || '') + ' (' + String(service.etd || '-') + ')</p><p class="text-xs text-text-secondary">' + String(service.description || 'Layanan pengiriman') + '</p></div></div>'
                    + '<span data-service-price class="' + (index === 0 ? 'font-data-mono text-primary-container' : 'font-data-mono text-white') + '">' + formatIdr(service.cost || 0) + '</span>'
                    + '</button>';
            }).join('');

            Array.from(serviceList.querySelectorAll('[data-service-option]')).forEach(function (button, index) {
                const service = services[index];
                button.addEventListener('click', function () {
                    selectService(service);
                });
            });

            selectService(services[0]);
        } catch (error) {
            serviceList.innerHTML = '<div class="p-4 bg-surface-container border border-white/5 rounded-lg text-text-secondary">Gagal menghitung ongkir. Coba lagi.</div>';
            resetShippingSelection('Gagal menghitung ongkir. Coba lagi.');
        }
    }

    addressCards.forEach(function (card) {
        card.addEventListener('click', function () {
            setActiveAddress(card.dataset.addressId || '');
        });
    });

    courierButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            setActiveCourier(button.dataset.courier || 'JNE');
        });
    });

    paymentButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            setActivePayment(button);
        });
    });

    nextButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const nextStep = Number(button.dataset.stepNext || '1');

            if (!validateStep(state.currentStep)) {
                return;
            }

            goToStep(nextStep);
        });
    });

    prevButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const prevStep = Number(button.dataset.stepPrev || '1');
            goToStep(prevStep);
        });
    });

    if (checkoutForm && submitButton) {
        checkoutForm.addEventListener('submit', async function (event) {
            event.preventDefault();

            if (!validateStep(1) || !validateStep(2) || !validateStep(3)) {
                return;
            }

            submitButton.disabled = true;
            submitButton.textContent = 'MEMPROSES...';

            try {
                const payload = {
                    recipient_name: ids.recipientName.value,
                    recipient_phone: ids.recipientPhone.value,
                    payment_method: ids.paymentMethod.value,
                    tripay_method: ids.tripayMethod.value,
                    delivery_method: 'delivery',
                    province_id: ids.provinceId.value,
                    province_name: ids.provinceName.value,
                    city_id: ids.cityId.value,
                    city_name: ids.cityName.value,
                    district: ids.district.value,
                    postal_code: ids.postalCode.value,
                    delivery_address: ids.deliveryAddress.value,
                    courier: ids.courier.value,
                    courier_service: ids.courierService.value,
                    shipping_cost: ids.shippingCost.value,
                    estimated_days: ids.estimatedDays.value,
                    notes: ids.notes ? ids.notes.value : ''
                };

                const result = await postForm(paymentCreateUrl, payload);

                if (result.redirect_url) {
                    window.location.href = result.redirect_url;
                    return;
                }

                if (!result.__ok && result.redirect_url) {
                    window.location.href = result.redirect_url;
                    return;
                }

                notify(result.message || 'Checkout gagal diproses.');
            } catch (error) {
                notify('Checkout gagal diproses.');
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'KONFIRMASI & BAYAR SEKARANG';
            }
        });
    }

    syncAddress();
    updateSummaryTotals();
    updatePaymentSummary();
    updateStepUi();

    if (addressCards.length > 0) {
        setActiveAddress(addressCards[0].dataset.addressId || '');
    }

    if (paymentButtons.length > 0) {
        const preferredPaymentButton = paymentButtons.find(function (button) {
            return button.dataset.tripayMethod === state.tripayMethod;
        }) || paymentButtons[0];
        setActivePayment(preferredPaymentButton);
    }
});
</script>
HTML;

$options = fitsMotorCommercePageOptions('checkout');
$options['hide_selectors'] = [];
$options['extra_body_end'] .= $checkoutScript;
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static function (string $html) use ($checkoutMainHtml): string {
        return fitsMotorPregReplaceFirst('/<main\b[^>]*>.*?<\/main>/is', $checkoutMainHtml, $html);
    }
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('keranjang_checkout_fits_motor/code.php'),
    $options
);
