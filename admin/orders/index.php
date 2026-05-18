<?php
declare(strict_types=1);

require_once __DIR__ . '/../../shared/page_presets.php';

requireAdminLogin();

$flashError = getFlash('error');
$flashSuccess = getFlash('success');
$statusFilter = trim((string) queryInput('status', ''));
$paymentFilter = trim((string) queryInput('payment_status', ''));
$courierFilter = strtoupper(trim((string) queryInput('courier', '')));
$dateFrom = trim((string) queryInput('date_from', ''));
$dateTo = trim((string) queryInput('date_to', ''));

$orders = fetchAllOrders($db, $statusFilter !== '' ? $statusFilter : null);
$orders = array_values(array_filter($orders, static function (array $order) use ($paymentFilter, $courierFilter, $dateFrom, $dateTo): bool {
    if ($paymentFilter !== '' && (string) ($order['payment_status'] ?? '') !== $paymentFilter) {
        return false;
    }

    if ($courierFilter !== '' && strtoupper((string) ($order['courier'] ?? '')) !== $courierFilter) {
        return false;
    }

    $createdAt = strtotime((string) ($order['created_at'] ?? ''));

    if ($dateFrom !== '' && $createdAt < strtotime($dateFrom . ' 00:00:00')) {
        return false;
    }

    if ($dateTo !== '' && $createdAt > strtotime($dateTo . ' 23:59:59')) {
        return false;
    }

    return true;
}));

$feedbackHtml = '';
if ($flashError !== null || $flashSuccess !== null) {
    $feedbackHtml = '<div class="mb-6 border ' . ($flashSuccess !== null ? 'border-[#38A169]/40 bg-[#38A169]/10' : 'border-danger-red/40 bg-danger-red/10') . ' px-5 py-4 rounded-lg text-white">' . htmlspecialchars((string) ($flashSuccess ?? $flashError), ENT_QUOTES, 'UTF-8') . '</div>';
}

$totalRevenue = 0.0;
$pendingCount = 0;
$paidCount = 0;
$shippingCount = 0;
$actionListHtml = '';
$ordersRowsHtml = '';

$statusBadge = static function (string $status): string {
    return match ($status) {
        'selesai' => 'bg-tertiary/10 text-tertiary border-tertiary/20',
        'diproses', 'siap_diambil' => 'bg-secondary-container/10 text-secondary-container border-secondary-container/20',
        'dibatalkan' => 'bg-danger-red/10 text-danger-red border-danger-red/20',
        default => 'bg-primary-container/10 text-primary-container border-primary-container/20',
    };
};

$paymentBadge = static function (string $status): string {
    return $status === 'sudah_bayar'
        ? 'bg-tertiary/10 text-tertiary border-tertiary/20'
        : 'bg-danger-red/10 text-danger-red border-danger-red/20';
};

