<?php
declare(strict_types=1);

require_once __DIR__ . '/../shared/page_presets.php';

requireCustomerLogin();

$customerId = (int) currentUserId();
$metrics = fetchCustomerDashboardMetrics($db, $customerId);
$recentOrders = array_slice(fetchCustomerOrders($db, $customerId), 0, 5);
$recentBookings = array_slice(fetchCustomerBookings($db, $customerId), 0, 3);
$currentUser = findUserById($db, $customerId);
$ordersHtml = '';
$bookingsHtml = '';

foreach ($recentOrders as $order) {
    $ordersHtml .= '<li><a class="text-primary" href="' . htmlspecialchars(fitsMotorUrl('/customer/order-detail.php?order_code=' . urlencode((string) $order['order_code'])), ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars((string) ($order['order_number'] ?? $order['order_code']), ENT_QUOTES, 'UTF-8') . '</a> - ' . htmlspecialchars(orderStatusLabel((string) ($order['order_status'] ?: $order['status'])), ENT_QUOTES, 'UTF-8') . ' - ' . htmlspecialchars(formatRupiah((float) ($order['total'] ?? $order['total_amount'])), ENT_QUOTES, 'UTF-8') . '</li>';
}

foreach ($recentBookings as $booking) {
    $bookingsHtml .= '<li>' . htmlspecialchars((string) ($booking['booking_number'] ?? $booking['booking_code']), ENT_QUOTES, 'UTF-8') . ' - ' . htmlspecialchars((string) $booking['service_name'], ENT_QUOTES, 'UTF-8') . ' - ' . htmlspecialchars(bookingStatusLabel((string) $booking['status']), ENT_QUOTES, 'UTF-8') . '</li>';
}

$panelHtml = fitsMotorBuildContextPanel(
    'Ringkasan Akun',
    '<p class="text-white text-lg">Halo, ' . htmlspecialchars((string) ($currentUser['name'] ?? 'Customer'), ENT_QUOTES, 'UTF-8') . '!</p>'
        . '<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">'
        . '<div><strong class="text-white block">Total Pesanan</strong><span>' . (int) $metrics['order_count'] . '</span></div>'
        . '<div><strong class="text-white block">Booking Aktif</strong><span>' . (int) $metrics['active_booking_count'] . '</span></div>'
        . '<div><strong class="text-white block">Total Belanja</strong><span>' . htmlspecialchars(formatRupiah((int) $metrics['total_spending']), ENT_QUOTES, 'UTF-8') . '</span></div>'
        . '</div>'
        . '<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">'
        . '<div><strong class="text-white block mb-2">Pesanan Terbaru</strong><ul class="list-disc pl-5">' . ($ordersHtml !== '' ? $ordersHtml : '<li>Belum ada pesanan.</li>') . '</ul></div>'
        . '<div><strong class="text-white block mb-2">Booking Terbaru</strong><ul class="list-disc pl-5">' . ($bookingsHtml !== '' ? $bookingsHtml : '<li>Belum ada booking.</li>') . '</ul></div>'
        . '</div>'
        . '<div class="flex flex-wrap gap-3"><a href="' . htmlspecialchars(fitsMotorUrl('/products.php'), ENT_QUOTES, 'UTF-8') . '" class="bg-primary-container text-white px-4 py-3 rounded-lg font-bold uppercase text-sm">Belanja Lagi</a><a href="' . htmlspecialchars(fitsMotorUrl('/booking.php'), ENT_QUOTES, 'UTF-8') . '" class="border border-white/10 text-white px-4 py-3 rounded-lg font-bold uppercase text-sm">Booking Servis</a><a href="' . htmlspecialchars(fitsMotorUrl('/customer/profile.php'), ENT_QUOTES, 'UTF-8') . '" class="border border-white/10 text-white px-4 py-3 rounded-lg font-bold uppercase text-sm">Edit Profil</a></div>'
);

$options = fitsMotorCustomerPageOptions('dashboard');
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static fn (string $html): string => fitsMotorInjectInsideMainStart($html, $panelHtml)
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('dashboard_akun_customer_fits_motor/code.php'),
    $options
);

