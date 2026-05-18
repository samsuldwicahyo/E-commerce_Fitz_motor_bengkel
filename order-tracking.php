<?php
declare(strict_types=1);

require_once __DIR__ . '/shared/page_presets.php';

$query = trim((string) queryInput('query', ''));
$order = $query !== '' ? searchTrackableOrder($db, $query) : null;
$orderItems = $order !== null ? fetchOrderItems($db, (int) $order['id']) : [];
$messageHtml = '';

if ($query !== '' && $order === null) {
    $messageHtml = '<div class="mb-6 border border-danger-red/40 bg-danger-red/10 px-5 py-4 rounded-xl text-white">Pesanan tidak ditemukan. Gunakan nomor pesanan atau nomor HP yang terdaftar.</div>';
}

$statusSteps = [
    'confirmed' => ['title' => 'Pesanan Dikonfirmasi', 'body' => 'Pembayaran berhasil diverifikasi oleh sistem.', 'meta' => 'Pembayaran valid'],
    'packed' => ['title' => 'Sedang Dikemas', 'body' => 'Pesanan sedang disiapkan di gudang FITS MOTOR.', 'meta' => 'Diproses gudang'],
    'shipping' => ['title' => 'Dalam Pengiriman', 'body' => 'Paket sedang dalam perjalanan menuju lokasi Anda.', 'meta' => 'Status kurir aktif'],
    'delivered' => ['title' => 'Pesanan Tiba', 'body' => 'Paket akan segera sampai di tangan Anda.', 'meta' => 'Estimasi tiba'],
];

$currentStep = 'confirmed';
if ($order !== null) {
    $normalizedStatus = strtoupper((string) ($order['order_status'] ?: $order['status']));
    $currentStep = match ($normalizedStatus) {
        'PROCESSING', 'DIPROSES' => 'packed',
        'SHIPPED', 'SIAP_DIAMBIL' => 'shipping',
        'DELIVERED', 'SELESAI' => 'delivered',
        default => 'confirmed',
    };
}

$stepOrder = ['confirmed', 'packed', 'shipping', 'delivered'];
$timelineHtml = '';

