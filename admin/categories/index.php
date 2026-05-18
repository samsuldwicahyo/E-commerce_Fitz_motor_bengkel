<?php
declare(strict_types=1);

require_once __DIR__ . '/../../shared/page_presets.php';

requireAdminLogin();

$type = trim((string) queryInput('type', 'category'));
$deleteId = (int) queryInput('delete', 0);

if ($deleteId > 0) {
    $deleted = $type === 'brand'
        ? deleteBrandRecord($db, $deleteId)
        : deleteCategoryRecord($db, $deleteId);

    redirectWithMessage(
        '/admin/categories/index.php?type=' . urlencode($type),
        $deleted ? 'success' : 'error',
        $deleted ? ($type === 'brand' ? 'Brand berhasil dihapus.' : 'Kategori berhasil dihapus.') : ($type === 'brand' ? 'Brand gagal dihapus.' : 'Kategori gagal dihapus.')
    );
}

$flashError = getFlash('error');
$flashSuccess = getFlash('success');
$feedbackHtml = '';

if ($flashError !== null || $flashSuccess !== null) {
    $feedbackHtml = '<div class="mb-6 border ' . ($flashSuccess !== null ? 'border-[#38A169]/40 bg-[#38A169]/10' : 'border-danger-red/40 bg-danger-red/10') . ' px-5 py-4 rounded-lg text-white">' . htmlspecialchars((string) ($flashSuccess ?? $flashError), ENT_QUOTES, 'UTF-8') . '</div>';
}

$categories = fetchAllCategories($db);
$brands = fetchAllBrands($db);
$rowsHtml = '';

