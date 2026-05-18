<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$date = trim((string) queryInput('date', date('Y-m-d')));
$slots = ['09:00:00', '10:00:00', '11:00:00', '13:00:00', '14:00:00', '15:00:00'];
$payload = [];

foreach ($slots as $slot) {
    $row = dbFetchOne(
        $db,
        'SELECT COUNT(*) AS total FROM bookings WHERE booking_date = :booking_date AND booking_time = :booking_time AND status IN ("menunggu","dikonfirmasi","dalam_proses")',
        [
            'booking_date' => $date,
            'booking_time' => $slot,
        ]
    );

    $used = (int) ($row['total'] ?? 0);
    $remaining = max(0, 3 - $used);

    $payload[] = [
        'time' => substr($slot, 0, 5),
        'available' => $remaining > 0,
        'remaining_slots' => $remaining,
    ];
}

jsonResponse([
    'success' => true,
    'date' => $date,
    'slots' => $payload,
]);
