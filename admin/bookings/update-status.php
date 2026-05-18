<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/bootstrap.php';

requireAdminLogin();

$bookingCode = trim((string) requestInput('booking_code', queryInput('booking_code', '')));
$status = trim((string) requestInput('status', queryInput('status', 'dikonfirmasi')));
$adminNotes = trim((string) requestInput('admin_notes', queryInput('admin_notes', '')));

if ($bookingCode === '') {
    $latest = fetchAdminUpcomingBookings($db, 1);
    $bookingCode = (string) ($latest[0]['booking_code'] ?? '');
}

if ($bookingCode === '') {
    redirectWithMessage('/admin/bookings/index.php', 'error', 'Tidak ada booking yang bisa diperbarui.');
}

$updated = updateBookingStatus($db, $bookingCode, $status, $adminNotes !== '' ? $adminNotes : null);

if (!$updated) {
    redirectWithMessage('/admin/bookings/detail.php?booking_code=' . urlencode($bookingCode), 'error', 'Status booking gagal diperbarui.');
}

redirectWithMessage('/admin/bookings/detail.php?booking_code=' . urlencode($bookingCode), 'success', 'Status booking berhasil diperbarui.');
