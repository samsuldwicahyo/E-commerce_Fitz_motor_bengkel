<?php
declare(strict_types=1);

require_once __DIR__ . '/shared/page_presets.php';

$search = trim((string) queryInput('search', ''));
$category = trim((string) queryInput('category', ''));
$brand = trim((string) queryInput('brand', ''));

$categories = fetchAllCategories($db);
$brands = fetchAllBrands($db);
$products = fetchStorefrontProducts($db, [
    'search' => $search,
    'category' => $category,
    'brand' => $brand,
]);
$filtersActive = $search !== '' || $category !== '' || $brand !== '';
$resetUrl = fitsMotorUrl('/products.php');
$catalogCardsHtml = '';

foreach ($products as $product) {
    $descriptionPreview = trim((string) ($product['description'] ?? ''));
    if (mb_strlen($descriptionPreview) > 120) {
        $descriptionPreview = mb_substr($descriptionPreview, 0, 117) . '...';
    }

    $productImages = trim((string) ($product['image'] ?? '')) === '' ? fetchProductImages($db, (int) $product['id']) : [];
    $imagePath = trim((string) ($product['image'] ?? ''));
    if ($imagePath === '' && $productImages !== []) {
        $imagePath = trim((string) ($productImages[0]['image_path'] ?? ''));
    }

    $productUrl = fitsMotorUrl('/product-detail.php?slug=' . urlencode((string) ($product['slug'] ?? '')));
    $stock = max(0, (int) ($product['stock'] ?? 0));
    $soldCount = max(0, (int) ($product['sold_count'] ?? 0));
    $currentPrice = (float) (($product['discount_price'] ?? null) !== null ? $product['discount_price'] : $product['price']);
    $hasDiscount = ($product['discount_price'] ?? null) !== null && (float) $product['discount_price'] < (float) $product['price'];
    $badgeLabel = trim((string) ($product['badge'] ?? ''));
    $compatibilityLabel = trim((string) ($product['compatibility'] ?? '')) !== '' ? (string) $product['compatibility'] : ((string) ($product['category_name'] ?? $product['category'] ?? 'Universal'));
    $brandLabel = trim((string) ($product['brand_name'] ?? $product['brand'] ?? '')) !== '' ? (string) ($product['brand_name'] ?? $product['brand']) : 'FITS MOTOR';

    $imageHtml = $imagePath !== ''
        ? '<a href="' . htmlspecialchars($productUrl, ENT_QUOTES, 'UTF-8') . '" class="relative aspect-square bg-white flex items-center justify-center p-8 overflow-hidden"><img alt="' . htmlspecialchars((string) $product['name'], ENT_QUOTES, 'UTF-8') . '" class="max-h-full object-contain transition-transform duration-300 group-hover:scale-105" src="' . htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8') . '"/></a>'
        : '<a href="' . htmlspecialchars($productUrl, ENT_QUOTES, 'UTF-8') . '" class="relative aspect-square bg-white flex items-center justify-center p-8 overflow-hidden"><div class="w-full h-full flex items-center justify-center text-center text-[#0f1724] font-black tracking-[0.28em]">FITS MOTOR</div></a>';

    $badgeHtml = $stock <= 0
        ? '<span class="absolute top-4 left-4 px-3 py-1 bg-danger-red text-white text-[10px] font-bold rounded uppercase tracking-tighter">STOK HABIS</span>'
        : ($badgeLabel !== ''
            ? '<span class="absolute top-4 left-4 px-3 py-1 bg-primary-container text-white text-[10px] font-bold rounded uppercase tracking-tighter">' . htmlspecialchars($badgeLabel, ENT_QUOTES, 'UTF-8') . '</span>'
            : '<span class="absolute top-4 left-4 px-3 py-1 bg-[#38A169] text-white text-[10px] font-bold rounded uppercase tracking-tighter">READY</span>');

    $discountHtml = $hasDiscount
        ? '<div class="flex items-center gap-2 mb-1"><span class="text-xs text-text-secondary line-through">' . htmlspecialchars(formatRupiah((float) $product['price']), ENT_QUOTES, 'UTF-8') . '</span><span class="text-secondary-fixed-dim text-xs font-bold">PROMO</span></div>'
        : '<div class="flex items-center gap-2 mb-1"><span class="text-xs text-text-secondary">Harga Terbaik</span></div>';

    $buttonHtml = $stock > 0
        ? '<div class="grid grid-cols-2 gap-2 mt-5">'
            . '<form class="fits-cart-form" method="post">'
            . '<input type="hidden" name="product_id" value="' . (int) $product['id'] . '"/>'
            . '<input type="hidden" name="quantity" value="1"/>'
            . '<button type="submit" name="mode" value="cart" class="w-full flex items-center justify-center gap-2 py-2 border border-primary-container text-primary-container rounded hover:bg-primary-container/10 transition-colors"><span class="material-symbols-outlined text-sm">shopping_cart</span><span class="font-label-md">KERANJANG</span></button>'
            . '</form>'
            . '<form class="fits-cart-form" method="post">'
            . '<input type="hidden" name="product_id" value="' . (int) $product['id'] . '"/>'
            . '<input type="hidden" name="quantity" value="1"/>'
            . '<button type="submit" name="mode" value="buy" class="w-full flex items-center justify-center gap-2 py-2 bg-primary-container text-white rounded hover:brightness-110 transition-all"><span class="font-label-md">BELI</span><span class="material-symbols-outlined text-sm">arrow_forward</span></button>'
            . '</form>'
            . '</div>'
        : '<div class="grid grid-cols-2 gap-2 mt-5"><button class="w-full flex items-center justify-center gap-2 py-2 border border-white/10 text-text-secondary rounded cursor-not-allowed" disabled><span class="material-symbols-outlined text-sm">shopping_cart</span><span class="font-label-md">KERANJANG</span></button><button class="w-full flex items-center justify-center gap-2 py-2 bg-surface-container text-text-secondary rounded cursor-not-allowed" disabled><span class="font-label-md">BELI</span><span class="material-symbols-outlined text-sm">arrow_forward</span></button></div>';

    $catalogCardsHtml .= '<article class="group bg-surface-card rounded-xl border border-white/5 overflow-hidden transition-all hover:scale-[1.02] hover:border-primary-container/50 hover:shadow-[0_0_40px_rgba(255,86,37,0.1)] flex flex-col">'
        . '<div class="relative">'
        . $imageHtml
        . $badgeHtml
        . '<span class="absolute top-4 right-4 bg-surface-container/90 text-white text-[10px] px-3 py-1 rounded-full font-bold">' . $stock . ' stok</span>'
        . '</div>'
        . '<div class="p-5 flex flex-col flex-1">'
        . '<span class="inline-block px-2 py-0.5 bg-surface-container border border-white/10 rounded text-[10px] text-text-secondary mb-3 w-fit">' . htmlspecialchars($compatibilityLabel, ENT_QUOTES, 'UTF-8') . '</span>'
        . '<a href="' . htmlspecialchars($productUrl, ENT_QUOTES, 'UTF-8') . '" class="font-title-lg text-white mb-2 line-clamp-2 leading-tight hover:text-primary transition-colors">' . htmlspecialchars((string) $product['name'], ENT_QUOTES, 'UTF-8') . '</a>'
        . '<div class="mt-auto">'
        . $discountHtml
        . '<div class="text-primary-container font-headline-md text-[24px] mb-2">' . htmlspecialchars(formatRupiah($currentPrice), ENT_QUOTES, 'UTF-8') . '</div>'
        . '<div class="flex items-center gap-1 text-xs text-text-secondary mb-2"><span class="font-bold text-white">' . htmlspecialchars($brandLabel, ENT_QUOTES, 'UTF-8') . '</span><span>•</span><span>' . $soldCount . ' terjual</span></div>'
        . '<p class="text-sm text-text-secondary min-h-[3.75rem]">' . htmlspecialchars($descriptionPreview !== '' ? $descriptionPreview : 'Produk siap dipakai untuk kebutuhan servis dan upgrade harian.', ENT_QUOTES, 'UTF-8') . '</p>'
        . $buttonHtml
        . '</div>'
        . '</div>'
        . '</article>';
}