foreach ($stepOrder as $index => $stepKey) {
    $step = $statusSteps[$stepKey];
    $stepState = array_search($stepKey, $stepOrder, true);
    $activeState = array_search($currentStep, $stepOrder, true);
    $isDone = $stepState < $activeState;
    $isActive = $stepKey === $currentStep;
    $lineClass = $isDone ? 'bg-tertiary-container' : 'bg-white/10';
    $nodeHtml = $isDone
        ? '<div class="relative z-10 w-8 h-8 rounded-full bg-tertiary-container flex items-center justify-center text-on-tertiary-container"><span class="material-symbols-outlined text-[20px]" style="font-variation-settings:\'FILL\' 1;">check</span></div>'
        : ($isActive
            ? '<div class="relative z-10 w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container shadow-[0_0_15px_rgba(255,86,37,0.5)]"><div class="w-2 h-2 bg-white rounded-full animate-ping"></div></div>'
            : '<div class="relative z-10 w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant"><span class="material-symbols-outlined text-[20px]">inventory_2</span></div>');
    $titleClass = $isActive ? 'text-primary-container' : 'text-white';
    $bodyClass = $isActive ? 'text-white' : 'text-on-surface-variant';
    $lineHtml = $index < count($stepOrder) - 1 ? '<div class="absolute left-[15px] top-[32px] bottom-0 w-[2px] ' . $lineClass . '"></div>' : '';
    $metaText = $order !== null
        ? htmlspecialchars(date('d M, H:i', strtotime((string) $order['created_at'])) . ' WIB', ENT_QUOTES, 'UTF-8')
        : htmlspecialchars((string) $step['meta'], ENT_QUOTES, 'UTF-8');

    $timelineHtml .= '<div class="flex gap-stack-lg relative' . ($index < count($stepOrder) - 1 ? ' pb-10' : '') . '">'
        . $lineHtml
        . $nodeHtml
        . '<div>'
        . '<h5 class="' . $titleClass . ' font-title-lg">' . htmlspecialchars($step['title'], ENT_QUOTES, 'UTF-8') . '</h5>'
        . '<p class="' . $bodyClass . ' text-body-md">' . htmlspecialchars($step['body'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '<span class="text-on-surface-variant font-data-mono text-sm">' . $metaText . '</span>'
        . '</div>'
        . '</div>';
}

$itemsHtml = '';

foreach ($orderItems as $item) {
    $itemsHtml .= '<div class="flex gap-stack-lg items-center py-4 border-b border-white/5 last:border-0">'
        . '<div class="w-16 h-16 bg-surface-container rounded-lg overflow-hidden flex-shrink-0">'
        . (trim((string) ($item['product_image'] ?? '')) !== ''
            ? '<img alt="' . htmlspecialchars((string) $item['product_name'], ENT_QUOTES, 'UTF-8') . '" class="w-full h-full object-cover" src="' . htmlspecialchars((string) $item['product_image'], ENT_QUOTES, 'UTF-8') . '"/>'
            : '<div class="w-full h-full flex items-center justify-center text-[10px] tracking-[0.24em] text-white/40">FITS</div>')
        . '</div>'
        . '<div class="flex-grow">'
        . '<h6 class="text-white font-title-lg">' . htmlspecialchars((string) $item['product_name'], ENT_QUOTES, 'UTF-8') . '</h6>'
        . '<p class="text-on-surface-variant text-sm">' . (int) $item['quantity'] . ' item</p>'
        . '</div>'
        . '<div class="text-right"><p class="text-primary font-data-mono">' . htmlspecialchars(formatRupiah((float) $item['subtotal']), ENT_QUOTES, 'UTF-8') . '</p></div>'
        . '</div>';
}

if ($itemsHtml === '') {
    $itemsHtml = '<div class="text-on-surface-variant">Belum ada item yang tercatat untuk pesanan ini.</div>';
}

$statusBadge = $order !== null
    ? htmlspecialchars(orderStatusLabel((string) ($order['order_status'] ?: $order['status'])), ENT_QUOTES, 'UTF-8')
    : 'Siap Dilacak';
$orderCodeLabel = $order !== null ? '#' . htmlspecialchars((string) ($order['order_number'] ?? $order['order_code']), ENT_QUOTES, 'UTF-8') : '#FM-TRACKING';
$orderDateLabel = $order !== null ? 'Pesanan dibuat pada ' . htmlspecialchars(date('d M Y', strtotime((string) $order['created_at'])), ENT_QUOTES, 'UTF-8') : 'Masukkan nomor pesanan atau nomor HP untuk melihat status.';
$courierLabel = $order !== null ? htmlspecialchars(trim((string) (($order['courier'] ?? 'JNE') . ' ' . ($order['courier_service'] ?? 'REGULAR'))), ENT_QUOTES, 'UTF-8') : 'JNE REGULAR';
$trackingNumber = $order !== null ? htmlspecialchars((string) (($order['tracking_number'] ?? '') !== '' ? $order['tracking_number'] : '-'), ENT_QUOTES, 'UTF-8') : '-';
$trackingQuery = $trackingNumber !== '-' ? rawurlencode(htmlspecialchars_decode($trackingNumber, ENT_QUOTES)) : '';
$trackingLink = $trackingNumber !== '-' ? 'https://www.jne.co.id/id/tracking/trace?q=' . $trackingQuery : '#';
$etaLabel = $order !== null ? htmlspecialchars((string) (($order['estimated_days'] ?? '') !== '' ? $order['estimated_days'] . ' HARI' : 'MENUNGGU UPDATE KURIR'), ENT_QUOTES, 'UTF-8') : '23 - 24 JANUARI 2025';

$trackingSectionHtml = '<section class="max-w-[1440px] mx-auto px-8 mb-24">'
    . '<div class="mb-stack-lg">'
    . '<h1 class="font-headline-lg text-headline-lg text-white mb-stack-md">LACAK PESANAN</h1>'
    . '<form class="flex max-w-xl" method="get" action="' . htmlspecialchars(fitsMotorUrl('/order-tracking.php'), ENT_QUOTES, 'UTF-8') . '">'
    . '<input class="flex-grow bg-surface-container border-none focus:ring-2 focus:ring-primary-container text-white px-4 py-3 font-body-md rounded-l-lg" name="query" value="' . htmlspecialchars($query, ENT_QUOTES, 'UTF-8') . '" placeholder="No. Pesanan atau No. HP" type="text"/>'
    . '<button class="bg-primary-container text-on-primary-fixed px-8 py-3 font-headline-md rounded-r-lg hover:bg-primary transition-colors" type="submit">Cari</button>'
    . '</form>'
    . '</div>'
    . $messageHtml
    . '<div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">'
    . '<div class="lg:col-span-8 flex flex-col gap-gutter">'
    . '<div class="bg-surface-card border border-white/10 p-stack-lg rounded-xl flex justify-between items-center"><div><p class="text-on-surface-variant font-label-md uppercase mb-1">ID PESANAN</p><h3 class="font-data-mono text-title-lg text-primary">' . $orderCodeLabel . '</h3><p class="text-on-surface-variant text-body-md">' . $orderDateLabel . '</p></div><div class="bg-primary-container/20 text-primary-container px-4 py-2 font-headline-md border border-primary-container/30">' . $statusBadge . '</div></div>'
    . '<div class="bg-surface-card border border-white/10 p-stack-lg rounded-xl"><h4 class="font-headline-md text-white mb-stack-lg">STATUS PENGIRIMAN</h4><div class="flex flex-col gap-0 relative">' . $timelineHtml . '</div></div>'
    . '<div class="bg-surface-container border border-white/5 rounded-xl overflow-hidden"><button type="button" class="w-full flex justify-between items-center p-stack-lg hover:bg-white/5 transition-colors group"><span class="font-headline-md text-white flex items-center gap-2"><span class="material-symbols-outlined">list_alt</span>LIHAT DETAIL PRODUK</span><span class="material-symbols-outlined text-primary group-hover:translate-y-1 transition-transform">keyboard_arrow_down</span></button><div class="p-stack-lg border-t border-white/5 bg-surface-card">' . $itemsHtml . '</div></div>'
    . '</div>'
    . '<div class="lg:col-span-4 flex flex-col gap-gutter">'
    . '<div class="bg-surface-card border-t-4 border-primary-container p-stack-lg rounded-xl shadow-xl"><h4 class="font-headline-md text-white mb-stack-lg">INFORMASI PENGIRIMAN</h4><div class="space-y-stack-lg"><div class="flex items-center gap-4"><div class="w-12 h-12 bg-white/5 flex items-center justify-center rounded-lg"><span class="material-symbols-outlined text-primary">local_shipping</span></div><div><p class="text-on-surface-variant text-sm">Kurir Pengiriman</p><p class="text-white font-title-lg">' . $courierLabel . '</p></div></div><div class="bg-surface-container p-4 rounded-lg"><p class="text-on-surface-variant text-sm mb-1">Nomor Resi</p><div class="flex justify-between items-center"><span class="font-data-mono text-primary text-lg tracking-wider">' . $trackingNumber . '</span><button type="button" class="material-symbols-outlined text-primary-container hover:scale-110 transition-transform">content_copy</button></div></div><a class="block w-full border border-outline px-4 py-3 text-center font-headline-md text-white hover:bg-white/5 transition-colors" href="' . htmlspecialchars($trackingLink, ENT_QUOTES, 'UTF-8') . '"' . ($trackingLink !== '#' ? ' target="_blank" rel="noopener noreferrer"' : '') . '>CEK RESI DI WEBSITE JNE →</a><div class="border-t border-white/5 pt-4"><p class="text-on-surface-variant text-sm">Estimasi Paket Tiba</p><p class="text-white font-headline-md text-2xl">' . $etaLabel . '</p></div></div></div>'
    . '<a class="bg-[#38A169] text-white w-full py-4 rounded-lg flex items-center justify-center gap-2 font-headline-md text-xl shadow-lg hover:brightness-110 transition-all" href="' . htmlspecialchars(whatsappDirectUrl(WA_NUMBER, 'Halo Admin FITS MOTOR, saya butuh bantuan untuk pesanan ' . ($order['order_code'] ?? $query)), ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer"><span class="material-symbols-outlined">chat</span>ADA MASALAH? CHAT ADMIN</a>'
    . '</div>'
    . '</div>'
    . '</section>';

$options = fitsMotorTrackingPageOptions('order-tracking', [
    'query' => $query,
    'tracking_panel' => '',
    'message' => null,
    'message_type' => 'error',
]);
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static function (string $html) use ($trackingSectionHtml): string {
        $updated = preg_replace('/<!-- FRAME 1: ORDER TRACKING PAGE \(Desktop Focused\) -->\s*<section\b[^>]*>.*?<\/section>/is', '<!-- FRAME 1: ORDER TRACKING PAGE (Desktop Focused) -->' . $trackingSectionHtml, $html, 1);

        return is_string($updated) ? $updated : $html;
    }
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('pelacakan_kontak_fits_motor/code.php'),
    $options
);