if ($type === 'brand') {
    foreach ($brands as $brand) {
        $logo = trim((string) ($brand['logo'] ?? ''));
        $rowsHtml .= '<tr class="hover:bg-white/[0.02] transition-colors"><td class="py-4 px-6 text-center"><input class="rounded bg-surface-container-lowest border-white/10 text-primary-container focus:ring-primary-container" type="checkbox"/></td><td class="py-4 px-4"><div class="w-12 h-12 bg-surface-container-high rounded-lg overflow-hidden border border-white/5">'
            . ($logo !== '' ? '<img class="w-full h-full object-cover" src="' . htmlspecialchars($logo, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars((string) $brand['name'], ENT_QUOTES, 'UTF-8') . '"/>' : '<div class="w-full h-full flex items-center justify-center text-[9px] tracking-[0.24em] text-white/40">LOGO</div>')
            . '</div></td><td class="py-4 px-4"><p class="font-bold text-on-surface">' . htmlspecialchars((string) $brand['name'], ENT_QUOTES, 'UTF-8') . '</p><p class="text-[11px] text-on-surface-variant font-data-mono">ID: #' . (int) $brand['id'] . '</p></td><td class="py-4 px-4 font-data-mono">' . htmlspecialchars((string) ($logo !== '' ? $logo : '-'), ENT_QUOTES, 'UTF-8') . '</td><td class="py-4 px-4 text-center"><span class="text-' . ((int) $brand['is_active'] === 1 ? 'tertiary' : 'danger-red') . ' text-[12px] font-bold flex items-center justify-center gap-1"><span class="w-2 h-2 rounded-full bg-' . ((int) $brand['is_active'] === 1 ? 'tertiary' : 'danger-red') . '"></span> ' . ((int) $brand['is_active'] === 1 ? 'Aktif' : 'Draft') . '</span></td><td class="py-4 px-4 text-right"><div class="flex justify-end gap-2"><a class="p-2 bg-surface-container-high text-on-surface hover:text-primary transition-all rounded" href="' . htmlspecialchars(fitsMotorUrl('/admin/categories/edit.php?type=brand&id=' . urlencode((string) $brand['id'])), ENT_QUOTES, 'UTF-8') . '"><span class="material-symbols-outlined text-[18px]">edit</span></a><a class="p-2 bg-surface-container-high text-on-surface hover:text-danger-red transition-all rounded" href="' . htmlspecialchars(fitsMotorUrl('/admin/categories/index.php?type=brand&delete=' . urlencode((string) $brand['id'])), ENT_QUOTES, 'UTF-8') . '" onclick="return confirm(\'Hapus brand ini?\')"><span class="material-symbols-outlined text-[18px]">delete</span></a></div></td></tr>';
    }
} else {
    foreach ($categories as $category) {
        $icon = trim((string) ($category['icon'] ?? ''));
        $rowsHtml .= '<tr class="hover:bg-white/[0.02] transition-colors"><td class="py-4 px-6 text-center"><input class="rounded bg-surface-container-lowest border-white/10 text-primary-container focus:ring-primary-container" type="checkbox"/></td><td class="py-4 px-4"><div class="w-12 h-12 bg-surface-container-high rounded-lg overflow-hidden border border-white/5 flex items-center justify-center"><span class="material-symbols-outlined text-primary-container">' . htmlspecialchars($icon !== '' ? $icon : 'category', ENT_QUOTES, 'UTF-8') . '</span></div></td><td class="py-4 px-4"><p class="font-bold text-on-surface">' . htmlspecialchars((string) $category['name'], ENT_QUOTES, 'UTF-8') . '</p><p class="text-[11px] text-on-surface-variant font-data-mono">ID: #' . (int) $category['id'] . '</p></td><td class="py-4 px-4 font-data-mono">' . htmlspecialchars((string) $category['slug'], ENT_QUOTES, 'UTF-8') . '</td><td class="py-4 px-4 text-center"><span class="text-' . ((int) $category['is_active'] === 1 ? 'tertiary' : 'danger-red') . ' text-[12px] font-bold flex items-center justify-center gap-1"><span class="w-2 h-2 rounded-full bg-' . ((int) $category['is_active'] === 1 ? 'tertiary' : 'danger-red') . '"></span> ' . ((int) $category['is_active'] === 1 ? 'Aktif' : 'Draft') . '</span></td><td class="py-4 px-4 text-right"><div class="flex justify-end gap-2"><a class="p-2 bg-surface-container-high text-on-surface hover:text-primary transition-all rounded" href="' . htmlspecialchars(fitsMotorUrl('/admin/categories/edit.php?type=category&id=' . urlencode((string) $category['id'])), ENT_QUOTES, 'UTF-8') . '"><span class="material-symbols-outlined text-[18px]">edit</span></a><a class="p-2 bg-surface-container-high text-on-surface hover:text-danger-red transition-all rounded" href="' . htmlspecialchars(fitsMotorUrl('/admin/categories/index.php?type=category&delete=' . urlencode((string) $category['id'])), ENT_QUOTES, 'UTF-8') . '" onclick="return confirm(\'Hapus kategori ini?\')"><span class="material-symbols-outlined text-[18px]">delete</span></a></div></td></tr>';
    }
}

if ($rowsHtml === '') {
    $rowsHtml = '<tr><td class="py-6 px-4 text-on-surface-variant" colspan="6">Belum ada data ' . htmlspecialchars($type === 'brand' ? 'brand' : 'kategori', ENT_QUOTES, 'UTF-8') . '.</td></tr>';
}

$productsSectionHtml = '<section class="mt-16 pt-16 border-t border-white/5" id="frame-products">'
    . $feedbackHtml
    . '<div class="flex flex-col gap-4 md:flex-row md:justify-between md:items-center mb-8"><div><h2 class="font-headline-lg text-headline-lg text-on-surface">' . htmlspecialchars($type === 'brand' ? 'Semua Brand' : 'Semua Kategori', ENT_QUOTES, 'UTF-8') . '</h2><div class="flex gap-4 mt-3"><a class="' . ($type === 'category' ? 'text-primary' : 'text-on-surface-variant') . ' font-label-md" href="' . htmlspecialchars(fitsMotorUrl('/admin/categories/index.php?type=category'), ENT_QUOTES, 'UTF-8') . '">Kategori</a><a class="' . ($type === 'brand' ? 'text-primary' : 'text-on-surface-variant') . ' font-label-md" href="' . htmlspecialchars(fitsMotorUrl('/admin/categories/index.php?type=brand'), ENT_QUOTES, 'UTF-8') . '">Brand</a></div></div><a class="bg-primary-container hover:bg-inverse-primary text-white flex items-center gap-2 px-6 py-2.5 rounded-lg transition-all font-headline-md" href="' . htmlspecialchars(fitsMotorUrl('/admin/categories/create.php?type=' . urlencode($type)), ENT_QUOTES, 'UTF-8') . '"><span class="material-symbols-outlined">add</span>Tambah ' . htmlspecialchars($type === 'brand' ? 'Brand' : 'Kategori', ENT_QUOTES, 'UTF-8') . '</a></div>'
    . '<div class="bg-surface-card border border-white/5 rounded-xl overflow-hidden"><table class="w-full text-left"><thead class="bg-surface-container text-on-surface-variant font-label-md text-label-md"><tr><th class="py-4 px-6 w-10 text-center"><input class="rounded bg-surface-container-lowest border-white/10 text-primary-container focus:ring-primary-container" type="checkbox"/></th><th class="py-4 px-4">' . htmlspecialchars($type === 'brand' ? 'Logo' : 'Icon', ENT_QUOTES, 'UTF-8') . '</th><th class="py-4 px-4">Nama</th><th class="py-4 px-4">' . htmlspecialchars($type === 'brand' ? 'Path Logo' : 'Slug', ENT_QUOTES, 'UTF-8') . '</th><th class="py-4 px-4 text-center">Status</th><th class="py-4 px-4 text-right">Aksi</th></tr></thead><tbody class="divide-y divide-white/5 font-body-md">' . $rowsHtml . '</tbody></table></div>'
    . '</section>';

$options = fitsMotorAdminPageOptions('categories');
$options['hide_selectors'] = [
    'main #frame-dashboard',
    'main #frame-product-form',
    'main #frame-order-detail',
    'main #frame-settings',
];
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static fn (string $html): string => fitsMotorReplaceSectionById($html, 'frame-products', $productsSectionHtml)
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('admin_panel_lengkap_fits_motor/code.php'),
    $options
);
