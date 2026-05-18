<?php
declare(strict_types=1);

require_once __DIR__ . '/../shared/page_presets.php';

requireCustomerLogin();

$orders = fetchCustomerOrders($db, (int) currentUserId());
$rowsHtml = '';

foreach ($orders as $order) {
    $rowsHtml .= '<tr>'
        . '<td class="p-3 border-b border-white/5">' . htmlspecialchars((string) ($order['order_number'] ?? $order['order_code']), ENT_QUOTES, 'UTF-8') . '</td>'
        . '<td class="p-3 border-b border-white/5">' . htmlspecialchars(date('d M Y', strtotime((string) $order['created_at'])), ENT_QUOTES, 'UTF-8') . '</td>'
        . '<td class="p-3 border-b border-white/5">' . htmlspecialchars(formatRupiah((float) ($order['total'] ?? $order['total_amount'])), ENT_QUOTES, 'UTF-8') . '</td>'
        . '<td class="p-3 border-b border-white/5">' . htmlspecialchars(orderStatusLabel((string) ($order['order_status'] ?: $order['status'])), ENT_QUOTES, 'UTF-8') . '</td>'
        . '<td class="p-3 border-b border-white/5"><a class="text-primary" href="' . htmlspecialchars(fitsMotorUrl('/customer/order-detail.php?order_code=' . urlencode((string) $order['order_code'])), ENT_QUOTES, 'UTF-8') . '">Lihat Detail</a></td>'
        . '</tr>';
}

$panelHtml = fitsMotorBuildContextPanel(
    'Pesanan Saya',
    $rowsHtml !== ''
        ? '<div class="overflow-x-auto"><table class="w-full text-left"><thead><tr><th class="p-3">No. Pesanan</th><th class="p-3">Tanggal</th><th class="p-3">Total</th><th class="p-3">Status</th><th class="p-3">Aksi</th></tr></thead><tbody>' . $rowsHtml . '</tbody></table></div>'
        : '<p>Belum ada pesanan untuk akun ini.</p>'
);

$options = fitsMotorCustomerPageOptions('orders');
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static fn (string $html): string => fitsMotorInjectInsideMainStart($html, $panelHtml)
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('dashboard_akun_customer_fits_motor/code.php'),
    $options
);

