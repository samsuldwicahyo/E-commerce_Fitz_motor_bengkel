<?php
declare(strict_types=1);

require_once __DIR__ . '/shared/page_presets.php';

$slug = trim((string) queryInput('slug', ''));
$name = trim((string) queryInput('name', ''));
$productId = (int) queryInput('id', 0);
$product = $slug !== '' ? findProductBySlug($db, $slug) : null;

if ($product === null && $productId > 0) {
    $product = findProductById($db, $productId);
}

if ($product === null && $name !== '') {
    $product = resolveProductByTemplateName($db, $name);
}

if ($product === null) {
    $fallbackProducts = fetchStorefrontProducts($db);
    $product = $fallbackProducts[0] ?? null;
}

$images = $product !== null ? fetchProductImages($db, (int) $product['id']) : [];
$relatedProducts = $product !== null ? fetchRelatedProducts($db, $product, 4) : [];
$mainContentHtml = '<main class="pt-28 pb-16 px-8 max-w-[1440px] mx-auto"><section class="bg-surface-card border border-danger-red/30 rounded-xl p-10 text-center"><h1 class="font-headline-lg text-headline-lg text-white mb-3 uppercase">Produk Tidak Ditemukan</h1><p class="text-text-secondary mb-6">Produk yang Anda cari belum tersedia atau sudah tidak aktif.</p><a href="' . htmlspecialchars(fitsMotorUrl('/products.php'), ENT_QUOTES, 'UTF-8') . '" class="inline-flex items-center gap-2 bg-primary-container text-white px-6 py-3 rounded-lg font-bold">Kembali ke Katalog</a></section></main>';