$categoryOptionsHtml = '<option value="">Semua Kategori</option>';
foreach ($categories as $categoryRow) {
    $slugValue = (string) ($categoryRow['slug'] ?? '');
    $nameValue = (string) ($categoryRow['name'] ?? '');
    $selected = $category === $slugValue || $category === $nameValue ? ' selected' : '';
    $categoryOptionsHtml .= '<option value="' . htmlspecialchars($slugValue, ENT_QUOTES, 'UTF-8') . '"' . $selected . '>' . htmlspecialchars($nameValue, ENT_QUOTES, 'UTF-8') . '</option>';
}

$brandOptionsHtml = '<option value="">Semua Brand</option>';
foreach ($brands as $brandRow) {
    $brandName = (string) ($brandRow['name'] ?? '');
    $selected = $brand === $brandName ? ' selected' : '';
    $brandOptionsHtml .= '<option value="' . htmlspecialchars($brandName, ENT_QUOTES, 'UTF-8') . '"' . $selected . '>' . htmlspecialchars($brandName, ENT_QUOTES, 'UTF-8') . '</option>';
}

$catalogMainHtml = '<main class="mt-24 mb-16 max-w-[1440px] mx-auto px-8 flex flex-col lg:flex-row gap-8">'
    . '<aside class="w-full lg:w-[280px] shrink-0 lg:sticky top-28 h-fit bg-surface-container-low p-6 rounded-xl border border-white/5">'
    . '<form method="get" action="' . htmlspecialchars(fitsMotorUrl('/products.php'), ENT_QUOTES, 'UTF-8') . '">'
    . '<div class="mb-8">'
    . '<h2 class="font-headline-md text-headline-md text-white inline-block border-b-4 border-primary-container pb-1 mb-6">FILTER PRODUK</h2>'
    . '<div class="mb-5">'
    . '<label class="block text-sm text-text-secondary mb-2">Pencarian</label>'
    . '<input class="w-full bg-surface-container border border-white/10 rounded-xl focus:border-primary-container focus:ring-1 focus:ring-primary-container text-white transition-all" name="search" placeholder="Cari suku cadang..." type="text" value="' . htmlspecialchars($search, ENT_QUOTES, 'UTF-8') . '"/>'
    . '</div>'
    . '<div class="mb-5"><label class="block text-sm text-text-secondary mb-2">Kategori</label><select class="w-full bg-surface-container border border-white/10 rounded-xl text-white" name="category">' . $categoryOptionsHtml . '</select></div>'
    . '<div class="mb-8"><label class="block text-sm text-text-secondary mb-2">Brand</label><select class="w-full bg-surface-container border border-white/10 rounded-xl text-white" name="brand">' . $brandOptionsHtml . '</select></div>'
    . '<button class="w-full py-4 bg-primary-container text-white font-headline-md tracking-wider hover:brightness-110 transition-all rounded uppercase" type="submit">TERAPKAN FILTER</button>'
    . ($filtersActive ? '<div class="text-right mt-4"><a class="text-xs text-text-secondary hover:text-primary transition-colors" href="' . htmlspecialchars($resetUrl, ENT_QUOTES, 'UTF-8') . '">Reset semua</a></div>' : '')
    . '</div>'
    . '</form>'
    . '</aside>'
    . '<section class="flex-1 min-w-0">'
    . '<nav class="flex items-center gap-2 mb-8 font-label-md text-label-md text-on-surface-variant"><a class="hover:text-primary" href="' . htmlspecialchars(fitsMotorUrl('/index.php'), ENT_QUOTES, 'UTF-8') . '">Beranda</a><span class="material-symbols-outlined text-[14px]">chevron_right</span><span class="text-primary">Produk</span></nav>'
    . '<div class="mb-8">'
    . '<div class="relative w-full mb-6"><span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-text-secondary" data-icon="search">search</span><form method="get" action="' . htmlspecialchars(fitsMotorUrl('/products.php'), ENT_QUOTES, 'UTF-8') . '"><input class="w-full pl-12 pr-4 py-4 bg-surface-container border border-white/10 rounded-xl focus:border-primary-container focus:ring-1 focus:ring-primary-container text-white transition-all" name="search" placeholder="Cari suku cadang, oli, atau aksesoris..." type="text" value="' . htmlspecialchars($search, ENT_QUOTES, 'UTF-8') . '"/><input type="hidden" name="category" value="' . htmlspecialchars($category, ENT_QUOTES, 'UTF-8') . '"/><input type="hidden" name="brand" value="' . htmlspecialchars($brand, ENT_QUOTES, 'UTF-8') . '"/></form></div>'
    . '<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 bg-surface-container/30 p-4 rounded-xl border border-white/5"><div class="font-body-md text-text-secondary">Menampilkan <span class="text-white font-bold">' . count($products) . '</span> produk aktif</div><div class="text-sm text-text-secondary">' . htmlspecialchars($filtersActive ? 'Filter aktif diterapkan.' : 'Menampilkan katalog lengkap FITS MOTOR.', ENT_QUOTES, 'UTF-8') . '</div></div>'
    . '</div>'
    . ($catalogCardsHtml !== '' ? '<div class="product-grid mb-12">' . $catalogCardsHtml . '</div>' : '<div class="bg-surface-card rounded-xl border border-white/5 p-10 text-center"><h2 class="font-headline-md text-headline-md text-white mb-3">Produk Tidak Ditemukan</h2><p class="text-text-secondary mb-6">Coba ubah kata kunci, kategori, atau brand untuk melihat produk lain.</p><a class="inline-flex items-center gap-2 bg-primary-container text-white px-6 py-3 rounded-lg font-bold" href="' . htmlspecialchars($resetUrl, ENT_QUOTES, 'UTF-8') . '">Lihat Semua Produk</a></div>')
    . '</section>'
    . '</main>';

