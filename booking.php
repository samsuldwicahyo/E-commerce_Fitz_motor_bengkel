<?php
declare(strict_types=1);

require_once __DIR__ . '/shared/page_presets.php';

const FITS_MOTOR_BOOKING_REGISTRATION_FEE = 10000;

function fitsMotorBookingDisplayPhone(string $phone): string
{
    $normalized = whatsappPhoneNumber($phone);

    return $normalized !== '' ? '+' . $normalized : '-';
}

function fitsMotorBookingServiceBasePrice(array $service): int
{
    return max(0, (int) round((float) ($service['price_min'] ?? 0)));
}

function fitsMotorBookingServicePriceLabel(array $service): string
{
    $priceMin = max(0, (int) round((float) ($service['price_min'] ?? 0)));
    $priceMax = max($priceMin, (int) round((float) ($service['price_max'] ?? 0)));

    if ($priceMin <= 0 && $priceMax <= 0) {
        return 'Estimasi';
    }

    if ($priceMin === $priceMax || $priceMax === 0) {
        return formatRupiah($priceMin);
    }

    return 'Mulai ' . formatRupiah($priceMin);
}

function fitsMotorBookingInitialStep(array $formData, bool $hasMessage): int
{
    if (!$hasMessage) {
        return 1;
    }

    if (
        trim((string) ($formData['vehicle_brand'] ?? '')) === ''
        || trim((string) ($formData['vehicle_type'] ?? '')) === ''
        || trim((string) ($formData['vehicle_year'] ?? '')) === ''
        || trim((string) ($formData['vehicle_plate'] ?? '')) === ''
    ) {
        return 2;
    }

    if (trim((string) ($formData['service_name'] ?? '')) === '') {
        return 3;
    }

    if (trim((string) ($formData['booking_date'] ?? '')) === '' || trim((string) ($formData['booking_time'] ?? '')) === '') {
        return 4;
    }

    return 4;
}

requireCustomerLogin();

$customerId = (int) currentUserId();
$customer = findUserById($db, $customerId);

if ($customer === null) {
    redirectTo('/auth/login.php?redirect=' . urlencode(fitsMotorUrl('/booking.php')));
}

$services = fetchActiveServices($db);
$vehicles = fetchCustomerVehicles($db, $customerId);
$latestVehicle = $vehicles[0] ?? null;

$defaultService = null;
foreach ($services as $serviceRow) {
    if (trim((string) ($serviceRow['name'] ?? '')) === 'Servis Ringan') {
        $defaultService = $serviceRow;
        break;
    }
}
if ($defaultService === null) {
    $defaultService = $services[0] ?? null;
}

$defaultDate = date('Y-m-d', strtotime('+1 day'));
$defaultTime = '09:00:00';

$formData = [
    'service_name' => (string) ($defaultService['name'] ?? ''),
    'booking_date' => $defaultDate,
    'booking_time' => $defaultTime,
    'vehicle_brand' => trim((string) ($latestVehicle['brand'] ?? '')),
    'vehicle_type' => trim((string) ($latestVehicle['type'] ?? '')),
    'vehicle_year' => trim((string) ($latestVehicle['year'] ?? '')),
    'vehicle_plate' => strtoupper(trim((string) ($latestVehicle['plate_number'] ?? ''))),
    'complaint' => trim((string) ($latestVehicle['notes'] ?? '')),
    'estimated_cost' => $defaultService !== null ? (string) (fitsMotorBookingServiceBasePrice($defaultService) + FITS_MOTOR_BOOKING_REGISTRATION_FEE) : '0',
];

$context = [
    'message' => getFlash('error'),
];

