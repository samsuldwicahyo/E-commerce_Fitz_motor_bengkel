<?php
declare(strict_types=1);

require_once __DIR__ . '/../../shared/page_presets.php';

requireAdminLogin();

$flashError = getFlash('error');
$flashSuccess = getFlash('success');
$statusFilter = trim((string) queryInput('status', ''));
$serviceFilter = trim((string) queryInput('service', ''));
$dateFilter = trim((string) queryInput('date', ''));
$bookings = fetchAllBookings($db, $statusFilter !== '' ? $statusFilter : null);
$bookings = array_values(array_filter($bookings, static function (array $booking) use ($serviceFilter, $dateFilter): bool {
    if ($serviceFilter !== '' && stripos((string) ($booking['service_name'] ?? ''), $serviceFilter) === false) {
        return false;
    }

    if ($dateFilter !== '' && (string) ($booking['booking_date'] ?? '') !== $dateFilter) {
        return false;
    }

    return true;
}));

$feedbackHtml = '';
if ($flashError !== null || $flashSuccess !== null) {
    $feedbackHtml = '<div class="mb-6 border ' . ($flashSuccess !== null ? 'border-[#38A169]/40 bg-[#38A169]/10' : 'border-danger-red/40 bg-danger-red/10') . ' px-5 py-4 rounded-lg text-white">' . htmlspecialchars((string) ($flashSuccess ?? $flashError), ENT_QUOTES, 'UTF-8') . '</div>';
}

$services = fetchActiveServices($db);
$serviceOptions = '<option value="">Semua Layanan</option>';
foreach ($services as $service) {
    $selected = $serviceFilter === (string) $service['name'] ? ' selected' : '';
    $serviceOptions .= '<option value="' . htmlspecialchars((string) $service['name'], ENT_QUOTES, 'UTF-8') . '"' . $selected . '>' . htmlspecialchars((string) $service['name'], ENT_QUOTES, 'UTF-8') . '</option>';
}

$waitingCount = 0;
$todayCount = 0;
$doneCount = 0;
$agendaHtml = '';
$rowsHtml = '';

$statusBadge = static function (string $status): string {
    return match ($status) {
        'selesai' => 'bg-tertiary/10 text-tertiary border-tertiary/20',
        'dikonfirmasi', 'dalam_proses' => 'bg-secondary-container/10 text-secondary-container border-secondary-container/20',
        'dibatalkan' => 'bg-danger-red/10 text-danger-red border-danger-red/20',
        default => 'bg-primary-container/10 text-primary-container border-primary-container/20',
    };
};

foreach ($bookings as $index => $booking) {
    $status = (string) ($booking['status'] ?? '');
    $bookingDate = (string) ($booking['booking_date'] ?? '');

    if (in_array($status, ['menunggu', 'dikonfirmasi', 'dalam_proses'], true)) {
        $waitingCount++;
    }
    if ($status === 'selesai') {
        $doneCount++;
    }
    if ($bookingDate === date('Y-m-d')) {
        $todayCount++;
    }

    if ($index < 5) {
        $agendaHtml .= '<a class="block bg-surface-container-low border-l-4 border-primary-container p-4 rounded-r-lg group hover:bg-surface-container-high transition-all" href="' . htmlspecialchars(fitsMotorUrl('/admin/bookings/detail.php?booking_code=' . urlencode((string) $booking['booking_code'])), ENT_QUOTES, 'UTF-8') . '"><div class="flex justify-between items-start mb-2"><p class="font-data-mono text-[12px] text-primary-container">' . htmlspecialchars(date('d M', strtotime($bookingDate)) . ' - ' . substr((string) $booking['booking_time'], 0, 5), ENT_QUOTES, 'UTF-8') . '</p><span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-all">arrow_forward</span></div><p class="font-bold text-on-surface">' . htmlspecialchars((string) $booking['customer_name'], ENT_QUOTES, 'UTF-8') . '</p><p class="text-[12px] text-on-surface-variant">' . htmlspecialchars((string) $booking['service_name'], ENT_QUOTES, 'UTF-8') . '</p></a>';
    }

    $rowsHtml .= '<tr class="hover:bg-white/[0.02] transition-colors">'
        . '<td class="py-4 px-2 font-data-mono">#' . htmlspecialchars((string) ($booking['booking_number'] ?? $booking['booking_code']), ENT_QUOTES, 'UTF-8') . '</td>'
        . '<td class="py-4 px-2"><p class="font-bold text-on-surface">' . htmlspecialchars((string) $booking['customer_name'], ENT_QUOTES, 'UTF-8') . '</p><p class="text-[11px] text-on-surface-variant">' . htmlspecialchars((string) ($booking['customer_phone'] ?? '-'), ENT_QUOTES, 'UTF-8') . '</p></td>'
        . '<td class="py-4 px-2"><p class="text-on-surface">' . htmlspecialchars((string) $booking['service_name'], ENT_QUOTES, 'UTF-8') . '</p><p class="text-[11px] text-on-surface-variant">' . htmlspecialchars(trim((string) (($booking['motor_brand'] ?? $booking['vehicle_brand'] ?? '') . ' ' . ($booking['motor_model'] ?? $booking['vehicle_type'] ?? ''))), ENT_QUOTES, 'UTF-8') . '</p></td>'
        . '<td class="py-4 px-2">' . htmlspecialchars(date('d M Y', strtotime($bookingDate)) . ' - ' . substr((string) $booking['booking_time'], 0, 5) . ' WIB', ENT_QUOTES, 'UTF-8') . '</td>'
        . '<td class="py-4 px-2"><span class="px-2 py-0.5 rounded-full text-[10px] border uppercase font-bold ' . $statusBadge($status) . '">' . htmlspecialchars(bookingStatusLabel($status), ENT_QUOTES, 'UTF-8') . '</span></td>'
        . '<td class="py-4 px-2 text-right"><a class="bg-surface-accent px-3 py-1 rounded text-primary text-[12px] font-bold hover:bg-primary-container hover:text-white transition-all" href="' . htmlspecialchars(fitsMotorUrl('/admin/bookings/detail.php?booking_code=' . urlencode((string) $booking['booking_code'])), ENT_QUOTES, 'UTF-8') . '">DETAIL</a></td>'
        . '</tr>';
}