if ($product !== null) {
    $imagePaths = [];
    $coverImage = trim((string) ($product['image'] ?? ''));
    if ($coverImage !== '') {
        $imagePaths[] = $coverImage;
    }

    foreach ($images as $image) {
        $imagePath = trim((string) ($image['image_path'] ?? ''));
        if ($imagePath !== '' && !in_array($imagePath, $imagePaths, true)) {
            $imagePaths[] = $imagePath;
        }
    }

    if ($imagePaths === []) {
        $imagePaths[] = '';
    }

    $mainImage = $imagePaths[0];
    $currentPrice = (float) (($product['discount_price'] ?? null) !== null ? $product['discount_price'] : $product['price']);
    $hasDiscount = ($product['discount_price'] ?? null) !== null && (float) $product['discount_price'] < (float) $product['price'];
    $stock = max(0, (int) ($product['stock'] ?? 0));
    $soldCount = max(0, (int) ($product['sold_count'] ?? 0));
    $brandLabel = trim((string) ($product['brand_name'] ?? $product['brand'] ?? '')) !== '' ? (string) ($product['brand_name'] ?? $product['brand']) : 'FITS MOTOR';
    $categoryLabel = trim((string) ($product['category_name'] ?? $product['category'] ?? 'Produk'));
    $compatibilitySource = trim((string) ($product['compatibility'] ?? ''));
    $compatibilityItems = $compatibilitySource !== '' ? preg_split('/\s*[,\/]\s*/', $compatibilitySource) : [];
    $compatibilityItems = array_values(array_filter(array_map(static fn (mixed $item): string => trim((string) $item), is_array($compatibilityItems) ? $compatibilityItems : []), static fn (string $item): bool => $item !== ''));
    if ($compatibilityItems === []) {
        $compatibilityItems = [$categoryLabel !== '' ? $categoryLabel : 'Universal'];
    }

    $descriptionHtml = nl2br(htmlspecialchars((string) ($product['description'] ?: 'Deskripsi produk belum tersedia.'), ENT_QUOTES, 'UTF-8'));
    $specRows = '';
    $specificationLines = preg_split('/\r\n|\r|\n/', trim((string) ($product['specifications'] ?? '')));
    $specificationLines = is_array($specificationLines) ? $specificationLines : [];

    foreach ($specificationLines as $line) {
        $line = trim((string) $line);
        if ($line === '') {
            continue;
        }

        $parts = preg_split('/\s*:\s*/', $line, 2);
        $label = trim((string) ($parts[0] ?? 'Detail'));
        $value = trim((string) ($parts[1] ?? $line));

        $specRows .= '<div class="grid grid-cols-2 border-b border-white/5"><div class="p-4 bg-white/5 font-label-md text-label-md text-on-surface-variant">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</div><div class="p-4 font-label-md text-label-md text-white">' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '</div></div>';
    }

    if ($specRows === '') {
        $specRows = '<div class="grid grid-cols-2 border-b border-white/5"><div class="p-4 bg-white/5 font-label-md text-label-md text-on-surface-variant">Brand</div><div class="p-4 font-label-md text-label-md text-white">' . htmlspecialchars($brandLabel, ENT_QUOTES, 'UTF-8') . '</div></div>'
            . '<div class="grid grid-cols-2 border-b border-white/5"><div class="p-4 bg-white/5 font-label-md text-label-md text-on-surface-variant">Kategori</div><div class="p-4 font-label-md text-label-md text-white">' . htmlspecialchars($categoryLabel !== '' ? $categoryLabel : 'Produk', ENT_QUOTES, 'UTF-8') . '</div></div>'
            . '<div class="grid grid-cols-2"><div class="p-4 bg-white/5 font-label-md text-label-md text-on-surface-variant">Berat</div><div class="p-4 font-label-md text-label-md text-white">' . (int) ($product['weight'] ?? 0) . ' gram</div></div>';
    }

    $thumbnailButtonsHtml = '';
    foreach ($imagePaths as $index => $imagePath) {
        $thumbnailButtonsHtml .= '<button type="button" class="' . ($index === 0 ? 'bg-surface-card rounded-lg overflow-hidden border-2 border-primary-container p-2 aspect-square flex items-center justify-center' : 'bg-surface-card rounded-lg overflow-hidden border border-white/5 p-2 aspect-square flex items-center justify-center hover:border-primary-container/50 transition-colors') . '" data-gallery-thumb="' . htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8') . '">'
            . ($imagePath !== ''
                ? '<img alt="Thumbnail ' . ($index + 1) . '" class="w-full h-full object-contain" src="' . htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8') . '"/>'
                : '<div class="w-full h-full flex items-center justify-center text-xs font-black tracking-[0.18em] text-[#0f1724]">FITS MOTOR</div>')
            . '</button>';
    }

    $compatibilityHtml = '';
    foreach ($compatibilityItems as $index => $item) {
        $compatibilityHtml .= '<button type="button" class="' . ($index === 0 ? 'px-6 py-2 rounded-full font-label-md text-label-md border-2 border-primary-container bg-primary-container text-on-primary-container transition-all' : 'px-6 py-2 rounded-full font-label-md text-label-md border-2 border-white/10 bg-transparent text-on-surface-variant hover:border-primary-container transition-all') . '">' . htmlspecialchars($item, ENT_QUOTES, 'UTF-8') . '</button>';
    }

    $relatedHtml = '';
    foreach ($relatedProducts as $relatedProduct) {
        $relatedPrice = (float) (($relatedProduct['discount_price'] ?? null) !== null ? $relatedProduct['discount_price'] : $relatedProduct['price']);
        $relatedStock = max(0, (int) ($relatedProduct['stock'] ?? 0));
        $relatedImage = trim((string) ($relatedProduct['image'] ?? ''));
        if ($relatedImage === '') {
            $relatedImages = fetchProductImages($db, (int) $relatedProduct['id']);
            $relatedImage = trim((string) ($relatedImages[0]['image_path'] ?? ''));
        }

        $relatedUrl = fitsMotorUrl('/product-detail.php?slug=' . urlencode((string) ($relatedProduct['slug'] ?? '')));
        $relatedHtml .= '<article class="group bg-surface-card rounded-xl border border-white/5 overflow-hidden transition-all hover:scale-[1.02] hover:border-primary-container/50 flex flex-col">'
            . '<a href="' . htmlspecialchars($relatedUrl, ENT_QUOTES, 'UTF-8') . '" class="relative aspect-square bg-white flex items-center justify-center p-8">'
            . ($relatedImage !== ''
                ? '<img alt="' . htmlspecialchars((string) $relatedProduct['name'], ENT_QUOTES, 'UTF-8') . '" class="max-h-full object-contain transition-transform duration-300 group-hover:scale-105" src="' . htmlspecialchars($relatedImage, ENT_QUOTES, 'UTF-8') . '"/>'
                : '<div class="w-full h-full flex items-center justify-center text-center text-[#0f1724] font-black tracking-[0.28em]">FITS MOTOR</div>')
            . '</a>'
            . '<div class="p-5 flex flex-col flex-1"><a href="' . htmlspecialchars($relatedUrl, ENT_QUOTES, 'UTF-8') . '" class="font-title-lg text-white mb-2 line-clamp-2 leading-tight hover:text-primary transition-colors">' . htmlspecialchars((string) $relatedProduct['name'], ENT_QUOTES, 'UTF-8') . '</a>'
            . '<div class="text-primary-container font-headline-md text-[24px] mb-3">' . htmlspecialchars(formatRupiah($relatedPrice), ENT_QUOTES, 'UTF-8') . '</div>'
            . ($relatedStock > 0
                ? '<div class="grid grid-cols-2 gap-2 mt-auto"><form class="fits-cart-form" method="post"><input type="hidden" name="product_id" value="' . (int) $relatedProduct['id'] . '"/><input type="hidden" name="quantity" value="1"/><button type="submit" name="mode" value="cart" class="w-full flex items-center justify-center gap-2 py-2 border border-primary-container text-primary-container rounded hover:bg-primary-container/10 transition-colors"><span class="material-symbols-outlined text-sm">shopping_cart</span><span class="font-label-md">KERANJANG</span></button></form><form class="fits-cart-form" method="post"><input type="hidden" name="product_id" value="' . (int) $relatedProduct['id'] . '"/><input type="hidden" name="quantity" value="1"/><button type="submit" name="mode" value="buy" class="w-full flex items-center justify-center gap-2 py-2 bg-primary-container text-white rounded hover:brightness-110 transition-all"><span class="font-label-md">BELI</span><span class="material-symbols-outlined text-sm">arrow_forward</span></button></form></div>'
                : '<button class="w-full py-2 bg-surface-container text-text-secondary rounded cursor-not-allowed mt-auto" disabled>STOK HABIS</button>')
            . '</div></article>';
    }

    $mainContentHtml = '<main class="pt-28 pb-16 px-8 max-w-[1440px] mx-auto">'
        . '<nav class="flex flex-wrap items-center gap-2 mb-8 font-label-md text-label-md text-on-surface-variant"><a class="hover:text-primary" href="' . htmlspecialchars(fitsMotorUrl('/index.php'), ENT_QUOTES, 'UTF-8') . '">Beranda</a><span class="material-symbols-outlined text-[14px]">chevron_right</span><a class="hover:text-primary" href="' . htmlspecialchars(fitsMotorUrl('/products.php'), ENT_QUOTES, 'UTF-8') . '">Produk</a><span class="material-symbols-outlined text-[14px]">chevron_right</span><span class="text-primary">' . htmlspecialchars((string) $product['name'], ENT_QUOTES, 'UTF-8') . '</span></nav>'
        . '<div class="grid grid-cols-1 lg:grid-cols-12 gap-12">'
        . '<div class="lg:col-span-7">'
        . '<div class="relative bg-white rounded-xl aspect-square flex items-center justify-center overflow-hidden shadow-2xl">'
        . ($mainImage !== ''
            ? '<img id="detail-main-image" alt="' . htmlspecialchars((string) $product['name'], ENT_QUOTES, 'UTF-8') . '" class="w-4/5 h-4/5 object-contain" src="' . htmlspecialchars($mainImage, ENT_QUOTES, 'UTF-8') . '"/>'
            : '<div id="detail-main-image" class="w-full h-full flex items-center justify-center text-center text-[#0f1724] font-black tracking-[0.32em]">FITS MOTOR</div>')
        . ($hasDiscount ? '<div class="absolute top-6 left-6 bg-danger-red text-white px-4 py-1 rounded-full font-label-md text-label-md font-bold tracking-wider">DISKON</div>' : '')
        . '<div class="absolute top-6 right-6 bg-[#38A169] text-white px-3 py-1 rounded-lg font-label-md text-label-md font-medium text-[12px]">STOK: ' . $stock . ' unit</div>'
        . '</div>'
        . '<div class="grid grid-cols-4 gap-4 mt-6">' . $thumbnailButtonsHtml . '</div>'
        . '</div>'
        . '<div class="lg:col-span-5 flex flex-col gap-6">'
        . '<div><span class="text-secondary font-headline-md text-[14px] tracking-[0.2em]">' . htmlspecialchars(mb_strtoupper($brandLabel), ENT_QUOTES, 'UTF-8') . '</span><h1 class="font-headline-lg text-headline-lg text-white mt-2 leading-tight uppercase">' . htmlspecialchars((string) $product['name'], ENT_QUOTES, 'UTF-8') . '</h1><div class="flex flex-wrap items-center gap-4 mt-4 text-on-surface-variant font-label-md text-label-md"><div class="flex items-center text-secondary"><span class="material-symbols-outlined text-[18px]" style="font-variation-settings: \'FILL\' 1;">star</span><span class="material-symbols-outlined text-[18px]" style="font-variation-settings: \'FILL\' 1;">star</span><span class="material-symbols-outlined text-[18px]" style="font-variation-settings: \'FILL\' 1;">star</span><span class="material-symbols-outlined text-[18px]" style="font-variation-settings: \'FILL\' 1;">star</span><span class="material-symbols-outlined text-[18px]">star_half</span><span class="ml-2 text-white font-bold">4.8</span></div><div class="w-[1px] h-4 bg-white/10"></div><span>' . $soldCount . ' terjual</span><div class="w-[1px] h-4 bg-white/10"></div><span>' . htmlspecialchars($categoryLabel !== '' ? $categoryLabel : 'Produk', ENT_QUOTES, 'UTF-8') . '</span></div></div>'
        . '<div class="bg-surface-card p-6 rounded-xl industrial-border"><div class="flex items-center gap-4">' . ($hasDiscount ? '<span class="text-on-surface-variant line-through font-body-md text-body-md">' . htmlspecialchars(formatRupiah((float) $product['price']), ENT_QUOTES, 'UTF-8') . '</span><span class="bg-[#38A169]/20 text-[#38A169] px-3 py-1 rounded-full font-label-md text-[12px] font-bold">PROMO</span>' : '<span class="text-on-surface-variant font-body-md text-body-md">Harga aktif</span>') . '</div><div class="font-headline-lg text-[42px] text-primary-container mt-1">' . htmlspecialchars(formatRupiah($currentPrice), ENT_QUOTES, 'UTF-8') . '</div></div>'
        . '<div class="flex flex-col gap-3"><label class="font-label-md text-label-md text-white">Kompatibilitas Kendaraan:</label><div class="flex flex-wrap gap-2">' . $compatibilityHtml . '</div></div>'
        . '<div class="flex flex-col gap-3"><label class="font-label-md text-label-md text-white">Jumlah Pesanan:</label><div class="flex items-center border-2 border-primary-container rounded-lg w-fit overflow-hidden"><button type="button" class="px-4 py-2 hover:bg-primary-container/10 transition-colors text-primary-container" data-detail-qty-minus><span class="material-symbols-outlined">remove</span></button><div class="w-12 text-center font-bold font-data-mono" id="detail-quantity-value">1</div><button type="button" class="px-4 py-2 hover:bg-primary-container/10 transition-colors text-primary-container" data-detail-qty-plus><span class="material-symbols-outlined">add</span></button></div></div>'
        . ($stock > 0
            ? '<form id="detail-cart-form" class="flex flex-col gap-3 mt-4" method="post"><input type="hidden" name="product_id" value="' . (int) $product['id'] . '"/><input type="hidden" name="quantity" id="detail-quantity-input" value="1"/><div class="grid grid-cols-1 md:grid-cols-2 gap-3"><button type="submit" name="mode" value="cart" class="border-2 border-primary-container text-primary-container font-headline-md text-headline-md py-4 rounded-xl hover:bg-primary-container/5 transition-all flex items-center justify-center gap-2"><span class="material-symbols-outlined">shopping_cart</span>TAMBAH KE KERANJANG</button><button type="submit" name="mode" value="buy" class="bg-primary-container text-on-primary-container font-headline-md text-headline-md py-4 rounded-xl hover:brightness-110 transition-all flex items-center justify-center gap-2"><span class="material-symbols-outlined" style="font-variation-settings: \'FILL\' 1;">bolt</span>BELI SEKARANG</button></div></form>'
            : '<div class="mt-4 rounded-xl border border-danger-red/30 bg-danger-red/10 p-4 text-white font-bold">Produk ini sedang habis. Silakan pilih produk terkait.</div>')
        . '<a href="' . htmlspecialchars(whatsappDirectUrl(WA_NUMBER, 'Halo FITS MOTOR, saya ingin tanya tentang produk ' . (string) $product['name']), ENT_QUOTES, 'UTF-8') . '" class="bg-[#38A169] text-white font-headline-md text-headline-md py-4 rounded-xl hover:brightness-110 transition-all flex items-center justify-center gap-2"><span class="material-symbols-outlined" style="font-variation-settings: \'FILL\' 1;">chat</span>Tanya via WhatsApp</a>'
        . '<div class="grid grid-cols-3 gap-2 py-6 border-t border-white/5 mt-4"><div class="flex flex-col items-center text-center gap-2"><div class="w-10 h-10 rounded-full bg-surface-accent flex items-center justify-center text-tertiary"><span class="material-symbols-outlined" data-icon="verified">verified</span></div><span class="font-label-md text-[10px] text-on-surface-variant uppercase tracking-wider">Garansi Resmi</span></div><div class="flex flex-col items-center text-center gap-2"><div class="w-10 h-10 rounded-full bg-surface-accent flex items-center justify-center text-tertiary"><span class="material-symbols-outlined" data-icon="local_shipping">local_shipping</span></div><span class="font-label-md text-[10px] text-on-surface-variant uppercase tracking-wider">Pengiriman Cepat</span></div><div class="flex flex-col items-center text-center gap-2"><div class="w-10 h-10 rounded-full bg-surface-accent flex items-center justify-center text-tertiary"><span class="material-symbols-outlined" data-icon="history">history</span></div><span class="font-label-md text-[10px] text-on-surface-variant uppercase tracking-wider">Retur 7 Hari</span></div></div>'
        . '</div>'
        . '</div>'
        . '<section class="mt-24"><div class="flex flex-wrap border-b border-white/10 mb-8"><a href="#detail-description" class="px-8 py-4 font-headline-md text-headline-md text-primary-container border-b-2 border-primary-container">Deskripsi</a><a href="#detail-specification" class="px-8 py-4 font-headline-md text-headline-md text-on-surface-variant hover:text-white transition-colors">Spesifikasi</a><a href="#detail-related" class="px-8 py-4 font-headline-md text-headline-md text-on-surface-variant hover:text-white transition-colors">Produk Terkait</a></div><div class="grid grid-cols-1 md:grid-cols-2 gap-16"><div id="detail-description"><h3 class="font-headline-md text-headline-md text-white mb-6">Deskripsi Produk</h3><div class="bg-surface-card rounded-xl overflow-hidden border border-white/5 p-6 text-on-surface-variant leading-relaxed">' . $descriptionHtml . '</div></div><div id="detail-specification"><h3 class="font-headline-md text-headline-md text-white mb-6">Spesifikasi Teknis</h3><div class="bg-surface-card rounded-xl overflow-hidden border border-white/5">' . $specRows . '</div></div></div></section>'
        . ($relatedHtml !== '' ? '<section id="detail-related" class="mt-20"><div class="flex items-center justify-between gap-4 mb-6"><h2 class="font-headline-md text-headline-md text-white">Produk Terkait</h2><a href="' . htmlspecialchars(fitsMotorUrl('/products.php'), ENT_QUOTES, 'UTF-8') . '" class="text-primary font-label-md">Lihat Semua</a></div><div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">' . $relatedHtml . '</div></section>' : '')
        . '</main>';
}