if (isPostRequest()) {
    $formData = [
        'service_name' => trim((string) requestInput('service_name', '')),
        'booking_date' => trim((string) requestInput('booking_date', '')),
        'booking_time' => trim((string) requestInput('booking_time', '')),
        'vehicle_brand' => trim((string) requestInput('vehicle_brand', '')),
        'vehicle_type' => trim((string) requestInput('vehicle_type', '')),
        'vehicle_year' => trim((string) requestInput('vehicle_year', '')),
        'vehicle_plate' => strtoupper(trim((string) requestInput('vehicle_plate', ''))),
        'complaint' => trim((string) requestInput('complaint', '')),
        'estimated_cost' => (string) requestInput('estimated_cost', '0'),
    ];

    if ($formData['service_name'] === '' || $formData['booking_date'] === '' || $formData['booking_time'] === '') {
        $context['message'] = 'Layanan, tanggal, dan jam booking wajib dipilih.';
    } elseif ($formData['vehicle_brand'] === '' || $formData['vehicle_type'] === '' || $formData['vehicle_year'] === '' || $formData['vehicle_plate'] === '') {
        $context['message'] = 'Data motor harus dilengkapi.';
    } else {
        $booking = createBooking($db, $customer, [
            'service_name' => $formData['service_name'],
            'booking_date' => $formData['booking_date'],
            'booking_time' => $formData['booking_time'],
            'vehicle_brand' => $formData['vehicle_brand'],
            'vehicle_type' => $formData['vehicle_type'],
            'vehicle_year' => $formData['vehicle_year'],
            'vehicle_plate' => $formData['vehicle_plate'],
            'complaint' => $formData['complaint'],
            'estimated_cost' => max(0, (float) $formData['estimated_cost']),
        ]);

        if ($booking === null) {
            $context['message'] = 'Booking gagal diproses.';
        } else {
            $_SESSION['last_booking_code'] = $booking['booking_code'];
            redirectTo('/booking-success.php');
        }
    }
}

$serviceLookup = [];
foreach ($services as $serviceRow) {
    $serviceLookup[(string) ($serviceRow['name'] ?? '')] = $serviceRow;
}

$selectedService = $serviceLookup[$formData['service_name']] ?? $defaultService;
if ($selectedService !== null && trim($formData['service_name']) === '') {
    $formData['service_name'] = (string) ($selectedService['name'] ?? '');
}

$estimatedTotal = $selectedService !== null
    ? fitsMotorBookingServiceBasePrice($selectedService) + FITS_MOTOR_BOOKING_REGISTRATION_FEE
    : FITS_MOTOR_BOOKING_REGISTRATION_FEE;

if (!isPostRequest()) {
    $formData['estimated_cost'] = (string) $estimatedTotal;
}

$vehicleOptions = [];
foreach ($vehicles as $vehicle) {
    $brand = trim((string) ($vehicle['brand'] ?? ''));
    $type = trim((string) ($vehicle['type'] ?? ''));
    if ($brand === '' || $type === '') {
        continue;
    }
    $vehicleOptions[$brand . '|' . $type] = [
        'brand' => $brand,
        'type' => $type,
        'label' => $brand . ' ' . $type,
    ];
}

foreach (
    [
        ['brand' => 'Honda', 'type' => 'CBR250RR'],
        ['brand' => 'Kawasaki', 'type' => 'ZX-25R'],
        ['brand' => 'Yamaha', 'type' => 'R25'],
        ['brand' => 'Honda', 'type' => 'Beat Street'],
        ['brand' => 'Honda', 'type' => 'Vario 160'],
        ['brand' => 'Yamaha', 'type' => 'NMAX'],
    ] as $presetVehicle
) {
    $vehicleOptions[$presetVehicle['brand'] . '|' . $presetVehicle['type']] = [
        'brand' => $presetVehicle['brand'],
        'type' => $presetVehicle['type'],
        'label' => $presetVehicle['brand'] . ' ' . $presetVehicle['type'],
    ];
}

if ($formData['vehicle_brand'] !== '' && $formData['vehicle_type'] !== '') {
    $vehicleOptions[$formData['vehicle_brand'] . '|' . $formData['vehicle_type']] = [
        'brand' => $formData['vehicle_brand'],
        'type' => $formData['vehicle_type'],
        'label' => $formData['vehicle_brand'] . ' ' . $formData['vehicle_type'],
    ];
}

$selectedVehicleValue = $formData['vehicle_brand'] !== '' || $formData['vehicle_type'] !== ''
    ? $formData['vehicle_brand'] . '|' . $formData['vehicle_type']
    : '';

$vehicleOptionsHtml = '<option value="">Pilih Motor Anda</option>';
foreach ($vehicleOptions as $optionValue => $vehicleOption) {
    $selected = $optionValue === $selectedVehicleValue ? ' selected' : '';
    $vehicleOptionsHtml .= '<option value="' . htmlspecialchars($optionValue, ENT_QUOTES, 'UTF-8') . '"' . $selected . '>' . htmlspecialchars((string) $vehicleOption['label'], ENT_QUOTES, 'UTF-8') . '</option>';
}

