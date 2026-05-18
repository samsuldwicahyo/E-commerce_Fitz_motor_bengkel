<?php
declare(strict_types=1);

require_once __DIR__ . '/../shared/page_presets.php';

requireAdminLogin();

$metrics = fetchAdminDashboardMetrics($db);
$recentOrders = fetchAdminRecentOrders($db, 5);
$upcomingBookings = fetchAdminUpcomingBookings($db, 5);

$statCards = [
    ['label' => 'Pesanan Hari Ini', 'value' => (string) ((int) ($metrics['orders_today'] ?? 0)), 'accent' => 'text-primary-container', 'icon' => 'shopping_cart', 'meta' => 'Total pesanan masuk hari ini'],
    ['label' => 'Revenue Bulan Ini', 'value' => htmlspecialchars(formatRupiah((int) ($metrics['revenue_month'] ?? 0)), ENT_QUOTES, 'UTF-8'), 'accent' => 'text-tertiary', 'icon' => 'payments', 'meta' => 'Akumulasi omzet bulan berjalan'],
    ['label' => 'Pesanan Baru', 'value' => (string) ((int) ($metrics['pending_orders'] ?? 0)), 'accent' => 'text-on-surface', 'icon' => 'new_releases', 'meta' => 'Perlu diproses admin'],
    ['label' => 'Booking Pending', 'value' => (string) ((int) ($metrics['pending_bookings'] ?? 0)), 'accent' => 'text-danger-red', 'icon' => 'event_busy', 'meta' => 'Menunggu konfirmasi bengkel'],
    ['label' => 'Total Customer', 'value' => (string) ((int) ($metrics['customer_count'] ?? 0)), 'accent' => 'text-secondary', 'icon' => 'groups', 'meta' => 'Customer terdaftar aktif'],
    ['label' => 'Stok Hampir Habis', 'value' => (string) ((int) ($metrics['low_stock_count'] ?? 0)), 'accent' => 'text-secondary-container', 'icon' => 'inventory', 'meta' => 'Produk yang perlu restock'],
];

