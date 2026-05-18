<?php
declare(strict_types=1);

require_once __DIR__ . '/../../shared/page_presets.php';

requireAdminLogin();

$categories = fetchAllCategories($db);
$brands = fetchAllBrands($db);
$values = [
    'name' => '',
    'category' => '',
    'brand' => '',
    'compatibility' => '',
    'description' => '',
    'specifications' => '',
    'price' => '',
    'discount_price' => '',
    'stock' => '',
    'weight' => '1000',
    'badge' => '',
    'is_active' => '1',
];
$message = null;

if (isPostRequest()) {
    $values = [
        'name' => trim((string) requestInput('name', '')),
        'category' => trim((string) requestInput('category', '')),
        'brand' => trim((string) requestInput('brand', '')),
        'compatibility' => trim((string) requestInput('compatibility', '')),
        'description' => trim((string) requestInput('description', '')),
        'specifications' => trim((string) requestInput('specifications', '')),
        'price' => trim((string) requestInput('price', '0')),
        'discount_price' => trim((string) requestInput('discount_price', '')),
        'stock' => trim((string) requestInput('stock', '0')),
        'weight' => trim((string) requestInput('weight', '1000')),
        'badge' => trim((string) requestInput('badge', '')),
        'is_active' => (string) requestInput('is_active', '1'),
    ];

    if ($values['name'] === '' || $values['category'] === '') {
        $message = 'Nama produk dan kategori wajib diisi.';
    } else {
        $created = createProductRecord($db, [
            'name' => $values['name'],
            'category' => $values['category'],
            'brand' => $values['brand'],
            'compatibility' => $values['compatibility'],
            'description' => $values['description'],
            'specifications' => $values['specifications'],
            'price' => (int) $values['price'],
            'discount_price' => $values['discount_price'] !== '' ? (float) $values['discount_price'] : null,
            'stock' => (int) $values['stock'],
            'weight' => (int) $values['weight'],
            'badge' => $values['badge'],
            'is_active' => $values['is_active'] === '1',
        ]);

        if ($created === null) {
            $message = 'Produk gagal disimpan.';
        } else {
            redirectWithMessage('/admin/products/index.php', 'success', 'Produk berhasil ditambahkan.');
        }
    }
}

$categoryOptions = '<option>Pilih Kategori</option>';
foreach ($categories as $category) {
    $selected = $values['category'] === (string) $category['name'] ? ' selected' : '';
    $categoryOptions .= '<option value="' . htmlspecialchars((string) $category['name'], ENT_QUOTES, 'UTF-8') . '"' . $selected . '>' . htmlspecialchars((string) $category['name'], ENT_QUOTES, 'UTF-8') . '</option>';
}

$brandOptions = '<option>Pilih Brand</option>';
foreach ($brands as $brand) {
    $selected = $values['brand'] === (string) $brand['name'] ? ' selected' : '';
    $brandOptions .= '<option value="' . htmlspecialchars((string) $brand['name'], ENT_QUOTES, 'UTF-8') . '"' . $selected . '>' . htmlspecialchars((string) $brand['name'], ENT_QUOTES, 'UTF-8') . '</option>';
}

$feedbackHtml = $message !== null
    ? '<div class="mb-6 border border-danger-red/40 bg-danger-red/10 px-5 py-4 rounded-lg text-white">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</div>'
    : '';