$serviceCardsHtml = '';
foreach ($services as $serviceRow) {
    $serviceName = trim((string) ($serviceRow['name'] ?? ''));
    $serviceDescription = trim((string) ($serviceRow['description'] ?? ''));
    $serviceIcon = trim((string) ($serviceRow['icon'] ?? 'build')) !== '' ? (string) $serviceRow['icon'] : 'build';
    $servicePriceValue = fitsMotorBookingServiceBasePrice($serviceRow);
    $servicePriceLabel = fitsMotorBookingServicePriceLabel($serviceRow);
    $isSelected = $selectedService !== null && $serviceName === (string) ($selectedService['name'] ?? '');

    $serviceCardsHtml .= '<button type="button" data-service-card data-service-name="' . htmlspecialchars($serviceName, ENT_QUOTES, 'UTF-8') . '" data-service-price="' . $servicePriceValue . '" data-service-label="' . htmlspecialchars($servicePriceLabel, ENT_QUOTES, 'UTF-8') . '" class="' . ($isSelected
        ? 'bg-surface-card border-2 border-primary-container p-6 relative group cursor-pointer overflow-hidden text-left'
        : 'bg-surface-card border border-white/5 p-6 hover:border-primary transition-all cursor-pointer text-left') . '">'
        . ($isSelected ? '<div class="absolute top-0 right-0 bg-primary-container text-on-primary-container px-3 py-1 font-label-md text-[10px] uppercase tracking-tighter">Terpilih</div>' : '')
        . '<div class="flex items-start gap-4 mb-4">'
        . '<div class="' . ($isSelected ? 'w-12 h-12 bg-surface-accent flex items-center justify-center' : 'w-12 h-12 bg-surface-container flex items-center justify-center') . '"><span class="material-symbols-outlined ' . ($isSelected ? 'text-primary' : 'text-on-surface-variant') . ' text-3xl">' . htmlspecialchars($serviceIcon, ENT_QUOTES, 'UTF-8') . '</span></div>'
        . '<div><h4 class="text-white font-title-lg text-title-lg">' . htmlspecialchars($serviceName, ENT_QUOTES, 'UTF-8') . '</h4><p class="text-on-surface-variant text-sm">' . htmlspecialchars($serviceDescription !== '' ? $serviceDescription : 'Layanan servis profesional FITS MOTOR.', ENT_QUOTES, 'UTF-8') . '</p></div>'
        . '</div>'
        . '<div class="flex items-center justify-between mt-6"><span class="font-data-mono ' . ($isSelected ? 'text-secondary' : 'text-on-surface-variant') . ' text-lg">' . htmlspecialchars($servicePriceLabel, ENT_QUOTES, 'UTF-8') . '</span><span class="material-symbols-outlined ' . ($isSelected ? 'text-primary' : 'text-white/10') . '"' . ($isSelected ? ' style="font-variation-settings: \'FILL\' 1;"' : '') . '>' . ($isSelected ? 'check_circle' : 'circle') . '</span></div>'
        . '</button>';
}

$timeSlots = [
    ['value' => '09:00:00', 'label' => '09:00', 'disabled' => false],
    ['value' => '10:00:00', 'label' => '10:00', 'disabled' => false],
    ['value' => '11:00:00', 'label' => '11:00', 'disabled' => false],
    ['value' => '13:00:00', 'label' => '13:00', 'disabled' => false],
    ['value' => '14:00:00', 'label' => '14:00 (Full)', 'disabled' => true],
    ['value' => '15:00:00', 'label' => '15:00', 'disabled' => false],
];

$timeSlotsHtml = '';
foreach ($timeSlots as $slot) {
    $isSelected = $formData['booking_time'] === $slot['value'];
    if ($slot['disabled']) {
        $timeSlotsHtml .= '<button type="button" data-time-slot data-slot-disabled="1" data-time-value="' . htmlspecialchars($slot['value'], ENT_QUOTES, 'UTF-8') . '" class="py-3 border border-white/5 bg-surface-container text-on-surface-variant font-data-mono text-sm opacity-50 cursor-not-allowed" disabled>' . htmlspecialchars($slot['label'], ENT_QUOTES, 'UTF-8') . '</button>';
        continue;
    }

    $timeSlotsHtml .= '<button type="button" data-time-slot data-time-value="' . htmlspecialchars($slot['value'], ENT_QUOTES, 'UTF-8') . '" class="' . ($isSelected
        ? 'py-3 border border-primary bg-surface-accent text-white font-data-mono text-sm'
        : 'py-3 border border-white/5 bg-surface-container text-on-surface-variant font-data-mono text-sm hover:border-primary') . '">' . htmlspecialchars($slot['label'], ENT_QUOTES, 'UTF-8') . '</button>';
}