foreach ($orders as $index => $order) {
    $status = (string) ($order['order_status'] ?: $order['status']);
    $paymentStatus = (string) ($order['payment_status'] ?? '');
    $total = (float) ($order['total'] ?? $order['total_amount']);

    if ($status !== 'dibatalkan') {
        $totalRevenue += $total;
    }

    if (in_array($status, ['pending', 'diproses'], true)) {
        $pendingCount++;
    }

    if ($paymentStatus === 'sudah_bayar') {
        $paidCount++;
    }

    if (trim((string) ($order['courier'] ?? '')) !== '') {
        $shippingCount++;
    }

    if ($index < 5 && ($status === 'pending' || $paymentStatus !== 'sudah_bayar')) {
        $actionListHtml .= '<a class="block bg-surface-container-low border-l-4 border-primary-container p-4 rounded-r-lg group hover:bg-surface-container-high transition-all" href="' . htmlspecialchars(fitsMotorUrl('/admin/orders/detail.php?order_code=' . urlencode((string) $order['order_code'])), ENT_QUOTES, 'UTF-8') . '"><div class="flex justify-between items-start mb-2"><p class="font-data-mono text-[12px] text-primary-container">#' . htmlspecialchars((string) ($order['order_number'] ?? $order['order_code']), ENT_QUOTES, 'UTF-8') . '</p><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-all">arrow_forward</span></div><p class="font-bold text-on-surface">' . htmlspecialchars((string) $order['customer_name'], ENT_QUOTES, 'UTF-8') . '</p><p class="text-[12px] text-on-surface-variant">' . htmlspecialchars(orderStatusLabel($status) . ' - ' . paymentStatusLabel($paymentStatus), ENT_QUOTES, 'UTF-8') . '</p></a>';
    }

    $ordersRowsHtml .= '<tr class="hover:bg-white/[0.02] transition-colors">'
        . '<td class="py-4 px-2 font-data-mono">#' . htmlspecialchars((string) ($order['order_number'] ?? $order['order_code']), ENT_QUOTES, 'UTF-8') . '</td>'
        . '<td class="py-4 px-2"><div><p class="font-bold text-on-surface">' . htmlspecialchars((string) $order['customer_name'], ENT_QUOTES, 'UTF-8') . '</p><p class="text-[11px] text-on-surface-variant">' . htmlspecialchars(date('d M Y H:i', strtotime((string) $order['created_at'])), ENT_QUOTES, 'UTF-8') . ' WIB</p></div></td>'
        . '<td class="py-4 px-2"><p class="font-data-mono">' . htmlspecialchars(formatRupiah($total), ENT_QUOTES, 'UTF-8') . '</p><p class="text-[11px] text-on-surface-variant">' . htmlspecialchars((string) (($order['courier'] ?? '') !== '' ? strtoupper((string) $order['courier']) : 'Pickup / belum pilih'), ENT_QUOTES, 'UTF-8') . '</p></td>'
        . '<td class="py-4 px-2"><span class="px-2 py-0.5 rounded-full text-[10px] border uppercase font-bold ' . $paymentBadge($paymentStatus) . '">' . htmlspecialchars(paymentStatusLabel($paymentStatus), ENT_QUOTES, 'UTF-8') . '</span></td>'
        . '<td class="py-4 px-2"><span class="px-2 py-0.5 rounded-full text-[10px] border uppercase font-bold ' . $statusBadge($status) . '">' . htmlspecialchars(orderStatusLabel($status), ENT_QUOTES, 'UTF-8') . '</span></td>'
        . '<td class="py-4 px-2 text-right"><a class="bg-surface-accent px-3 py-1 rounded text-primary text-[12px] font-bold hover:bg-primary-container hover:text-white transition-all" href="' . htmlspecialchars(fitsMotorUrl('/admin/orders/detail.php?order_code=' . urlencode((string) $order['order_code'])), ENT_QUOTES, 'UTF-8') . '">DETAIL</a></td>'
        . '</tr>';
}

if ($ordersRowsHtml === '') {
    $ordersRowsHtml = '<tr><td class="py-4 px-2 text-on-surface-variant" colspan="6">Belum ada data pesanan yang sesuai filter.</td></tr>';
}

if ($actionListHtml === '') {
    $actionListHtml = '<div class="text-on-surface-variant">Tidak ada pesanan yang butuh tindakan segera.</div>';
}

$statCards = [
    ['label' => 'Total Pesanan', 'value' => (string) count($orders), 'accent' => 'text-primary-container', 'icon' => 'shopping_cart', 'meta' => 'Hasil sesuai filter aktif'],
    ['label' => 'Omzet Tercatat', 'value' => htmlspecialchars(formatRupiah($totalRevenue), ENT_QUOTES, 'UTF-8'), 'accent' => 'text-tertiary', 'icon' => 'payments', 'meta' => 'Pesanan selain dibatalkan'],
    ['label' => 'Perlu Diproses', 'value' => (string) $pendingCount, 'accent' => 'text-danger-red', 'icon' => 'new_releases', 'meta' => 'Status pending atau diproses'],
    ['label' => 'Pembayaran Lunas', 'value' => (string) $paidCount, 'accent' => 'text-secondary', 'icon' => 'verified', 'meta' => 'Order sudah dibayar'],
    ['label' => 'Pakai Kurir', 'value' => (string) $shippingCount, 'accent' => 'text-secondary-container', 'icon' => 'local_shipping', 'meta' => 'Order dengan pengiriman'],
];