if ($rowsHtml === '') {
    $rowsHtml = '<tr><td class="py-4 px-2 text-on-surface-variant" colspan="6">Belum ada data booking yang sesuai filter.</td></tr>';
}

if ($agendaHtml === '') {
    $agendaHtml = '<div class="text-on-surface-variant">Belum ada agenda booking.</div>';
}

$statsHtml = '';
$statCards = [
    ['label' => 'Semua Booking', 'value' => (string) count($bookings), 'accent' => 'text-primary-container', 'icon' => 'calendar_month', 'meta' => 'Data sesuai filter'],
    ['label' => 'Hari Ini', 'value' => (string) $todayCount, 'accent' => 'text-secondary', 'icon' => 'today', 'meta' => 'Jadwal tanggal hari ini'],
    ['label' => 'Masih Berjalan', 'value' => (string) $waitingCount, 'accent' => 'text-danger-red', 'icon' => 'event_busy', 'meta' => 'Menunggu sampai proses'],
    ['label' => 'Selesai', 'value' => (string) $doneCount, 'accent' => 'text-tertiary', 'icon' => 'task_alt', 'meta' => 'Booking yang ditutup'],
];
foreach ($statCards as $card) {
    $statsHtml .= '<div class="bg-surface-card border border-white/5 p-6 rounded-xl relative overflow-hidden group hover:border-primary-container/30 transition-all"><div class="absolute -right-4 -bottom-4 opacity-10 group-hover:opacity-20 transition-all"><span class="material-symbols-outlined text-[80px] ' . $card['accent'] . '">' . $card['icon'] . '</span></div><p class="text-on-surface-variant text-[12px] uppercase font-bold tracking-wider mb-2">' . htmlspecialchars($card['label'], ENT_QUOTES, 'UTF-8') . '</p><h3 class="font-headline-lg text-headline-lg ' . $card['accent'] . '">' . htmlspecialchars($card['value'], ENT_QUOTES, 'UTF-8') . '</h3><p class="text-[10px] text-on-surface-variant mt-2">' . htmlspecialchars($card['meta'], ENT_QUOTES, 'UTF-8') . '</p></div>';
}

$filterHtml = '<form method="get" action="' . htmlspecialchars(fitsMotorUrl('/admin/bookings/index.php'), ENT_QUOTES, 'UTF-8') . '" class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-6"><select name="status" class="bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-white"><option value="">Semua Status</option><option value="menunggu"' . ($statusFilter === 'menunggu' ? ' selected' : '') . '>menunggu</option><option value="dikonfirmasi"' . ($statusFilter === 'dikonfirmasi' ? ' selected' : '') . '>dikonfirmasi</option><option value="dalam_proses"' . ($statusFilter === 'dalam_proses' ? ' selected' : '') . '>dalam_proses</option><option value="selesai"' . ($statusFilter === 'selesai' ? ' selected' : '') . '>selesai</option><option value="dibatalkan"' . ($statusFilter === 'dibatalkan' ? ' selected' : '') . '>dibatalkan</option></select><select name="service" class="bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-white">' . $serviceOptions . '</select><input type="date" name="date" value="' . htmlspecialchars($dateFilter, ENT_QUOTES, 'UTF-8') . '" class="bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-white"/><button type="submit" class="bg-primary-container text-white font-bold uppercase p-3 rounded-lg">Filter Booking</button></form>';

$dashboardSectionHtml = '<section id="frame-dashboard">'
    . $feedbackHtml
    . '<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-gutter mb-8">' . $statsHtml . '</div>'
    . '<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter"><div class="lg:col-span-2 bg-surface-card border border-white/5 rounded-xl p-6"><div class="flex justify-between items-center mb-6"><h2 class="font-headline-md text-headline-md text-on-surface">Semua Booking</h2><p class="text-on-surface-variant text-[12px] uppercase tracking-[0.24em]">Layout frame dashboard Stitch</p></div>' . $filterHtml . '<div class="overflow-x-auto"><table class="w-full text-left"><thead class="border-b border-white/5 text-on-surface-variant font-label-md text-label-md"><tr><th class="py-4 px-2">No</th><th class="py-4 px-2">Customer</th><th class="py-4 px-2">Layanan</th><th class="py-4 px-2">Jadwal</th><th class="py-4 px-2">Status</th><th class="py-4 px-2 text-right">Aksi</th></tr></thead><tbody class="divide-y divide-white/5 font-body-md">' . $rowsHtml . '</tbody></table></div></div><div class="bg-surface-card border border-white/5 rounded-xl p-6"><h2 class="font-headline-md text-headline-md text-on-surface mb-6">Agenda Cepat</h2><div class="space-y-4">' . $agendaHtml . '</div></div></div>'
    . '</section>';

$options = fitsMotorAdminPageOptions('bookings');
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