$selectedMotorLabel = trim($formData['vehicle_brand'] . ' ' . $formData['vehicle_type'] . ($formData['vehicle_year'] !== '' ? ' (' . $formData['vehicle_year'] . ')' : ''));
$selectedMotorLabel = $selectedMotorLabel !== '' ? $selectedMotorLabel : 'Belum dipilih';
$selectedPlateLabel = $formData['vehicle_plate'] !== '' ? $formData['vehicle_plate'] : '-';
$selectedServiceName = $selectedService !== null ? (string) ($selectedService['name'] ?? 'Belum dipilih') : 'Belum dipilih';
$selectedServicePriceLabel = $selectedService !== null ? fitsMotorBookingServicePriceLabel($selectedService) : 'Belum dipilih';
$initialStep = fitsMotorBookingInitialStep($formData, trim((string) ($context['message'] ?? '')) !== '');

$progressSectionHtml = '<section class="max-w-[1440px] mx-auto px-8 -mt-16 relative z-20">'
    . '<div class="bg-surface-card border border-white/5 p-8 shadow-2xl">'
    . '<div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-5xl mx-auto">'
    . '<button type="button" data-step-indicator="1" class="group flex flex-col items-center gap-3 text-center"><span data-step-circle class="w-12 h-12 rounded-full border-2 border-primary bg-primary text-on-primary flex items-center justify-center font-bold text-lg shadow-[0_0_15px_rgba(255,181,160,0.4)]">1</span><span data-step-label class="text-primary font-label-md text-label-md">Data Kamu</span></button>'
    . '<button type="button" data-step-indicator="2" class="group flex flex-col items-center gap-3 text-center"><span data-step-circle class="w-12 h-12 rounded-full border-2 border-white/10 bg-surface-container flex items-center justify-center font-bold text-lg text-on-surface-variant">2</span><span data-step-label class="text-on-surface-variant font-label-md text-label-md">Data Motor</span></button>'
    . '<button type="button" data-step-indicator="3" class="group flex flex-col items-center gap-3 text-center"><span data-step-circle class="w-12 h-12 rounded-full border-2 border-white/10 bg-surface-container flex items-center justify-center font-bold text-lg text-on-surface-variant">3</span><span data-step-label class="text-on-surface-variant font-label-md text-label-md">Pilih Layanan</span></button>'
    . '<button type="button" data-step-indicator="4" class="group flex flex-col items-center gap-3 text-center"><span data-step-circle class="w-12 h-12 rounded-full border-2 border-white/10 bg-surface-container flex items-center justify-center font-bold text-lg text-on-surface-variant">4</span><span data-step-label class="text-on-surface-variant font-label-md text-label-md">Pilih Jadwal</span></button>'
    . '</div>'
    . '<div class="max-w-5xl mx-auto mt-6"><div class="h-[2px] bg-white/10"><div id="booking-progress-bar" class="h-[2px] bg-primary transition-all duration-300" style="width:25%;"></div></div></div>'
    . '</div>'
    . '</section>';