$cartAddUrl = fitsMotorUrl('/api/cart-add.php');
$checkoutUrl = fitsMotorUrl('/checkout.php');
$detailMaxQuantity = $product !== null ? max(1, (int) ($product['stock'] ?? 1)) : 1;
$detailScript = <<<HTML
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('detail-cart-form');
    const quantityInput = document.getElementById('detail-quantity-input');
    const quantityValue = document.getElementById('detail-quantity-value');
    const thumbs = Array.from(document.querySelectorAll('[data-gallery-thumb]'));
    const mainImage = document.getElementById('detail-main-image');
    const forms = Array.from(document.querySelectorAll('.fits-cart-form'));
    const maxQuantity = {$detailMaxQuantity};
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

    const submitCartPayload = async function (payload, buyNowMessage) {
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

            notify(result.message || buyNowMessage, 'success');
        } catch (error) {
            notify('Gagal menambahkan produk ke keranjang.');
        }
    };

    if (quantityInput && quantityValue) {
        const syncQuantity = function (nextValue) {
            const value = Math.max(1, Math.min(maxQuantity, nextValue));
            quantityInput.value = String(value);
            quantityValue.textContent = String(value);
        };

        document.querySelector('[data-detail-qty-minus]')?.addEventListener('click', function () {
            syncQuantity((parseInt(quantityInput.value || '1', 10) || 1) - 1);
        });

        document.querySelector('[data-detail-qty-plus]')?.addEventListener('click', function () {
            syncQuantity((parseInt(quantityInput.value || '1', 10) || 1) + 1);
        });
    }

    thumbs.forEach(function (thumb, index) {
        thumb.addEventListener('click', function () {
            thumbs.forEach(function (node, thumbIndex) {
                node.className = thumbIndex === index
                    ? 'bg-surface-card rounded-lg overflow-hidden border-2 border-primary-container p-2 aspect-square flex items-center justify-center'
                    : 'bg-surface-card rounded-lg overflow-hidden border border-white/5 p-2 aspect-square flex items-center justify-center hover:border-primary-container/50 transition-colors';
            });

            const imagePath = thumb.dataset.galleryThumb || '';
            if (mainImage && mainImage.tagName === 'IMG' && imagePath !== '') {
                mainImage.setAttribute('src', imagePath);
            }
        });
    });

    forms.forEach(function (cartForm) {
        cartForm.addEventListener('submit', async function (event) {
            event.preventDefault();

            const submitter = event.submitter;
            const payload = Object.fromEntries(new FormData(cartForm).entries());
            payload.buy_now = submitter && submitter.value === 'buy';
            await submitCartPayload(payload, 'Produk berhasil ditambahkan ke keranjang.');
        });
    });

    if (form) {
        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            const submitter = event.submitter;
            const payload = Object.fromEntries(new FormData(form).entries());
            payload.buy_now = submitter && submitter.value === 'buy';
            await submitCartPayload(payload, 'Produk berhasil ditambahkan ke keranjang.');
        });
    }
});
</script>
HTML;

$options = fitsMotorPublicPageOptions();
$options['extra_css'] = 'body > div.fixed.bottom-10.right-10{display:none !important;}';
$options['extra_body_end'] .= $detailScript;
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static fn (string $html): string => fitsMotorPregReplaceFirst('/<main\b[^>]*>.*?<\/main>/is', $mainContentHtml, $html)
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('detail_produk_fits_motor/code.php'),
    $options
);