$formSectionHtml = '<section class="mt-16 pt-16 border-t border-white/5" id="frame-product-form">'
    . $feedbackHtml
    . '<div class="flex items-center gap-4 mb-8"><a class="w-10 h-10 rounded-lg bg-surface-container-high flex items-center justify-center border border-white/5 hover:text-primary transition-all" href="' . htmlspecialchars(fitsMotorUrl('/admin/products/index.php'), ENT_QUOTES, 'UTF-8') . '"><span class="material-symbols-outlined">arrow_back</span></a><h2 class="font-headline-lg text-headline-lg text-on-surface">Tambah Produk Baru</h2></div>'
    . '<form method="post" action="' . htmlspecialchars(fitsMotorUrl('/admin/products/create.php'), ENT_QUOTES, 'UTF-8') . '" class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">'
    . '<div class="lg:col-span-2 space-y-gutter"><div class="bg-surface-card border border-white/5 rounded-xl p-6 space-y-6">'
    . '<div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Nama Produk</label><input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none" name="name" placeholder="Contoh: Knalpot Akrapovic Titanium Slip-On" type="text" value="' . htmlspecialchars($values['name'], ENT_QUOTES, 'UTF-8') . '"/></div>'
    . '<div class="grid grid-cols-2 gap-4"><div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Kompatibilitas</label><input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none" name="compatibility" placeholder="Contoh: Honda Beat / Vario" type="text" value="' . htmlspecialchars($values['compatibility'], ENT_QUOTES, 'UTF-8') . '"/></div><div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Badge</label><select class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none" name="badge"><option value="">Tanpa Badge</option><option value="BARU"' . ($values['badge'] === 'BARU' ? ' selected' : '') . '>BARU</option><option value="PROMO"' . ($values['badge'] === 'PROMO' ? ' selected' : '') . '>PROMO</option><option value="TERLARIS"' . ($values['badge'] === 'TERLARIS' ? ' selected' : '') . '>TERLARIS</option></select></div></div>'
    . '<div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Deskripsi</label><textarea class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none" name="description" placeholder="Jelaskan detail produk secara lengkap..." rows="6">' . htmlspecialchars($values['description'], ENT_QUOTES, 'UTF-8') . '</textarea></div>'
    . '<div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Spesifikasi &amp; Kompatibilitas</label><textarea class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none" name="specifications" placeholder="Daftar kompatibilitas motor dan spesifikasi teknis..." rows="4">' . htmlspecialchars($values['specifications'], ENT_QUOTES, 'UTF-8') . '</textarea></div>'
    . '</div></div>'
    . '<div class="space-y-gutter"><div class="bg-surface-card border border-white/5 rounded-xl p-6 space-y-6">'
    . '<div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Foto Produk</label><div class="border-2 border-dashed border-white/10 rounded-xl p-8 flex flex-col items-center justify-center text-center"><span class="material-symbols-outlined text-[48px] text-on-surface-variant mb-4">upload_file</span><p class="font-label-md text-on-surface mb-1">Upload gambar via path/database</p><p class="text-[10px] text-on-surface-variant uppercase">Belum ada uploader file otomatis</p></div></div>'
    . '<div class="grid grid-cols-2 gap-4"><div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Kategori</label><select class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none" name="category">' . $categoryOptions . '</select></div><div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Brand</label><select class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none" name="brand">' . $brandOptions . '</select></div></div>'
    . '<div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Harga Jual (IDR)</label><input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none font-data-mono" name="price" placeholder="0" type="number" value="' . htmlspecialchars($values['price'], ENT_QUOTES, 'UTF-8') . '"/></div>'
    . '<div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Harga Diskon (Opsional)</label><input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none font-data-mono" name="discount_price" placeholder="0" type="number" value="' . htmlspecialchars($values['discount_price'], ENT_QUOTES, 'UTF-8') . '"/></div>'
    . '<div class="grid grid-cols-2 gap-4"><div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Stok</label><input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none" name="stock" placeholder="0" type="number" value="' . htmlspecialchars($values['stock'], ENT_QUOTES, 'UTF-8') . '"/></div><div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Berat (gr)</label><input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none" name="weight" placeholder="0" type="number" value="' . htmlspecialchars($values['weight'], ENT_QUOTES, 'UTF-8') . '"/></div></div>'
    . '<div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Status Produk</label><div class="flex gap-4"><label class="flex-1 cursor-pointer"><input class="hidden peer" name="is_active" type="radio" value="1"' . ($values['is_active'] === '1' ? ' checked' : '') . '/><div class="p-3 text-center border border-white/10 rounded-lg peer-checked:bg-primary-container/20 peer-checked:border-primary-container peer-checked:text-primary transition-all">Aktif</div></label><label class="flex-1 cursor-pointer"><input class="hidden peer" name="is_active" type="radio" value="0"' . ($values['is_active'] !== '1' ? ' checked' : '') . '/><div class="p-3 text-center border border-white/10 rounded-lg peer-checked:bg-danger-red/20 peer-checked:border-danger-red peer-checked:text-danger-red transition-all">Draft</div></label></div></div>'
    . '<button class="w-full bg-primary-container text-white font-headline-md py-4 rounded-lg uppercase shadow-xl hover:scale-[1.02] active:scale-95 transition-all" type="submit">Simpan Produk</button>'
    . '</div></div>'
    . '</form>'
    . '</section>';

$options = fitsMotorAdminPageOptions('product-form');
$options['hide_selectors'] = [
    'main #frame-dashboard',
    'main #frame-products',
    'main #frame-order-detail',
    'main #frame-settings',
];
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static function (string $html) use ($formSectionHtml): string {
        $updated = preg_replace('/<section class="mt-16 pt-16 border-t border-white\/5" id="frame-product-form">.*?<\/section>/is', $formSectionHtml, $html, 1);

        return is_string($updated) ? $updated : $html;
    }
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('admin_panel_lengkap_fits_motor/code.php'),
    $options
);
