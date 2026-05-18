<?php
declare(strict_types=1);

require_once __DIR__ . '/../../shared/page_presets.php';

requireAdminLogin();

$flashError = getFlash('error');
$flashSuccess = getFlash('success');
$products = fetchAllProducts($db);
$feedbackHtml = '';

if ($flashError !== null || $flashSuccess !== null) {
    $feedbackHtml = '<div class="mb-6 border ' . ($flashSuccess !== null ? 'border-[#38A169]/40 bg-[#38A169]/10' : 'border-danger-red/40 bg-danger-red/10') . ' px-5 py-4 rounded-lg text-white">' . htmlspecialchars((string) ($flashSuccess ?? $flashError), ENT_QUOTES, 'UTF-8') . '</div>';
}

$rowsHtml = '';
foreach ($products as $product) {
    $image = trim((string) ($product['image'] ?? ''));
    $rowsHtml .= '<tr class="hover:bg-white/[0.02] transition-colors">'
        . '<td class="py-4 px-6 text-center"><input class="rounded bg-surface-container-lowest border-white/10 text-primary-container focus:ring-primary-container" type="checkbox"/></td>'
        . '<td class="py-4 px-4"><div class="w-12 h-12 bg-surface-container-high rounded-lg overflow-hidden border border-white/5">'
        . ($image !== ''
            ? '<img class="w-full h-full object-cover" src="' . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars((string) $product['name'], ENT_QUOTES, 'UTF-8') . '"/>'
            : '<div class="w-full h-full flex items-center justify-center text-[9px] tracking-[0.24em] text-white/40">FITS</div>')
        . '</div></td>'
        . '<td class="py-4 px-4"><p class="font-bold text-on-surface">' . htmlspecialchars((string) $product['name'], ENT_QUOTES, 'UTF-8') . '</p><p class="text-[11px] text-on-surface-variant font-data-mono">SKU: #' . (int) $product['id'] . '</p></td>'
        . '<td class="py-4 px-4"><span class="bg-surface-accent px-2 py-1 rounded text-[12px]">' . htmlspecialchars((string) ($product['category'] ?? '-'), ENT_QUOTES, 'UTF-8') . '</span></td>'
        . '<td class="py-4 px-4 font-data-mono">' . htmlspecialchars(formatRupiah((float) $product['price']), ENT_QUOTES, 'UTF-8') . '</td>'
        . '<td class="py-4 px-4 text-center">' . (int) $product['stock'] . '</td>'
        . '<td class="py-4 px-4 text-center"><span class="text-' . ((int) $product['is_active'] === 1 ? 'tertiary' : 'danger-red') . ' text-[12px] font-bold flex items-center justify-center gap-1"><span class="w-2 h-2 rounded-full bg-' . ((int) $product['is_active'] === 1 ? 'tertiary' : 'danger-red') . '"></span> ' . ((int) $product['is_active'] === 1 ? 'Aktif' : 'Draft') . '</span></td>'
        . '<td class="py-4 px-4 text-right"><div class="flex justify-end gap-2">'
        . '<a class="p-2 bg-surface-container-high text-on-surface hover:text-primary transition-all rounded" href="' . htmlspecialchars(fitsMotorUrl('/admin/products/edit.php?id=' . urlencode((string) $product['id'])), ENT_QUOTES, 'UTF-8') . '"><span class="material-symbols-outlined text-[18px]">edit</span></a>'
        . '<a class="p-2 bg-surface-container-high text-on-surface hover:text-danger-red transition-all rounded" href="' . htmlspecialchars(fitsMotorUrl('/admin/products/delete.php?id=' . urlencode((string) $product['id'])), ENT_QUOTES, 'UTF-8') . '" onclick="return confirm(\'Hapus produk ini?\')"><span class="material-symbols-outlined text-[18px]">delete</span></a>'
        . '</div></td>'
        . '</tr>';
}

if ($rowsHtml === '') {
    $rowsHtml = '<tr><td class="py-6 px-4 text-on-surface-variant" colspan="8">Belum ada produk.</td></tr>';
}

$productsSectionHtml = '<section class="mt-16 pt-16 border-t border-white/5" id="frame-products">'
    . $feedbackHtml
    . '<div class="flex justify-between items-center mb-8">'
    . '<h2 class="font-headline-lg text-headline-lg text-on-surface">Semua Produk</h2>'
    . '<a class="bg-primary-container hover:bg-inverse-primary text-white flex items-center gap-2 px-6 py-2.5 rounded-lg transition-all font-headline-md" href="' . htmlspecialchars(fitsMotorUrl('/admin/products/create.php'), ENT_QUOTES, 'UTF-8') . '"><span class="material-symbols-outlined">add</span>Tambah Produk</a>'
    . '</div>'
    . '<div class="bg-surface-card border border-white/5 rounded-xl overflow-hidden">'
    . '<table class="w-full text-left">'
    . '<thead class="bg-surface-container text-on-surface-variant font-label-md text-label-md"><tr><th class="py-4 px-6 w-10 text-center"><input class="rounded bg-surface-container-lowest border-white/10 text-primary-container focus:ring-primary-container" type="checkbox"/></th><th class="py-4 px-4">Foto</th><th class="py-4 px-4">Nama Produk</th><th class="py-4 px-4">Kategori</th><th class="py-4 px-4">Harga</th><th class="py-4 px-4 text-center">Stok</th><th class="py-4 px-4 text-center">Status</th><th class="py-4 px-4 text-right">Aksi</th></tr></thead>'
    . '<tbody class="divide-y divide-white/5 font-body-md">' . $rowsHtml . '</tbody>'
    . '</table>'
    . '</div>'
    . '</section>';

$options = fitsMotorAdminPageOptions('products');
$options['hide_selectors'] = [
    'main #frame-dashboard',
    'main #frame-product-form',
    'main #frame-order-detail',
    'main #frame-settings',
];
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static function (string $html) use ($productsSectionHtml): string {
        $updated = preg_replace('/<section class="mt-16 pt-16 border-t border-white\/5" id="frame-products">.*?<\/section>/is', $productsSectionHtml, $html, 1);

        return is_string($updated) ? $updated : $html;
    }
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('admin_panel_lengkap_fits_motor/code.php'),
    $options
);
