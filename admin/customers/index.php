<?php
declare(strict_types=1);

require_once __DIR__ . '/../../shared/page_presets.php';

requireAdminLogin();

$customers = fetchAllCustomers($db);
$totalCustomers = count($customers);
$thisMonth = 0;
$withPhone = 0;
$recentListHtml = '';
$rowsHtml = '';

foreach ($customers as $index => $customer) {
    if (date('Y-m', strtotime((string) $customer['created_at'])) === date('Y-m')) {
        $thisMonth++;
    }

    if (trim((string) ($customer['phone'] ?? '')) !== '') {
        $withPhone++;
    }

    if ($index < 5) {
        $recentListHtml .= '<div class="bg-surface-container-low border-l-4 border-primary-container p-4 rounded-r-lg"><p class="font-bold text-on-surface">' . htmlspecialchars((string) $customer['name'], ENT_QUOTES, 'UTF-8') . '</p><p class="text-[12px] text-on-surface-variant">' . htmlspecialchars((string) $customer['email'], ENT_QUOTES, 'UTF-8') . '</p><p class="text-[12px] text-primary-container font-data-mono mt-2">' . htmlspecialchars(date('d M Y', strtotime((string) $customer['created_at'])), ENT_QUOTES, 'UTF-8') . '</p></div>';
    }

    $rowsHtml .= '<tr class="hover:bg-white/[0.02] transition-colors"><td class="py-4 px-2"><p class="font-bold text-on-surface">' . htmlspecialchars((string) $customer['name'], ENT_QUOTES, 'UTF-8') . '</p><p class="text-[11px] text-on-surface-variant font-data-mono">ID: #' . (int) $customer['id'] . '</p></td><td class="py-4 px-2">' . htmlspecialchars((string) $customer['email'], ENT_QUOTES, 'UTF-8') . '</td><td class="py-4 px-2">' . htmlspecialchars((string) ($customer['phone'] ?: '-'), ENT_QUOTES, 'UTF-8') . '</td><td class="py-4 px-2">' . htmlspecialchars(date('d M Y', strtotime((string) $customer['created_at'])), ENT_QUOTES, 'UTF-8') . '</td></tr>';
}

if ($rowsHtml === '') {
    $rowsHtml = '<tr><td class="py-4 px-2 text-on-surface-variant" colspan="4">Belum ada customer.</td></tr>';
}

if ($recentListHtml === '') {
    $recentListHtml = '<div class="text-on-surface-variant">Belum ada customer terbaru.</div>';
}

$statsHtml = '';
$statCards = [
    ['label' => 'Total Customer', 'value' => (string) $totalCustomers, 'accent' => 'text-primary-container', 'icon' => 'groups', 'meta' => 'Seluruh akun customer'],
    ['label' => 'Daftar Bulan Ini', 'value' => (string) $thisMonth, 'accent' => 'text-tertiary', 'icon' => 'person_add', 'meta' => 'Registrasi bulan berjalan'],
    ['label' => 'Nomor Tersimpan', 'value' => (string) $withPhone, 'accent' => 'text-secondary', 'icon' => 'call', 'meta' => 'Siap dihubungi lewat WA'],
];
foreach ($statCards as $card) {
    $statsHtml .= '<div class="bg-surface-card border border-white/5 p-6 rounded-xl relative overflow-hidden group hover:border-primary-container/30 transition-all"><div class="absolute -right-4 -bottom-4 opacity-10 group-hover:opacity-20 transition-all"><span class="material-symbols-outlined text-[80px] ' . $card['accent'] . '">' . $card['icon'] . '</span></div><p class="text-on-surface-variant text-[12px] uppercase font-bold tracking-wider mb-2">' . htmlspecialchars($card['label'], ENT_QUOTES, 'UTF-8') . '</p><h3 class="font-headline-lg text-headline-lg ' . $card['accent'] . '">' . htmlspecialchars($card['value'], ENT_QUOTES, 'UTF-8') . '</h3><p class="text-[10px] text-on-surface-variant mt-2">' . htmlspecialchars($card['meta'], ENT_QUOTES, 'UTF-8') . '</p></div>';
}

$dashboardSectionHtml = '<section id="frame-dashboard"><div class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-8">' . $statsHtml . '</div><div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter"><div class="lg:col-span-2 bg-surface-card border border-white/5 rounded-xl p-6"><div class="flex justify-between items-center mb-6"><h2 class="font-headline-md text-headline-md text-on-surface">Data Pelanggan</h2><p class="text-on-surface-variant text-[12px] uppercase tracking-[0.24em]">Frame dashboard Stitch</p></div><div class="overflow-x-auto"><table class="w-full text-left"><thead class="border-b border-white/5 text-on-surface-variant font-label-md text-label-md"><tr><th class="py-4 px-2">Nama</th><th class="py-4 px-2">Email</th><th class="py-4 px-2">Telepon</th><th class="py-4 px-2">Terdaftar</th></tr></thead><tbody class="divide-y divide-white/5 font-body-md">' . $rowsHtml . '</tbody></table></div></div><div class="bg-surface-card border border-white/5 rounded-xl p-6"><h2 class="font-headline-md text-headline-md text-on-surface mb-6">Customer Terbaru</h2><div class="space-y-4">' . $recentListHtml . '</div></div></div></section>';

$options = fitsMotorAdminPageOptions('customers');
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