$statsHtml = '';
foreach ($statCards as $card) {
    $statsHtml .= '<div class="bg-surface-card border border-white/5 p-6 rounded-xl relative overflow-hidden group hover:border-primary-container/30 transition-all"><div class="absolute -right-4 -bottom-4 opacity-10 group-hover:opacity-20 transition-all"><span class="material-symbols-outlined text-[80px] ' . $card['accent'] . '">' . $card['icon'] . '</span></div><p class="text-on-surface-variant text-[12px] uppercase font-bold tracking-wider mb-2">' . htmlspecialchars($card['label'], ENT_QUOTES, 'UTF-8') . '</p><h3 class="font-headline-lg text-headline-lg ' . $card['accent'] . '">' . $card['value'] . '</h3><p class="text-[10px] text-on-surface-variant mt-2">' . htmlspecialchars($card['meta'], ENT_QUOTES, 'UTF-8') . '</p></div>';
}

$filterHtml = '<form method="get" action="' . htmlspecialchars(fitsMotorUrl('/admin/orders/index.php'), ENT_QUOTES, 'UTF-8') . '" class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-6"><select name="status" class="bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-white"><option value="">Semua Status</option><option value="pending"' . ($statusFilter === 'pending' ? ' selected' : '') . '>pending</option><option value="diproses"' . ($statusFilter === 'diproses' ? ' selected' : '') . '>diproses</option><option value="siap_diambil"' . ($statusFilter === 'siap_diambil' ? ' selected' : '') . '>siap_diambil</option><option value="selesai"' . ($statusFilter === 'selesai' ? ' selected' : '') . '>selesai</option><option value="dibatalkan"' . ($statusFilter === 'dibatalkan' ? ' selected' : '') . '>dibatalkan</option></select><select name="payment_status" class="bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-white"><option value="">Semua Pembayaran</option><option value="belum_bayar"' . ($paymentFilter === 'belum_bayar' ? ' selected' : '') . '>belum_bayar</option><option value="sudah_bayar"' . ($paymentFilter === 'sudah_bayar' ? ' selected' : '') . '>sudah_bayar</option></select><select name="courier" class="bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-white"><option value="">Semua Kurir</option><option value="JNE"' . ($courierFilter === 'JNE' ? ' selected' : '') . '>JNE</option><option value="SICEPAT"' . ($courierFilter === 'SICEPAT' ? ' selected' : '') . '>SiCepat</option><option value="ANTERAJA"' . ($courierFilter === 'ANTERAJA' ? ' selected' : '') . '>AnterAja</option></select><input type="date" name="date_from" value="' . htmlspecialchars($dateFrom, ENT_QUOTES, 'UTF-8') . '" class="bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-white"/><input type="date" name="date_to" value="' . htmlspecialchars($dateTo, ENT_QUOTES, 'UTF-8') . '" class="bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-white"/><button type="submit" class="md:col-span-5 bg-primary-container text-white font-bold uppercase p-3 rounded-lg">Filter Pesanan</button></form>';

$dashboardSectionHtml = '<section id="frame-dashboard">'
    . $feedbackHtml
    . '<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-gutter mb-8">' . $statsHtml . '</div>'
    . '<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter"><div class="lg:col-span-2 bg-surface-card border border-white/5 rounded-xl p-6"><div class="flex justify-between items-center mb-6"><h2 class="font-headline-md text-headline-md text-on-surface">Semua Pesanan</h2><p class="text-on-surface-variant text-[12px] uppercase tracking-[0.24em]">UI mengikuti frame dashboard Stitch</p></div>' . $filterHtml . '<div class="overflow-x-auto"><table class="w-full text-left"><thead class="border-b border-white/5 text-on-surface-variant font-label-md text-label-md"><tr><th class="py-4 px-2">No</th><th class="py-4 px-2">Customer</th><th class="py-4 px-2">Total</th><th class="py-4 px-2">Pembayaran</th><th class="py-4 px-2">Status</th><th class="py-4 px-2 text-right">Aksi</th></tr></thead><tbody class="divide-y divide-white/5 font-body-md">' . $ordersRowsHtml . '</tbody></table></div></div><div class="bg-surface-card border border-white/5 rounded-xl p-6"><h2 class="font-headline-md text-headline-md text-on-surface mb-6">Perlu Tindakan</h2><div class="space-y-4">' . $actionListHtml . '</div></div></div>'
    . '</section>';

$options = fitsMotorAdminPageOptions('orders');
$options['hide_selectors'] = [
    'main #frame-products',
    'main #frame-product-form',
    'main #frame-order-detail',
    'main #frame-settings',
];
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static fn (string $html): string => fitsMotorReplaceSectionById($html, 'frame-dashboard', $dashboardSectionHtml)
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('admin_panel_lengkap_fits_motor/code.php'),
    $options
);