$contentSectionHtml = '<section class="max-w-[1440px] mx-auto px-8 py-16 grid grid-cols-1 lg:grid-cols-12 gap-gutter">'
    . '<input type="hidden" name="estimated_cost" id="booking-estimated-cost" value="' . htmlspecialchars((string) $formData['estimated_cost'], ENT_QUOTES, 'UTF-8') . '"/>'
    . '<div id="booking-wizard-root" class="lg:col-span-8 space-y-8" data-initial-step="' . $initialStep . '" data-default-date="' . htmlspecialchars($defaultDate, ENT_QUOTES, 'UTF-8') . '" data-default-time="' . htmlspecialchars($defaultTime, ENT_QUOTES, 'UTF-8') . '" data-initial-date="' . htmlspecialchars((string) $formData['booking_date'], ENT_QUOTES, 'UTF-8') . '" data-initial-time="' . htmlspecialchars((string) $formData['booking_time'], ENT_QUOTES, 'UTF-8') . '" data-booking-fee="' . FITS_MOTOR_BOOKING_REGISTRATION_FEE . '" data-customer-name="' . htmlspecialchars((string) ($customer['name'] ?? ''), ENT_QUOTES, 'UTF-8') . '" data-customer-phone="' . htmlspecialchars((string) ($customer['phone'] ?? ''), ENT_QUOTES, 'UTF-8') . '">'
    . '<div class="border-l-4 border-primary pl-6"><h2 class="font-headline-md text-headline-md text-white uppercase italic">Informasi Kendaraan &amp; Layanan</h2><p class="text-on-surface-variant font-body-md text-body-md">Silakan lengkapi detail servis Anda per langkah, lalu lanjutkan sampai jadwal booking selesai dipilih.</p></div>'
    . '<div data-booking-step="1" class="bg-surface-card p-8 border border-white/5 relative">'
    . '<div class="flex justify-between items-center mb-6"><h3 class="font-title-lg text-title-lg text-white flex items-center gap-2"><span class="material-symbols-outlined text-primary">person</span>Data Pemilik</h3><a href="' . htmlspecialchars(fitsMotorUrl('/customer/profile.php'), ENT_QUOTES, 'UTF-8') . '" class="text-primary font-label-md text-label-md hover:underline italic">Ubah Profil</a></div>'
    . '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">'
    . '<div class="space-y-2"><label class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">Nama Lengkap</label><div class="bg-surface-container p-4 border border-white/10 text-white font-medium">' . htmlspecialchars((string) ($customer['name'] ?? '-'), ENT_QUOTES, 'UTF-8') . '</div></div>'
    . '<div class="space-y-2"><label class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">Nomor Telepon</label><div class="bg-surface-container p-4 border border-white/10 text-white font-medium">' . htmlspecialchars(fitsMotorBookingDisplayPhone((string) ($customer['phone'] ?? '')), ENT_QUOTES, 'UTF-8') . '</div></div>'
    . '<div class="space-y-2 md:col-span-2"><label class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">Email</label><div class="bg-surface-container p-4 border border-white/10 text-white font-medium">' . htmlspecialchars((string) ($customer['email'] ?? '-'), ENT_QUOTES, 'UTF-8') . '</div></div>'
    . '</div>'
    . '<div class="mt-8 flex justify-end"><button type="button" data-booking-next="2" class="px-6 py-3 bg-primary-container text-white font-label-md uppercase tracking-wider hover:brightness-110 transition-all">Lanjutkan ke Data Motor</button></div>'
    . '</div>'
    . '<div data-booking-step="2" class="bg-surface-card p-8 border border-white/5 hidden">'
    . '<div class="flex items-start justify-between gap-4 mb-8"><div><h3 class="font-title-lg text-title-lg text-white flex items-center gap-2"><span class="material-symbols-outlined text-primary">motorcycle</span>Spesifikasi Motor</h3><p class="text-on-surface-variant text-sm mt-2">Isi data motor yang akan masuk bengkel.</p></div><span class="text-[11px] uppercase tracking-[0.24em] text-text-secondary">Langkah 2</span></div>'
    . '<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">'
    . '<div class="space-y-2"><label class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">Merk &amp; Tipe</label><select id="vehicle-brand-select" class="w-full bg-surface-container border border-white/10 p-4 text-white focus:border-primary-container outline-none transition-colors appearance-none">' . $vehicleOptionsHtml . '</select></div>'
    . '<div class="space-y-2"><label class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">Tahun Produksi</label><input id="vehicle-year-input" class="w-full bg-surface-container border border-white/10 p-4 text-white focus:border-primary-container outline-none transition-colors" name="vehicle_year" placeholder="Contoh: 2022" type="text" value="' . htmlspecialchars((string) $formData['vehicle_year'], ENT_QUOTES, 'UTF-8') . '" required/></div>'
    . '<div class="space-y-2"><label class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">Nomor Plat (Nopol)</label><input id="vehicle-plate-input" class="w-full bg-surface-container border border-white/10 p-4 text-white font-data-mono focus:border-primary-container outline-none transition-colors" name="vehicle_plate" placeholder="B 1234 ABC" type="text" value="' . htmlspecialchars((string) $formData['vehicle_plate'], ENT_QUOTES, 'UTF-8') . '" required/></div>'
    . '<div class="space-y-2"><label class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">Keluhan / Catatan Tambahan</label><textarea id="vehicle-complaint-input" class="w-full bg-surface-container border border-white/10 p-4 text-white focus:border-primary-container outline-none transition-colors resize-none" name="complaint" placeholder="Ceritakan masalah motor Anda..." rows="3">' . htmlspecialchars((string) $formData['complaint'], ENT_QUOTES, 'UTF-8') . '</textarea></div>'
    . '</div>'
    . '<div class="mt-8 flex items-center justify-between gap-4"><button type="button" data-booking-back="1" class="px-6 py-3 border border-white/10 text-white font-label-md uppercase tracking-wider hover:bg-surface-accent transition-all">Kembali</button><button type="button" data-booking-next="3" class="px-6 py-3 bg-primary-container text-white font-label-md uppercase tracking-wider hover:brightness-110 transition-all">Lanjutkan ke Layanan</button></div>'
    . '</div>'
    . '<div data-booking-step="3" class="space-y-6 hidden">'
    . '<div class="flex items-start justify-between gap-4"><div><h3 class="font-title-lg text-title-lg text-white flex items-center gap-2"><span class="material-symbols-outlined text-primary">build</span>Pilih Jenis Layanan</h3><p class="text-on-surface-variant text-sm mt-2">Pilih paket servis yang paling sesuai dengan kondisi motor.</p></div><span class="text-[11px] uppercase tracking-[0.24em] text-text-secondary">Langkah 3</span></div>'
    . '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">' . $serviceCardsHtml . '</div>'
    . '<div class="mt-2 flex items-center justify-between gap-4"><button type="button" data-booking-back="2" class="px-6 py-3 border border-white/10 text-white font-label-md uppercase tracking-wider hover:bg-surface-accent transition-all">Kembali</button><button type="button" data-booking-next="4" class="px-6 py-3 bg-primary-container text-white font-label-md uppercase tracking-wider hover:brightness-110 transition-all">Lanjutkan ke Jadwal</button></div>'
    . '</div>'
    . '<div data-booking-step="4" class="bg-surface-card border border-white/5 overflow-hidden hidden">'
    . '<div class="p-8 border-b border-white/5 flex items-start justify-between gap-4"><div><h3 class="font-title-lg text-title-lg text-white flex items-center gap-2"><span class="material-symbols-outlined text-primary">calendar_month</span>Tentukan Jadwal</h3><p class="text-on-surface-variant text-sm mt-2">Pilih tanggal dan jam kedatangan, lalu konfirmasi booking.</p></div><span class="text-[11px] uppercase tracking-[0.24em] text-text-secondary">Langkah 4</span></div>'
    . '<div class="grid grid-cols-1 md:grid-cols-2">'
    . '<div class="p-8 border-r border-white/5"><div class="flex justify-between items-center mb-6"><span data-booking-calendar-label class="font-bold text-white uppercase tracking-widest">Jadwal Booking</span><div class="flex gap-2"><button type="button" data-booking-calendar-prev class="w-8 h-8 flex items-center justify-center border border-white/10 hover:bg-surface-accent"><span class="material-symbols-outlined text-sm">chevron_left</span></button><button type="button" data-booking-calendar-next class="w-8 h-8 flex items-center justify-center border border-white/10 hover:bg-surface-accent"><span class="material-symbols-outlined text-sm">chevron_right</span></button></div></div><div class="grid grid-cols-7 gap-2 text-center text-[10px] text-on-surface-variant font-bold mb-4"><span>MIN</span><span>SEN</span><span>SEL</span><span>RAB</span><span>KAM</span><span>JUM</span><span>SAB</span></div><div data-booking-calendar-grid class="grid grid-cols-7 gap-2"></div></div>'
    . '<div class="p-8 bg-surface-container-low"><h4 class="text-white font-label-md text-label-md uppercase mb-6 tracking-wider">Jam Kedatangan</h4><div class="grid grid-cols-2 gap-3">' . $timeSlotsHtml . '</div><p class="text-on-surface-variant text-xs mt-4">Slot dapat berubah sesuai antrean workshop hari itu.</p></div>'
    . '</div>'
    . '<div class="p-8 border-t border-white/5 flex items-center justify-between gap-4"><button type="button" data-booking-back="3" class="px-6 py-3 border border-white/10 text-white font-label-md uppercase tracking-wider hover:bg-surface-accent transition-all">Kembali</button><button type="submit" class="px-6 py-3 bg-primary-container text-white font-headline-md text-headline-md uppercase hover:shadow-[0_0_20px_rgba(255,86,37,0.4)] transition-all active:scale-[0.98]">Konfirmasi Booking</button></div>'
    . '</div>'
    . '</div>'
    . '<div class="lg:col-span-4"><div class="sticky top-28 space-y-6"><div class="bg-surface-card border border-white/10 p-8 shadow-xl"><div class="flex items-center justify-between gap-4 mb-6 border-b border-white/5 pb-4"><h3 class="font-headline-md text-headline-md text-white italic">RINGKASAN BOOKING</h3><span id="booking-step-pill" class="text-[11px] uppercase tracking-[0.24em] text-primary">Langkah 1 / 4</span></div><div class="space-y-6">'
    . '<div><label class="text-[10px] text-on-surface-variant uppercase tracking-[0.2em] block mb-2">Motor</label><p id="summary-motor" class="text-white font-bold">' . htmlspecialchars($selectedMotorLabel, ENT_QUOTES, 'UTF-8') . '</p><p id="summary-plate" class="text-on-surface-variant text-sm font-data-mono">' . htmlspecialchars($selectedPlateLabel, ENT_QUOTES, 'UTF-8') . '</p></div>'
    . '<div><label class="text-[10px] text-on-surface-variant uppercase tracking-[0.2em] block mb-2">Layanan Terpilih</label><div class="flex justify-between text-sm"><span id="summary-service-name" class="text-white">' . htmlspecialchars($selectedServiceName, ENT_QUOTES, 'UTF-8') . '</span><span id="summary-service-price" class="text-on-surface-variant font-data-mono">' . htmlspecialchars($selectedServicePriceLabel, ENT_QUOTES, 'UTF-8') . '</span></div><div class="flex justify-between text-sm mt-1"><span class="text-white">Biaya Pendaftaran</span><span id="summary-booking-fee" class="text-on-surface-variant font-data-mono">' . htmlspecialchars(formatRupiah(FITS_MOTOR_BOOKING_REGISTRATION_FEE), ENT_QUOTES, 'UTF-8') . '</span></div></div>'
    . '<div class="pt-4 border-t border-dashed border-white/10"><label class="text-[10px] text-on-surface-variant uppercase tracking-[0.2em] block mb-2">Jadwal</label><p id="summary-schedule" class="text-white font-bold flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">event_available</span><span>Pilih tanggal dan jam booking.</span></p></div>'
    . '<div class="bg-surface-accent p-4 border-l-4 border-secondary"><div class="flex justify-between items-center"><span class="text-on-surface-variant text-sm">Total Estimasi</span><span id="summary-total" class="text-secondary font-headline-md text-2xl">' . htmlspecialchars(formatRupiah($estimatedTotal), ENT_QUOTES, 'UTF-8') . '</span></div></div>'
    . '<p id="summary-step-note" class="text-center text-[11px] text-on-surface-variant italic">Lanjutkan langkah booking sampai tahap jadwal untuk mengaktifkan konfirmasi akhir.</p>'
    . '</div></div>'
    . '<div class="bg-surface-container p-6 border border-white/5 flex items-center gap-4"><div class="w-12 h-12 rounded-full bg-surface-accent flex items-center justify-center"><span class="material-symbols-outlined text-primary">location_on</span></div><div><h5 class="text-white font-bold text-sm">FITS MOTOR Workshop</h5><p class="text-on-surface-variant text-[12px]">Jl. Industri Raya No. 42, Jakarta Pusat</p></div></div>'
    . '</div></div>'
    . '</section>';

$options = fitsMotorBookingPageOptions($context);
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static function (string $html) use ($progressSectionHtml, $contentSectionHtml): string {
        $updated = fitsMotorPregReplaceFirst(
            '/<!-- Progress Indicator -->\s*<section class="max-w-\[1440px\] mx-auto px-8 -mt-16 relative z-20">.*?<\/section>/s',
            '<!-- Progress Indicator -->' . $progressSectionHtml,
            $html
        );

        return fitsMotorPregReplaceFirst(
            '/<section class="max-w-\[1440px\] mx-auto px-8 py-16 grid grid-cols-1 lg:grid-cols-12 gap-gutter">.*?<\/section>/s',
            $contentSectionHtml,
            $updated
        );
    }
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('booking_servis_fits_motor/code.php'),
    $options
);

