<?php
declare(strict_types=1);

require_once __DIR__ . '/../../shared/page_presets.php';

requireAdminLogin();

$month = trim((string) queryInput('month', date('Y-m')));
$orders = fetchAllOrders($db);
$rowsHtml = '';
$totalRevenue = 0.0;
$totalOrders = 0;
$paidOrders = 0;
$cancelledOrders = 0;
$latestHtml = '';

foreach ($orders as $index => $order) {
    $createdMonth = date('Y-m', strtotime((string) $order['created_at']));

    if ($month !== '' && $createdMonth !== $month) {
        continue;
    }

    $status = (string) ($order['order_status'] ?: $order['status']);
    $paymentStatus = (string) ($order['payment_status'] ?? '');
    $total = (float) ($order['total'] ?? $order['total_amount']);

    $totalOrders++;

    if ($paymentStatus === 'sudah_bayar') {
        $paidOrders++;
    }

    if ($status === 'dibatalkan') {
        $cancelledOrders++;
    } else {
        $totalRevenue += $total;
    }

    if ($index < 5) {
        $latestHtml .= '<div class="bg-surface-container-low border-l-4 border-primary-container p-4 rounded-r-lg"><p class="font-data-mono text-[12px] text-primary-container">#' . htmlspecialchars((string) ($order['order_number'] ?? $order['order_code']), ENT_QUOTES, 'UTF-8') . '</p><p class="font-bold text-on-surface mt-1">' . htmlspecialchars((string) $order['customer_name'], ENT_QUOTES, 'UTF-8') . '</p><p class="text-[12px] text-on-surface-variant">' . htmlspecialchars(formatRupiah($total), ENT_QUOTES, 'UTF-8') . '</p></div>';
    }

    $rowsHtml .= '<tr class="hover:bg-white/[0.02] transition-colors"><td class="py-4 px-2">' . htmlspecialchars(date('d M Y', strtotime((string) $order['created_at'])), ENT_QUOTES, 'UTF-8') . '</td><td class="py-4 px-2 font-data-mono">#' . htmlspecialchars((string) ($order['order_number'] ?? $order['order_code']), ENT_QUOTES, 'UTF-8') . '</td><td class="py-4 px-2">' . htmlspecialchars((string) $order['customer_name'], ENT_QUOTES, 'UTF-8') . '</td><td class="py-4 px-2">' . htmlspecialchars(paymentStatusLabel($paymentStatus), ENT_QUOTES, 'UTF-8') . '</td><td class="py-4 px-2">' . htmlspecialchars(orderStatusLabel($status), ENT_QUOTES, 'UTF-8') . '</td><td class="py-4 px-2 font-data-mono">' . htmlspecialchars(formatRupiah($total), ENT_QUOTES, 'UTF-8') . '</td></tr>';
}

if ($rowsHtml === '') {
    $rowsHtml = '<tr><td class="py-4 px-2 text-on-surface-variant" colspan="6">Tidak ada pesanan pada periode ini.</td></tr>';
}

if ($latestHtml === '') {
    $latestHtml = '<div class="text-on-surface-variant">Tidak ada transaksi terbaru pada periode ini.</div>';
}

$statCards = [
    ['label' => 'Total Pesanan', 'value' => (string) $totalOrders, 'accent' => 'text-primary-container', 'icon' => 'receipt_long', 'meta' => 'Order pada periode terpilih'],
    ['label' => 'Pesanan Lunas', 'value' => (string) $paidOrders, 'accent' => 'text-tertiary', 'icon' => 'verified', 'meta' => 'Pembayaran sukses'],
    ['label' => 'Omzet Tercatat', 'value' => htmlspecialchars(formatRupiah($totalRevenue), ENT_QUOTES, 'UTF-8'), 'accent' => 'text-secondary', 'icon' => 'payments', 'meta' => 'Tidak termasuk order batal'],
    ['label' => 'Dibatalkan', 'value' => (string) $cancelledOrders, 'accent' => 'text-danger-red', 'icon' => 'cancel', 'meta' => 'Perlu evaluasi operasional'],
];

$statsHtml = '';
foreach ($statCards as $card) {
    $statsHtml .= '<div class="bg-surface-card border border-white/5 p-6 rounded-xl relative overflow-hidden group hover:border-primary-container/30 transition-all"><div class="absolute -right-4 -bottom-4 opacity-10 group-hover:opacity-20 transition-all"><span class="material-symbols-outlined text-[80px] ' . $card['accent'] . '">' . $card['icon'] . '</span></div><p class="text-on-surface-variant text-[12px] uppercase font-bold tracking-wider mb-2">' . htmlspecialchars($card['label'], ENT_QUOTES, 'UTF-8') . '</p><h3 class="font-headline-lg text-headline-lg ' . $card['accent'] . '">' . $card['value'] . '</h3><p class="text-[10px] text-on-surface-variant mt-2">' . htmlspecialchars($card['meta'], ENT_QUOTES, 'UTF-8') . '</p></div>';
}

$dashboardSectionHtml = '<section id="frame-dashboard"><div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-gutter mb-8">' . $statsHtml . '</div><div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter"><div class="lg:col-span-2 bg-surface-card border border-white/5 rounded-xl p-6"><div class="flex justify-between items-center mb-6"><h2 class="font-headline-md text-headline-md text-on-surface">Laporan Penjualan</h2><form method="get" action="' . htmlspecialchars(fitsMotorUrl('/admin/reports/sales.php'), ENT_QUOTES, 'UTF-8') . '" class="flex gap-3"><input type="month" name="month" value="' . htmlspecialchars($month, ENT_QUOTES, 'UTF-8') . '" class="bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-white"/><button type="submit" class="bg-primary-container text-white font-bold uppercase px-4 py-3 rounded-lg">Tampilkan</button></form></div><div class="overflow-x-auto"><table class="w-full text-left"><thead class="border-b border-white/5 text-on-surface-variant font-label-md text-label-md"><tr><th class="py-4 px-2">Tanggal</th><th class="py-4 px-2">No. Pesanan</th><th class="py-4 px-2">Customer</th><th class="py-4 px-2">Pembayaran</th><th class="py-4 px-2">Status</th><th class="py-4 px-2">Total</th></tr></thead><tbody class="divide-y divide-white/5 font-body-md">' . $rowsHtml . '</tbody></table></div></div><div class="bg-surface-card border border-white/5 rounded-xl p-6"><h2 class="font-headline-md text-headline-md text-on-surface mb-6">Transaksi Ringkas</h2><div class="space-y-4">' . $latestHtml . '</div></div></div></section>';

$options = fitsMotorAdminPageOptions('reports');
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