$statsHtml = '';
foreach ($statCards as $card) {
    $statsHtml .= '<div class="bg-surface-card border border-white/5 p-6 rounded-xl relative overflow-hidden group hover:border-primary-container/30 transition-all">'
        . '<div class="absolute -right-4 -bottom-4 opacity-10 group-hover:opacity-20 transition-all"><span class="material-symbols-outlined text-[80px] ' . $card['accent'] . '">' . $card['icon'] . '</span></div>'
        . '<p class="text-on-surface-variant text-[12px] uppercase font-bold tracking-wider mb-2">' . htmlspecialchars($card['label'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '<h3 class="font-headline-lg text-headline-lg ' . $card['accent'] . '">' . $card['value'] . '</h3>'
        . '<p class="text-[10px] text-on-surface-variant mt-2">' . htmlspecialchars($card['meta'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '</div>';
}

$ordersRowsHtml = '';
foreach ($recentOrders as $order) {
    $ordersRowsHtml .= '<tr class="hover:bg-white/[0.02] transition-colors">'
        . '<td class="py-4 px-2 font-data-mono">#' . htmlspecialchars((string) ($order['order_number'] ?? $order['order_code']), ENT_QUOTES, 'UTF-8') . '</td>'
        . '<td class="py-4 px-2">' . htmlspecialchars((string) $order['customer_name'], ENT_QUOTES, 'UTF-8') . '</td>'
        . '<td class="py-4 px-2">' . htmlspecialchars(formatRupiah((float) ($order['total'] ?? $order['total_amount'])), ENT_QUOTES, 'UTF-8') . '</td>'
        . '<td class="py-4 px-2"><span class="px-2 py-0.5 rounded-full text-[10px] bg-primary-container/10 text-primary-container border border-primary-container/20 uppercase font-bold">' . htmlspecialchars(orderStatusLabel((string) ($order['order_status'] ?: $order['status'])), ENT_QUOTES, 'UTF-8') . '</span></td>'
        . '<td class="py-4 px-2 text-right"><a class="bg-surface-accent px-3 py-1 rounded text-primary text-[12px] font-bold hover:bg-primary-container hover:text-white transition-all" href="' . htmlspecialchars(fitsMotorUrl('/admin/orders/detail.php?order_code=' . urlencode((string) $order['order_code'])), ENT_QUOTES, 'UTF-8') . '">DETAIL</a></td>'
        . '</tr>';
}

if ($ordersRowsHtml === '') {
    $ordersRowsHtml = '<tr><td class="py-4 px-2 text-on-surface-variant" colspan="5">Belum ada pesanan terbaru.</td></tr>';
}

$bookingsHtml = '';
foreach ($upcomingBookings as $booking) {
    $bookingsHtml .= '<a class="block bg-surface-container-low border-l-4 border-primary-container p-4 rounded-r-lg group hover:bg-surface-container-high transition-all cursor-pointer" href="' . htmlspecialchars(fitsMotorUrl('/admin/bookings/detail.php?booking_code=' . urlencode((string) $booking['booking_code'])), ENT_QUOTES, 'UTF-8') . '">'
        . '<div class="flex justify-between items-start mb-2"><p class="font-data-mono text-[12px] text-primary-container">' . htmlspecialchars(substr((string) $booking['booking_time'], 0, 5), ENT_QUOTES, 'UTF-8') . ' WIB</p><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-all">arrow_forward</span></div>'
        . '<p class="font-bold text-on-surface">' . htmlspecialchars((string) $booking['customer_name'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '<p class="text-[12px] text-on-surface-variant">' . htmlspecialchars((string) (($booking['motor_brand'] ?? $booking['vehicle_brand'] ?? '-') . ' - ' . ($booking['service_name'] ?? '-')), ENT_QUOTES, 'UTF-8') . '</p>'
        . '</a>';
}

if ($bookingsHtml === '') {
    $bookingsHtml = '<div class="text-on-surface-variant">Tidak ada booking hari ini.</div>';
}

$dashboardSectionHtml = '<section id="frame-dashboard">'
    . '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-gutter mb-8">' . $statsHtml . '</div>'
    . '<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">'
    . '<div class="lg:col-span-2 bg-surface-card border border-white/5 rounded-xl p-6"><div class="flex justify-between items-center mb-6"><h2 class="font-headline-md text-headline-md text-on-surface">Pesanan Terbaru</h2><a class="text-primary font-label-md text-label-md hover:underline transition-all" href="' . htmlspecialchars(fitsMotorUrl('/admin/orders/index.php'), ENT_QUOTES, 'UTF-8') . '">Lihat Semua</a></div><div class="overflow-x-auto"><table class="w-full text-left"><thead class="border-b border-white/5 text-on-surface-variant font-label-md text-label-md"><tr><th class="py-4 px-2">No</th><th class="py-4 px-2">Customer</th><th class="py-4 px-2">Total</th><th class="py-4 px-2">Status</th><th class="py-4 px-2 text-right">Aksi</th></tr></thead><tbody class="divide-y divide-white/5 font-body-md">' . $ordersRowsHtml . '</tbody></table></div></div>'
    . '<div class="bg-surface-card border border-white/5 rounded-xl p-6"><h2 class="font-headline-md text-headline-md text-on-surface mb-6">Booking Hari Ini</h2><div class="space-y-4">' . $bookingsHtml . '</div></div>'
    . '</div>'
    . '</section>';

$options = fitsMotorAdminPageOptions('dashboard');
$options['hide_selectors'] = [
    'main #frame-products',
    'main #frame-product-form',
    'main #frame-order-detail',
    'main #frame-settings',
];
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static function (string $html) use ($dashboardSectionHtml): string {
        $updated = preg_replace('/<section id="frame-dashboard">.*?<\/section>/is', $dashboardSectionHtml, $html, 1);

        return is_string($updated) ? $updated : $html;
    }
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('admin_panel_lengkap_fits_motor/code.php'),
    $options
);