$cartAddUrl = fitsMotorUrl('/api/cart-add.php');
$checkoutUrl = fitsMotorUrl('/checkout.php');
$cartScript = <<<HTML
<script>
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('.fits-cart-form');
    const notify = function (message, tone) {
        if (window.fitsMotorNotify) {
            window.fitsMotorNotify(message, tone);
            return;
        }

        window.alert(message);
    };

    const syncCartBadge = function (count) {
        if (typeof count === 'undefined' || count === null) {
            return;
        }

        if (window.fitsMotorUpdateCartBadge) {
            window.fitsMotorUpdateCartBadge(count);
        }
    };

    const postForm = async function (payload) {
        const body = new URLSearchParams();

        Object.keys(payload || {}).forEach(function (key) {
            const value = payload[key];

            if (value === null || typeof value === 'undefined') {
                return;
            }

            body.append(key, typeof value === 'boolean' ? (value ? '1' : '0') : String(value));
        });

        const response = await fetch('{$cartAddUrl}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                'Accept': 'application/json'
            },
            body: body.toString()
        });

        return response.json();
    };

    forms.forEach(function (form) {
        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            const submitter = event.submitter;
            const payload = Object.fromEntries(new FormData(form).entries());
            payload.buy_now = submitter && submitter.value === 'buy';

            try {
                const result = await postForm(payload);

                if (result.redirect_url) {
                    notify(result.message || 'Silakan login sebagai customer terlebih dahulu.');
                    window.setTimeout(function () {
                        window.location.href = result.redirect_url;
                    }, 180);
                    return;
                }

                if (!result.success) {
                    notify(result.message || 'Produk gagal diproses.');
                    return;
                }

                syncCartBadge(result.cart_count);

                if (payload.buy_now) {
                    window.location.href = '{$checkoutUrl}';
                    return;
                }

                notify(result.message || 'Produk berhasil ditambahkan ke keranjang.', 'success');
            } catch (error) {
                notify('Gagal menghubungkan produk ke keranjang.');
            }
        });
    });
});
</script>
HTML;

$options = fitsMotorPublicPageOptions();
$options['extra_body_end'] .= $cartScript;
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static fn (string $html): string => fitsMotorPregReplaceFirst('/<main\b[^>]*>.*?<\/main>/is', $catalogMainHtml, $html)
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('katalog_produk_fits_motor/code.php'),
    $options
);

