<?php
declare(strict_types=1);

require_once __DIR__ . '/../../shared/page_presets.php';

requireAdminLogin();

$flashError = getFlash('error');
$flashSuccess = getFlash('success');
$bookingCode = trim((string) queryInput('booking_code', ''));
$booking = $bookingCode !== '' ? findBookingByCode($db, $bookingCode) : (fetchAdminUpcomingBookings($db, 1)[0] ?? null);

$feedbackHtml = '';
if ($flashError !== null || $flashSuccess !== null) {
    $feedbackHtml = '<div class="mb-6 border ' . ($flashSuccess !== null ? 'border-[#38A169]/40 bg-[#38A169]/10' : 'border-danger-red/40 bg-danger-red/10') . ' px-5 py-4 rounded-lg text-white">' . htmlspecialchars((string) ($flashSuccess ?? $flashError), ENT_QUOTES, 'UTF-8') . '</div>';
}

$statusBadge = static function (string $status): string {
    return match ($status) {
        'selesai' => 'bg-tertiary/10 text-tertiary border-tertiary/20',
        'dikonfirmasi', 'dalam_proses' => 'bg-secondary-container/10 text-secondary-container border-secondary-container/20',
        'dibatalkan' => 'bg-danger-red/10 text-danger-red border-danger-red/20',
        default => 'bg-primary-container/10 text-primary-container border-primary-container/20',
    };
};

