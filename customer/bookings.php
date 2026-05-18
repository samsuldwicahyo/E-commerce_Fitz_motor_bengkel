<?php
declare(strict_types=1);

require_once __DIR__ . '/../shared/page_presets.php';

requireCustomerLogin();

$bookings = fetchCustomerBookings($db, (int) currentUserId());
$rowsHtml = '';

foreach ($bookings as $booking) {
    $rowsHtml .= '<tr>'
        . '<td class="p-3 border-b border-white/5">' . htmlspecialchars((string) ($booking['booking_number'] ?? $booking['booking_code']), ENT_QUOTES, 'UTF-8') . '</td>'
        . '<td class="p-3 border-b border-white/5">' . htmlspecialchars((string) $booking['service_name'], ENT_QUOTES, 'UTF-8') . '</td>'
        . '<td class="p-3 border-b border-white/5">' . htmlspecialchars(date('d M Y', strtotime((string) $booking['booking_date'])) . ' ' . substr((string) $booking['booking_time'], 0, 5), ENT_QUOTES, 'UTF-8') . '</td>'
        . '<td class="p-3 border-b border-white/5">' . htmlspecialchars(bookingStatusLabel((string) $booking['status']), ENT_QUOTES, 'UTF-8') . '</td>'
        . '</tr>';
}

$panelHtml = fitsMotorBuildContextPanel(
    'Booking Saya',
    $rowsHtml !== ''
        ? '<div class="overflow-x-auto"><table class="w-full text-left"><thead><tr><th class="p-3">No. Booking</th><th class="p-3">Layanan</th><th class="p-3">Jadwal</th><th class="p-3">Status</th></tr></thead><tbody>' . $rowsHtml . '</tbody></table></div>'
        : '<p>Belum ada booking untuk akun ini.</p>'
);

$options = fitsMotorCustomerPageOptions('bookings');
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static fn (string $html): string => fitsMotorInjectInsideMainStart($html, $panelHtml)
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('dashboard_akun_customer_fits_motor/code.php'),
    $options
);

