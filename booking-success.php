<?php
declare(strict_types=1);

require_once __DIR__ . '/shared/page_presets.php';

requireCustomerLogin();

$bookingCode = trim((string) ($_SESSION['last_booking_code'] ?? ''));
$booking = $bookingCode !== '' ? findBookingByCode($db, $bookingCode) : null;
$context = [];

if ($booking !== null) {
    $context['booking_code'] = $booking['booking_code'];
    $context['booking_meta'] = sprintf(
        '%s | %s %s | %s',
        date('d M Y', strtotime((string) $booking['booking_date'])),
        substr((string) $booking['booking_time'], 0, 5),
        'WIB',
        (string) $booking['service_name']
    );
}

fitsMotorRenderPage(
    fitsMotorTemplatePath('booking_servis_fits_motor/code.php'),
    fitsMotorBookingSuccessOptions($context)
);