$servicesSelected = trim((string) ($booking['services_json'] ?? ''));
$detailHtml = '<section class="mt-16 pt-16 border-t border-white/5" id="frame-order-detail">'
    . $feedbackHtml
    . ($booking !== null
        ? '<div class="flex justify-between items-end mb-8"><div><div class="flex items-center gap-3 mb-2"><h2 class="font-headline-lg text-headline-lg text-on-surface">Booking #' . htmlspecialchars((string) ($booking['booking_number'] ?? $booking['booking_code']), ENT_QUOTES, 'UTF-8') . '</h2><span class="px-3 py-1 rounded text-[12px] border font-bold uppercase ' . $statusBadge((string) $booking['status']) . '">' . htmlspecialchars(bookingStatusLabel((string) $booking['status']), ENT_QUOTES, 'UTF-8') . '</span></div><p class="text-on-surface-variant font-body-md">Jadwal ' . htmlspecialchars(date('d M Y', strtotime((string) $booking['booking_date'])) . ', ' . substr((string) $booking['booking_time'], 0, 5), ENT_QUOTES, 'UTF-8') . ' WIB</p></div><div class="flex gap-4"><a class="bg-surface-container-high px-4 py-2 rounded-lg font-label-md flex items-center gap-2 hover:text-primary transition-all" href="' . htmlspecialchars(fitsMotorUrl('/admin/bookings/index.php'), ENT_QUOTES, 'UTF-8') . '"><span class="material-symbols-outlined text-[18px]">arrow_back</span>Kembali</a><a href="#admin-booking-action" class="bg-primary-container text-white px-6 py-2 rounded-lg font-headline-md uppercase inline-flex items-center">Update Status</a></div></div>'
        . '<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter"><div class="lg:col-span-2 space-y-gutter"><div class="bg-surface-card border border-white/5 rounded-xl p-6"><h3 class="font-headline-md text-headline-md text-on-surface mb-6">Detail Booking</h3><div class="space-y-4"><div class="flex items-start justify-between gap-4 py-4 border-b border-white/5"><div><p class="font-bold text-on-surface">Layanan Utama</p><p class="text-[12px] text-on-surface-variant">' . htmlspecialchars((string) ($booking['service_name'] ?? '-'), ENT_QUOTES, 'UTF-8') . '</p></div><p class="font-data-mono text-on-surface">' . htmlspecialchars(formatRupiah((float) ($booking['estimated_cost'] ?? $booking['price_min'] ?? 0)), ENT_QUOTES, 'UTF-8') . '</p></div><div class="py-4 border-b border-white/5"><p class="text-[12px] uppercase tracking-[0.24em] text-on-surface-variant mb-2">Kendaraan</p><p class="font-bold text-on-surface">' . htmlspecialchars(trim((string) (($booking['motor_brand'] ?? $booking['vehicle_brand'] ?? '-') . ' ' . ($booking['motor_model'] ?? $booking['vehicle_type'] ?? '-'))), ENT_QUOTES, 'UTF-8') . '</p><p class="text-[12px] text-on-surface-variant">Plat: ' . htmlspecialchars((string) ($booking['vehicle_plate'] ?? '-'), ENT_QUOTES, 'UTF-8') . ' - Tahun: ' . htmlspecialchars((string) ($booking['vehicle_year'] ?? '-'), ENT_QUOTES, 'UTF-8') . '</p></div><div class="py-4 border-b border-white/5"><p class="text-[12px] uppercase tracking-[0.24em] text-on-surface-variant mb-2">Keluhan Customer</p><p class="text-on-surface">' . htmlspecialchars((string) (($booking['complaint'] ?? '') !== '' ? $booking['complaint'] : '-'), ENT_QUOTES, 'UTF-8') . '</p></div><div class="pt-4"><p class="text-[12px] uppercase tracking-[0.24em] text-on-surface-variant mb-2">Payload Layanan</p><p class="break-all text-on-surface-variant font-data-mono text-[12px]">' . htmlspecialchars($servicesSelected !== '' ? $servicesSelected : '-', ENT_QUOTES, 'UTF-8') . '</p></div></div></div></div>'
        . '<div class="space-y-gutter"><div class="bg-surface-card border border-white/5 rounded-xl p-6"><h3 class="font-label-md text-label-md text-on-surface-variant uppercase mb-4 tracking-widest">Informasi Customer</h3><div class="space-y-4"><div><p class="font-bold text-on-surface">' . htmlspecialchars((string) $booking['customer_name'], ENT_QUOTES, 'UTF-8') . '</p><p class="text-[14px] text-on-surface-variant">' . htmlspecialchars((string) ($booking['customer_email'] ?: '-'), ENT_QUOTES, 'UTF-8') . '</p><p class="text-[14px] text-on-surface-variant">' . htmlspecialchars((string) ($booking['customer_phone'] ?: '-'), ENT_QUOTES, 'UTF-8') . '</p></div><div class="pt-4 border-t border-white/5"><p class="text-[12px] text-on-surface-variant uppercase mb-1">Admin Notes</p><p class="text-[14px] text-on-surface">' . htmlspecialchars((string) (($booking['admin_notes'] ?? '') !== '' ? $booking['admin_notes'] : '-'), ENT_QUOTES, 'UTF-8') . '</p></div></div></div>'
        . '<div id="admin-booking-action" class="bg-surface-accent border border-primary-container/20 rounded-xl p-6"><h3 class="font-label-md text-label-md text-primary uppercase mb-4 tracking-widest">Action Required</h3><form method="post" action="' . htmlspecialchars(fitsMotorUrl('/admin/bookings/update-status.php'), ENT_QUOTES, 'UTF-8') . '" class="space-y-4"><input type="hidden" name="booking_code" value="' . htmlspecialchars((string) $booking['booking_code'], ENT_QUOTES, 'UTF-8') . '"/><div><label class="block text-[12px] text-on-surface-variant mb-2">Status Booking</label><select name="status" class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none"><option value="menunggu"' . ($booking['status'] === 'menunggu' ? ' selected' : '') . '>menunggu</option><option value="dikonfirmasi"' . ($booking['status'] === 'dikonfirmasi' ? ' selected' : '') . '>dikonfirmasi</option><option value="dalam_proses"' . ($booking['status'] === 'dalam_proses' ? ' selected' : '') . '>dalam_proses</option><option value="selesai"' . ($booking['status'] === 'selesai' ? ' selected' : '') . '>selesai</option><option value="dibatalkan"' . ($booking['status'] === 'dibatalkan' ? ' selected' : '') . '>dibatalkan</option></select></div><div><label class="block text-[12px] text-on-surface-variant mb-2">Catatan Admin</label><input type="text" name="admin_notes" value="' . htmlspecialchars((string) ($booking['admin_notes'] ?? ''), ENT_QUOTES, 'UTF-8') . '" class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none" placeholder="Catatan perubahan status"/></div><button type="submit" class="w-full bg-primary-container text-white py-3 rounded-lg font-bold uppercase transition-all hover:bg-inverse-primary">Simpan &amp; Proses</button></form></div></div></div>'
        : '<p class="text-on-surface-variant">Belum ada booking yang dapat ditampilkan.</p>')
    . '</section>';

$options = fitsMotorAdminPageOptions('booking-detail');
$options['hide_selectors'] = [
    'main #frame-dashboard',
    'main #frame-products',
    'main #frame-product-form',
    'main #frame-settings',
];
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static fn (string $html): string => fitsMotorReplaceSectionById($html, 'frame-order-detail', $detailHtml)
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('admin_panel_lengkap_fits_motor/code.php'),
    $options
);
