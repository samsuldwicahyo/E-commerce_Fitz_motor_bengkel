<?php
declare(strict_types=1);

require_once __DIR__ . '/../../shared/page_presets.php';

requireAdminLogin();

$type = trim((string) queryInput('type', 'category'));
$id = (int) queryInput('id', requestInput('id', 0));
$record = $type === 'brand' ? findBrandById($db, $id) : findCategoryById($db, $id);

if ($record === null) {
    redirectWithMessage('/admin/categories/index.php?type=' . urlencode($type), 'error', $type === 'brand' ? 'Brand tidak ditemukan.' : 'Kategori tidak ditemukan.');
}

$values = [
    'name' => (string) $record['name'],
    'slug' => (string) ($record['slug'] ?? ''),
    'icon' => (string) ($record['icon'] ?? ''),
    'logo' => (string) ($record['logo'] ?? ''),
    'is_active' => (int) $record['is_active'] === 1 ? '1' : '0',
];
$message = null;

if (isPostRequest()) {
    $values = [
        'name' => trim((string) requestInput('name', '')),
        'slug' => trim((string) requestInput('slug', '')),
        'icon' => trim((string) requestInput('icon', '')),
        'logo' => trim((string) requestInput('logo', '')),
        'is_active' => (string) requestInput('is_active', '0'),
    ];

    if ($values['name'] === '') {
        $message = $type === 'brand' ? 'Nama brand wajib diisi.' : 'Nama kategori wajib diisi.';
    } else {
        $saved = $type === 'brand'
            ? updateBrandRecord($db, $id, [
                'name' => $values['name'],
                'logo' => $values['logo'],
                'is_active' => $values['is_active'] === '1',
            ])
            : updateCategoryRecord($db, $id, [
                'name' => $values['name'],
                'slug' => $values['slug'],
                'icon' => $values['icon'],
                'is_active' => $values['is_active'] === '1',
            ]);

        if ($saved) {
            redirectWithMessage('/admin/categories/index.php?type=' . urlencode($type), 'success', $type === 'brand' ? 'Brand berhasil diperbarui.' : 'Kategori berhasil diperbarui.');
        }

        $message = $type === 'brand' ? 'Brand gagal diperbarui.' : 'Kategori gagal diperbarui.';
    }
}

$feedbackHtml = $message !== null
    ? '<div class="mb-6 border border-danger-red/40 bg-danger-red/10 px-5 py-4 rounded-lg text-white">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</div>'
    : '';

$isBrand = $type === 'brand';
$title = $isBrand ? 'Edit Brand' : 'Edit Kategori';
$backUrl = fitsMotorUrl('/admin/categories/index.php?type=' . urlencode($type));

$formSectionHtml = '<section class="mt-16 pt-16 border-t border-white/5" id="frame-product-form">'
    . $feedbackHtml
    . '<div class="flex items-center gap-4 mb-8"><a class="w-10 h-10 rounded-lg bg-surface-container-high flex items-center justify-center border border-white/5 hover:text-primary transition-all" href="' . htmlspecialchars($backUrl, ENT_QUOTES, 'UTF-8') . '"><span class="material-symbols-outlined">arrow_back</span></a><div><h2 class="font-headline-lg text-headline-lg text-on-surface">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h2><p class="text-on-surface-variant text-[12px] font-data-mono">ID: #' . $id . '</p></div></div>'
    . '<form method="post" action="' . htmlspecialchars(fitsMotorUrl('/admin/categories/edit.php?type=' . urlencode($type) . '&id=' . urlencode((string) $id)), ENT_QUOTES, 'UTF-8') . '" class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">'
    . '<div class="lg:col-span-2"><div class="bg-surface-card border border-white/5 rounded-xl p-6 space-y-6">'
    . '<input type="hidden" name="id" value="' . $id . '"/>'
    . '<div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Nama ' . htmlspecialchars($isBrand ? 'Brand' : 'Kategori', ENT_QUOTES, 'UTF-8') . '</label><input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none" name="name" type="text" value="' . htmlspecialchars($values['name'], ENT_QUOTES, 'UTF-8') . '" required/></div>'
    . ($isBrand
        ? '<div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Path / URL Logo</label><input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none" name="logo" type="text" value="' . htmlspecialchars($values['logo'], ENT_QUOTES, 'UTF-8') . '" placeholder="/assets/img/brand.png atau URL"/></div>'
        : '<div class="grid grid-cols-2 gap-4"><div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Slug</label><input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none font-data-mono" name="slug" type="text" value="' . htmlspecialchars($values['slug'], ENT_QUOTES, 'UTF-8') . '" placeholder="otomatis-jika-kosong"/></div><div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Nama Icon Material</label><input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none font-data-mono" name="icon" type="text" value="' . htmlspecialchars($values['icon'], ENT_QUOTES, 'UTF-8') . '" placeholder="settings_input_component"/></div></div>')
    . '</div></div>'
    . '<div><div class="bg-surface-card border border-white/5 rounded-xl p-6 space-y-6">'
    . '<div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Status</label><div class="flex gap-4"><label class="flex-1 cursor-pointer"><input class="hidden peer" name="is_active" type="radio" value="1"' . ($values['is_active'] === '1' ? ' checked' : '') . '/><div class="p-3 text-center border border-white/10 rounded-lg peer-checked:bg-primary-container/20 peer-checked:border-primary-container peer-checked:text-primary transition-all">Aktif</div></label><label class="flex-1 cursor-pointer"><input class="hidden peer" name="is_active" type="radio" value="0"' . ($values['is_active'] !== '1' ? ' checked' : '') . '/><div class="p-3 text-center border border-white/10 rounded-lg peer-checked:bg-danger-red/20 peer-checked:border-danger-red peer-checked:text-danger-red transition-all">Draft</div></label></div></div>'
    . '<div class="rounded-xl border border-white/5 bg-surface-container-low p-4 text-on-surface-variant text-[13px] leading-6">Perubahan langsung ditulis ke database dan tetap memakai komposisi frame form Stitch supaya UI admin tetap konsisten.</div>'
    . '<button class="w-full bg-primary-container text-white font-headline-md py-4 rounded-lg uppercase shadow-xl hover:scale-[1.02] active:scale-95 transition-all" type="submit">Simpan Perubahan</button>'
    . '</div></div>'
    . '</form>'
    . '</section>';

$options = fitsMotorAdminPageOptions('categories');
$options['hide_selectors'] = [
    'main #frame-dashboard',
    'main #frame-products',
    'main #frame-order-detail',
    'main #frame-settings',
];
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static fn (string $html): string => fitsMotorReplaceSectionById($html, 'frame-product-form', $formSectionHtml)
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('admin_panel_lengkap_fits_motor/code.php'),
    $options
);
