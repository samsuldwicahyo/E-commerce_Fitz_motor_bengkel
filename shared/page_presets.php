<?php
declare(strict_types=1);

require_once __DIR__ . '/render_page.php';
require_once __DIR__ . '/../includes/bootstrap.php';

function fitsMotorPregReplaceFirst(string $pattern, string $replacement, string $subject): string
{
    $result = preg_replace($pattern, $replacement, $subject, 1);

    return is_string($result) ? $result : $subject;
}

function fitsMotorBuildAlertBox(?string $message, string $tone = 'error'): string
{
    if ($message === null || trim($message) === '') {
        return '';
    }

    $isSuccess = $tone === 'success';
    $background = $isSuccess ? 'bg-tertiary/10 border-tertiary text-tertiary' : 'bg-error-container/20 border-error text-error';
    $icon = $isSuccess ? 'check_circle' : 'warning';

    return '<div class="mb-6 border p-4 rounded flex items-center gap-3 ' . $background . '"><span class="material-symbols-outlined">' . $icon . '</span><p class="font-label-md">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p></div>';
}

function fitsMotorBuildContextPanel(string $title, string $bodyHtml, string $tone = 'default'): string
{
    $toneClasses = match ($tone) {
        'success' => 'border-tertiary/40 bg-tertiary/10',
        'warning' => 'border-secondary/40 bg-secondary/10',
        'error' => 'border-error/40 bg-error-container/20',
        default => 'border-primary/30 bg-surface-card/95',
    };

    return '<section class="max-w-[1440px] mx-auto px-8 pt-8"><div class="rounded-xl border ' . $toneClasses . ' p-6 shadow-xl"><h2 class="font-headline-md text-headline-md text-white uppercase tracking-wider mb-4">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h2><div class="text-on-surface-variant font-body-md space-y-3">' . $bodyHtml . '</div></div></section>';
}

function fitsMotorInjectInsideMainStart(string $html, string $content): string
{
    if ($content === '') {
        return $html;
    }

    $updated = preg_replace('/<main\b[^>]*>/i', '$0' . $content, $html, 1);

    return is_string($updated) ? $updated : $html;
}

function fitsMotorApplyAdditionalTransform(array $options, callable $transform): array
{
    $existing = $options['transform_html'] ?? null;

    $options['transform_html'] = static function (string $html) use ($existing, $transform): string {
        if (is_callable($existing)) {
            $html = (string) $existing($html);
        }

        return (string) $transform($html);
    };

    return $options;
}

function fitsMotorReplaceSectionById(string $html, string $sectionId, string $replacement): string
{
    $pattern = '/<section\b[^>]*id="' . preg_quote($sectionId, '/') . '"[^>]*>.*?<\/section>/is';

    return fitsMotorPregReplaceFirst($pattern, $replacement, $html);
}

function fitsMotorRewriteTagAttribute(string $tag, string $attribute, ?string $value = null): string
{
    $tag = preg_replace('/\s+' . preg_quote($attribute, '/') . '=(["\']).*?\1/i', '', $tag) ?? $tag;
    $tag = preg_replace('/\s+' . preg_quote($attribute, '/') . '(?=[\s>])/i', '', $tag) ?? $tag;

    $attributeHtml = $value === null
        ? ' ' . $attribute
        : ' ' . $attribute . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';

    return preg_replace('/\s*\/?>$/', $attributeHtml . '$0', $tag, 1) ?? $tag;
}

function fitsMotorTransformFormByIndex(string $html, int $targetIndex, callable $transform): string
{
    $index = -1;

    $result = preg_replace_callback('/<form\b[^>]*>.*?<\/form>/is', static function (array $matches) use (&$index, $targetIndex, $transform): string {
        $index++;

        if ($index !== $targetIndex) {
            return $matches[0];
        }

        return (string) $transform($matches[0]);
    }, $html, 1 + $targetIndex);

    return is_string($result) ? $result : $html;
}

function fitsMotorTransformInputByType(string $html, string $type, int $targetIndex, callable $transform): string
{
    $index = -1;

    $result = preg_replace_callback('/<input\b[^>]*type="' . preg_quote($type, '/') . '"[^>]*\/?>/i', static function (array $matches) use (&$index, $targetIndex, $transform): string {
        $index++;

        if ($index !== $targetIndex) {
            return $matches[0];
        }

        return (string) $transform($matches[0]);
    }, $html, 1 + $targetIndex);

    return is_string($result) ? $result : $html;
}

function fitsMotorExtractHtmlAttribute(string $html, string $attribute): ?string
{
    if (preg_match('/\b' . preg_quote($attribute, '/') . '=(["\'])(.*?)\1/is', $html, $matches) !== 1) {
        return null;
    }

    return html_entity_decode($matches[2], ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function fitsMotorPublicCartCount(): int
{
    if (!isCustomerLoggedIn()) {
        return 0;
    }

    $customerId = currentUserId();

    if ($customerId === null) {
        return 0;
    }

    global $db;

    return countCartItems($db instanceof PDO ? $db : null, $customerId);
}

function fitsMotorPublicAccountMarkup(?string $sourceTagHtml = null): string
{
    $className = trim((string) fitsMotorExtractHtmlAttribute((string) $sourceTagHtml, 'class'));
    $className = $className !== ''
        ? $className
        : 'text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors px-4 py-2';
    $logoutClass = 'inline-flex text-on-surface-variant font-label-md text-label-md hover:text-primary transition-colors';

    if (isAdminLoggedIn()) {
        return '<div class="flex items-center gap-3">'
            . '<a class="' . htmlspecialchars($className, ENT_QUOTES, 'UTF-8') . '" href="' . htmlspecialchars(fitsMotorUrl('/admin/dashboard.php'), ENT_QUOTES, 'UTF-8') . '">Admin</a>'
            . '<a class="' . htmlspecialchars($logoutClass, ENT_QUOTES, 'UTF-8') . '" href="' . htmlspecialchars(fitsMotorUrl('/admin/logout.php'), ENT_QUOTES, 'UTF-8') . '">Keluar</a>'
            . '</div>';
    }

    if (isCustomerLoggedIn()) {
        return '<div class="flex items-center gap-3">'
            . '<a class="' . htmlspecialchars($className, ENT_QUOTES, 'UTF-8') . '" href="' . htmlspecialchars(fitsMotorUrl('/customer/dashboard.php'), ENT_QUOTES, 'UTF-8') . '">Dashboard</a>'
            . '<a class="' . htmlspecialchars($logoutClass, ENT_QUOTES, 'UTF-8') . '" href="' . htmlspecialchars(fitsMotorUrl('/auth/logout.php'), ENT_QUOTES, 'UTF-8') . '">Keluar</a>'
            . '</div>';
    }

    return '<a class="' . htmlspecialchars($className, ENT_QUOTES, 'UTF-8') . '" href="' . htmlspecialchars(fitsMotorUrl('/auth/login.php'), ENT_QUOTES, 'UTF-8') . '">Masuk</a>';
}

function fitsMotorPublicCartMarkup(?string $sourceTagHtml = null): string
{
    $iconClass = trim((string) fitsMotorExtractHtmlAttribute((string) $sourceTagHtml, 'class'));
    $iconClass = preg_replace('/\bcursor-pointer\b/u', '', $iconClass) ?? $iconClass;
    $iconClass = trim($iconClass);
    $iconClass = $iconClass !== ''
        ? $iconClass
        : 'material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors';

    $badge = '';
    $count = fitsMotorPublicCartCount();

    if ($count > 0) {
        $badge = '<span data-fits-cart-badge="1" class="absolute -right-2 -top-2 min-w-[18px] h-[18px] px-1 rounded-full bg-primary-container text-white text-[10px] font-bold inline-flex items-center justify-center">'
            . htmlspecialchars((string) min($count, 99), ENT_QUOTES, 'UTF-8')
            . '</span>';
    }

    return '<a class="relative inline-flex items-center justify-center" href="' . htmlspecialchars(fitsMotorUrl('/cart.php'), ENT_QUOTES, 'UTF-8') . '" aria-label="Keranjang" data-fits-cart-trigger="1">'
        . '<span class="' . htmlspecialchars($iconClass, ENT_QUOTES, 'UTF-8') . '" data-icon="shopping_cart">shopping_cart</span>'
        . $badge
        . '</a>';
}

function fitsMotorTransformPublicHeaderActions(string $html): string
{
    if (preg_match('/<(a|button)\b[^>]*>\s*Masuk\s*<\/\1>/is', $html, $loginMatches) === 1) {
        $html = fitsMotorPregReplaceFirst(
            '/<(a|button)\b[^>]*>\s*Masuk\s*<\/\1>/is',
            fitsMotorPublicAccountMarkup($loginMatches[0]),
            $html
        );
    }

    if (preg_match('/<(span|button)\b[^>]*class=(["\']).*?material-symbols-outlined.*?\2[^>]*>\s*shopping_cart\s*<\/\1>/is', $html, $cartMatches) === 1) {
        $html = fitsMotorPregReplaceFirst(
            '/<(span|button)\b[^>]*class=(["\']).*?material-symbols-outlined.*?\2[^>]*>\s*shopping_cart\s*<\/\1>/is',
            fitsMotorPublicCartMarkup($cartMatches[0]),
            $html
        );
    }

    return $html;
}

function fitsMotorBuildLandingAjaxCartScript(): string
{
    $cartAddUrl = fitsMotorUrl('/api/cart-add.php');

    return <<<HTML
<script>
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('.fits-landing-cart-form');

    if (forms.length === 0) {
        return;
    }

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

            const payload = Object.fromEntries(new FormData(form).entries());

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
                notify(result.message || 'Produk berhasil ditambahkan ke keranjang.', 'success');
            } catch (error) {
                notify('Gagal menghubungkan produk ke keranjang.');
            }
        });
    });
});
</script>
HTML;
}

function fitsMotorBuildLandingFeaturedSection(): string
{
    global $db;

    $featuredConfigs = [
        [
            'template_name' => 'Knalpot Akrapovic Titanium',
            'badge' => 'PROMO',
            'badge_classes' => 'bg-secondary text-on-secondary',
            'compatibility' => 'HONDA CBR',
        ],
        [
            'template_name' => 'Shock Ohlins Racing Gold',
            'badge' => 'TERLARIS',
            'badge_classes' => 'bg-danger-red text-white',
            'compatibility' => 'YAMAHA R25',
        ],
        [
            'template_name' => 'Brembo Caliper Racing 4P',
            'badge' => '',
            'badge_classes' => '',
            'compatibility' => 'UNIVERSAL',
        ],
        [
            'template_name' => 'Engine Cover Carbon Fiber',
            'badge' => '',
            'badge_classes' => '',
            'compatibility' => 'KAWASAKI ZX25R',
        ],
    ];
    $cardsHtml = '';

    foreach ($featuredConfigs as $config) {
        $product = resolveProductByTemplateName($db instanceof PDO ? $db : null, (string) $config['template_name']);

        if ($product === null) {
            continue;
        }

        $stock = max(0, (int) ($product['stock'] ?? 0));
        $isSoldOut = $stock <= 0;
        $imagePath = trim((string) ($product['image'] ?? ''));

        if ($imagePath === '') {
            $productImages = fetchProductImages($db instanceof PDO ? $db : null, (int) $product['id']);
            $imagePath = trim((string) ($productImages[0]['image_path'] ?? ''));
        }

        $price = (float) (($product['discount_price'] ?? null) !== null ? $product['discount_price'] : ($product['price'] ?? 0));
        $productUrl = fitsMotorUrl('/product-detail.php?slug=' . urlencode((string) ($product['slug'] ?? '')));
        $badgeHtml = '';

        if (!$isSoldOut && trim((string) $config['badge']) !== '') {
            $badgeHtml = '<span class="absolute top-2 left-2 ' . htmlspecialchars((string) $config['badge_classes'], ENT_QUOTES, 'UTF-8') . ' px-2 py-1 font-label-md text-label-md">'
                . htmlspecialchars((string) $config['badge'], ENT_QUOTES, 'UTF-8')
                . '</span>';
        }

        $imageHtml = $imagePath !== ''
            ? '<a href="' . htmlspecialchars($productUrl, ENT_QUOTES, 'UTF-8') . '" class="block relative aspect-square mb-6 bg-surface-container-highest flex items-center justify-center overflow-hidden"><img class="w-4/5 h-4/5 object-contain transition-transform duration-500 group-hover:scale-110" src="' . htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars((string) $product['name'], ENT_QUOTES, 'UTF-8') . '"/>' . $badgeHtml . ($isSoldOut ? '<div class="absolute inset-0 bg-background/60 z-10 flex items-center justify-center"><span class="font-headline-md text-headline-md">STOK HABIS</span></div>' : '') . '</a>'
            : '<a href="' . htmlspecialchars($productUrl, ENT_QUOTES, 'UTF-8') . '" class="block relative aspect-square mb-6 bg-surface-container-highest flex items-center justify-center overflow-hidden"><div class="w-full h-full flex items-center justify-center text-center text-white/60 tracking-[0.28em] font-black">FITS MOTOR</div>' . $badgeHtml . ($isSoldOut ? '<div class="absolute inset-0 bg-background/60 z-10 flex items-center justify-center"><span class="font-headline-md text-headline-md">STOK HABIS</span></div>' : '') . '</a>';

        $buttonHtml = $isSoldOut
            ? '<button class="mt-6 w-full bg-white/5 text-on-surface-variant py-3 font-label-md text-label-md cursor-not-allowed" disabled>Habis Terjual</button>'
            : '<form class="fits-landing-cart-form mt-6" method="post">'
                . '<input type="hidden" name="product_id" value="' . (int) $product['id'] . '"/>'
                . '<input type="hidden" name="quantity" value="1"/>'
                . '<button type="submit" class="w-full border border-primary text-primary py-3 font-label-md text-label-md flex items-center justify-center gap-2 hover:bg-primary hover:text-on-primary transition-all"><span class="material-symbols-outlined" data-icon="shopping_basket">shopping_basket</span>Keranjang</button>'
                . '</form>';

        $cardsHtml .= '<div class="bg-surface-card border border-white/5 p-4 flex flex-col group' . ($isSoldOut ? ' opacity-60' : '') . '">'
            . $imageHtml
            . '<div class="flex-grow">'
            . '<div class="flex flex-wrap gap-2 mb-3"><span class="px-2 py-0.5 bg-surface-accent text-[10px] font-bold text-white rounded-full">' . htmlspecialchars((string) $config['compatibility'], ENT_QUOTES, 'UTF-8') . '</span></div>'
            . '<a href="' . htmlspecialchars($productUrl, ENT_QUOTES, 'UTF-8') . '" class="block font-title-lg text-title-lg mb-2 hover:text-primary transition-colors">' . htmlspecialchars((string) $product['name'], ENT_QUOTES, 'UTF-8') . '</a>'
            . '<p class="font-data-mono text-data-mono ' . ($isSoldOut ? 'text-on-surface-variant' : 'text-primary-container') . ' text-lg">' . htmlspecialchars(formatIdrText($price), ENT_QUOTES, 'UTF-8') . '</p>'
            . '</div>'
            . $buttonHtml
            . '</div>';
    }

    return '<section class="py-24 bg-background">'
        . '<div class="max-w-[1440px] mx-auto px-8">'
        . '<div class="flex justify-between items-end mb-12">'
        . '<div><h2 class="font-headline-lg text-headline-lg mb-2">PRODUK UNGGULAN</h2><div class="h-1 w-24 bg-primary-container"></div></div>'
        . '<a class="text-primary font-label-md text-label-md flex items-center hover:underline" href="' . htmlspecialchars(fitsMotorUrl('/products.php'), ENT_QUOTES, 'UTF-8') . '">Lihat Semua Produk <span class="material-symbols-outlined ml-2" data-icon="arrow_forward">arrow_forward</span></a>'
        . '</div>'
        . '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter">' . $cardsHtml . '</div>'
        . '</div>'
        . '</section>';
}

function fitsMotorTransformLandingFooterLinks(string $html): string
{
    $contactUrl = htmlspecialchars(fitsMotorUrl('/contact.php'), ENT_QUOTES, 'UTF-8');
    $whatsAppUrl = htmlspecialchars(whatsappDirectUrl(WA_NUMBER, WA_MESSAGE), ENT_QUOTES, 'UTF-8');

    $updated = preg_replace_callback('/<div class="flex space-x-4">.*?<\/div>/is', static function (array $matches) use ($contactUrl, $whatsAppUrl): string {
        $block = $matches[0];
        $block = fitsMotorPregReplaceFirst('/<a\b([^>]*)href=(["\'])#\2([^>]*)>/', '<a$1href="' . $contactUrl . '"$3>', $block);
        $block = fitsMotorPregReplaceFirst('/<a\b([^>]*)href=(["\'])#\2([^>]*)>/', '<a$1href="' . $whatsAppUrl . '" target="_blank" rel="noopener noreferrer"$3>', $block);

        return $block;
    }, $html, 1);

    return is_string($updated) ? $updated : $html;
}

function fitsMotorTransformLandingHeroCtas(string $html): string
{
    $productsUrl = htmlspecialchars(fitsMotorUrl('/products.php'), ENT_QUOTES, 'UTF-8');
    $bookingUrl = htmlspecialchars(fitsMotorUrl('/booking.php'), ENT_QUOTES, 'UTF-8');

    $html = fitsMotorPregReplaceFirst(
        '/<button class="bg-primary-container text-white font-headline-md text-headline-md px-10 py-4 hover:brightness-110 transition-all active:scale-95">\s*BELANJA SEKARANG\s*<\/button>/is',
        '<a class="inline-flex items-center justify-center bg-primary-container text-white font-headline-md text-headline-md px-10 py-4 hover:brightness-110 transition-all active:scale-95" href="' . $productsUrl . '">BELANJA SEKARANG</a>',
        $html
    );

    return fitsMotorPregReplaceFirst(
        '/<button class="border-2 border-outline text-white font-headline-md text-headline-md px-10 py-4 hover:bg-white\/10 transition-all active:scale-95">\s*BOOKING SERVIS\s*<\/button>/is',
        '<a class="inline-flex items-center justify-center border-2 border-outline text-white font-headline-md text-headline-md px-10 py-4 hover:bg-white/10 transition-all active:scale-95" href="' . $bookingUrl . '">BOOKING SERVIS</a>',
        $html
    );
}

function fitsMotorTransformLandingPage(string $html): string
{
    $html = fitsMotorTransformPublicHeaderActions($html);
    $html = fitsMotorTransformLandingHeroCtas($html);
    $html = fitsMotorPregReplaceFirst('/<!-- Featured Products -->.*?<!-- Stats Bar -->/is', '<!-- Featured Products -->' . fitsMotorBuildLandingFeaturedSection() . "\n<!-- Stats Bar -->", $html);
    $html = fitsMotorTransformLandingFooterLinks($html);

    return $html;
}

function fitsMotorStorefrontActionScript(): string
{
    $cartAddUrl = fitsMotorUrl('/api/cart-add.php');
    $cartUrl = fitsMotorUrl('/cart.php');
    $checkoutUrl = fitsMotorUrl('/checkout.php');

    return <<<HTML
<script>
document.addEventListener('DOMContentLoaded', function () {
    const endpoints = {
        cartAddUrl: '{$cartAddUrl}',
        cartUrl: '{$cartUrl}',
        checkoutUrl: '{$checkoutUrl}'
    };

    const isIconToken = function (token) {
        return /^[A-Z0-9_]+$/.test(token || '') && ((token || '').indexOf('_') !== -1 || /^[A-Z0-9]+$/.test(token || ''));
    };

    const normalize = function (text) {
        return (text || '').replace(/\\u2192/g, '').replace(/\\s+/g, ' ').trim().toUpperCase();
    };

    const normalizeActionLabel = function (text) {
        const parts = normalize(text).split(' ').filter(Boolean);

        while (parts.length > 1 && isIconToken(parts[0])) {
            parts.shift();
        }

        while (parts.length > 1 && isIconToken(parts[parts.length - 1])) {
            parts.pop();
        }

        return parts.join(' ');
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

    const postForm = async function (url, payload) {
        const body = new URLSearchParams();

        Object.keys(payload || {}).forEach(function (key) {
            const value = payload[key];

            if (value === null || typeof value === 'undefined') {
                return;
            }

            body.append(key, typeof value === 'boolean' ? (value ? '1' : '0') : String(value));
        });

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                'Accept': 'application/json'
            },
            body: body.toString()
        });

        return response.json();
    };

    const findProductName = function (button) {
        const roots = [
            button.closest('.lg\\\\:col-span-5'),
            button.closest('.group'),
            button.closest('.bg-surface-card'),
            button.closest('main')
        ].filter(Boolean);

        for (const root of roots) {
            const heading = root.querySelector('h1, h3, h4');
            if (heading && heading.textContent.trim() !== '') {
                return heading.textContent.trim();
            }
        }

        return '';
    };

    const findQuantityRoot = function (button) {
        return button.closest('.lg\\\\:col-span-5') || button.closest('main');
    };

    const getQuantity = function (button) {
        const root = findQuantityRoot(button);

        if (!root) {
            return 1;
        }

        const quantityBox = Array.from(root.querySelectorAll('div')).find(function (node) {
            return /^\\d+$/.test((node.textContent || '').trim()) && node.parentElement && node.parentElement.querySelectorAll('button').length >= 2;
        });

        if (!quantityBox) {
            return 1;
        }

        return Math.max(1, parseInt(quantityBox.textContent.trim(), 10) || 1);
    };

    const updateQuantity = function (button, delta) {
        const root = findQuantityRoot(button);

        if (!root) {
            return;
        }

        const quantityBox = Array.from(root.querySelectorAll('div')).find(function (node) {
            return /^\\d+$/.test((node.textContent || '').trim()) && node.parentElement && node.parentElement.querySelectorAll('button').length >= 2;
        });

        if (!quantityBox) {
            return;
        }

        const current = parseInt(quantityBox.textContent.trim(), 10) || 1;
        quantityBox.textContent = String(Math.max(1, current + delta));
    };

    document.querySelectorAll('button').forEach(function (button) {
        if (button.closest('form.fits-cart-form, form#detail-cart-form')) {
            return;
        }

        const label = normalizeActionLabel(button.textContent);
        const icon = normalize(button.dataset.icon || button.textContent);

        if (icon === 'SHOPPING_CART' && label === 'SHOPPING_CART') {
            button.type = 'button';
            button.addEventListener('click', function (event) {
                event.preventDefault();
                window.location.href = endpoints.cartUrl;
            });
            return;
        }

        if (label === 'REMOVE') {
            button.type = 'button';
            button.addEventListener('click', function (event) {
                event.preventDefault();
                updateQuantity(button, -1);
            });
            return;
        }

        if (label === 'ADD') {
            button.type = 'button';
            button.addEventListener('click', function (event) {
                event.preventDefault();
                updateQuantity(button, 1);
            });
            return;
        }

        if (!['KERANJANG', 'TAMBAH KE KERANJANG', 'BELI', 'BELI SEKARANG'].includes(label)) {
            return;
        }

        button.type = 'button';
        button.addEventListener('click', async function (event) {
            event.preventDefault();

            const productName = findProductName(button);
            const quantity = getQuantity(button);
            const buyNow = label === 'BELI' || label === 'BELI SEKARANG';

            if (!productName) {
                notify('Produk tidak dapat dikenali dari template halaman ini.');
                return;
            }

            try {
                const result = await postForm(endpoints.cartAddUrl, {
                    product_name: productName,
                    quantity: quantity,
                    buy_now: buyNow
                });

                if (result.redirect_url) {
                    notify(result.message || 'Silakan login sebagai customer terlebih dahulu.');
                    window.setTimeout(function () {
                        window.location.href = result.redirect_url;
                    }, 180);
                    return;
                }

                if (!result.success) {
                notify(result.message || 'Aksi gagal diproses.');
                return;
            }

                syncCartBadge(result.cart_count);

                if (buyNow) {
                    window.location.href = endpoints.checkoutUrl;
                    return;
                }

                notify(result.message || 'Produk berhasil ditambahkan ke keranjang.', 'success');
            } catch (error) {
                notify('Terjadi kesalahan saat menghubungkan tombol produk ke backend.');
            }
        });
    });
});
</script>
HTML;
}

function fitsMotorCheckoutActionScript(): string
{
    $paymentCreateUrl = fitsMotorUrl('/api/payment-create.php');

    return <<<HTML
<script>
document.addEventListener('DOMContentLoaded', function () {
    const normalize = function (text) {
        return (text || '').replace(/\\u2192/g, '').replace(/\\s+/g, ' ').trim().toUpperCase();
    };

    const postJson = async function (url, payload) {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        return response.json();
    };

    document.querySelectorAll('button').forEach(function (button) {
        if (normalize(button.textContent) !== 'KONFIRMASI & BAYAR SEKARANG') {
            return;
        }

        button.type = 'button';
        button.addEventListener('click', async function (event) {
            event.preventDefault();

            const addressCard = document.querySelector('div.border-2.border-primary-container p.text-on-surface-variant');
            const deliveryAddress = addressCard ? addressCard.textContent.trim() : '';

            try {
                const result = await postJson('{$paymentCreateUrl}', {
                    payment_method: 'transfer',
                    delivery_method: 'delivery',
                    delivery_address: deliveryAddress
                });

                if (result.redirect_url) {
                    window.location.href = result.redirect_url;
                    return;
                }

                if (window.fitsMotorNotify) {
                    window.fitsMotorNotify(result.message || 'Checkout gagal diproses.');
                } else {
                    window.alert(result.message || 'Checkout gagal diproses.');
                }
            } catch (error) {
                if (window.fitsMotorNotify) {
                    window.fitsMotorNotify('Checkout gagal diproses.');
                } else {
                    window.alert('Checkout gagal diproses.');
                }
            }
        });
    });
});
</script>
HTML;
}

function fitsMotorBookingActionScript(string $defaultDate): string
{
    return <<<HTML
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('booking-form');
    const root = document.getElementById('booking-wizard-root');

    if (!form || !root) {
        return;
    }

    const serviceInput = document.getElementById('booking-service-name');
    const dateInput = document.getElementById('booking-date');
    const timeInput = document.getElementById('booking-time');
    const estimatedCostInput = document.getElementById('booking-estimated-cost');
    const brandInput = document.getElementById('vehicle-brand-hidden');
    const typeInput = document.getElementById('vehicle-type-hidden');
    const vehicleSelect = document.getElementById('vehicle-brand-select');
    const yearInput = document.getElementById('vehicle-year-input');
    const plateInput = document.getElementById('vehicle-plate-input');
    const complaintInput = document.getElementById('vehicle-complaint-input');
    const stepPanels = Array.from(document.querySelectorAll('[data-booking-step]'));
    const stepIndicators = Array.from(document.querySelectorAll('[data-step-indicator]'));
    const nextButtons = Array.from(document.querySelectorAll('[data-booking-next]'));
    const backButtons = Array.from(document.querySelectorAll('[data-booking-back]'));
    const serviceCards = Array.from(document.querySelectorAll('[data-service-card]'));
    const timeButtons = Array.from(document.querySelectorAll('[data-time-slot]'));
    const calendarGrid = document.querySelector('[data-booking-calendar-grid]');
    const calendarLabel = document.querySelector('[data-booking-calendar-label]');
    const calendarPrev = document.querySelector('[data-booking-calendar-prev]');
    const calendarNext = document.querySelector('[data-booking-calendar-next]');
    const progressBar = document.getElementById('booking-progress-bar');
    const summaryMotor = document.getElementById('summary-motor');
    const summaryPlate = document.getElementById('summary-plate');
    const summaryServiceName = document.getElementById('summary-service-name');
    const summaryServicePrice = document.getElementById('summary-service-price');
    const summarySchedule = document.getElementById('summary-schedule');
    const summaryTotal = document.getElementById('summary-total');
    const stepPill = document.getElementById('booking-step-pill');
    const stepNote = document.getElementById('summary-step-note');
    const bookingFee = parseInt(root.dataset.bookingFee || '10000', 10) || 10000;
    const initialStep = Math.max(1, Math.min(4, parseInt(root.dataset.initialStep || '1', 10) || 1));
    const defaultDate = root.dataset.defaultDate || '{$defaultDate}';
    const defaultTime = root.dataset.defaultTime || '09:00:00';
    const initialDate = root.dataset.initialDate || defaultDate;
    const initialTime = root.dataset.initialTime || defaultTime;
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const state = {
        currentStep: initialStep,
        unlockedStep: initialStep,
        selectedDate: null,
        currentMonth: null,
        selectedService: null
    };

    const notify = function (message, tone) {
        if (window.fitsMotorNotify) {
            window.fitsMotorNotify(message, tone);
            return;
        }

        window.alert(message);
    };

    const formatIdr = function (value) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value || 0);
    };

    const parseYmd = function (value) {
        if (!/^\\d{4}-\\d{2}-\\d{2}$/.test(value || '')) {
            return null;
        }

        const parts = value.split('-').map(function (item) {
            return parseInt(item, 10);
        });

        const date = new Date(parts[0], parts[1] - 1, parts[2]);
        date.setHours(0, 0, 0, 0);

        return isNaN(date.getTime()) ? null : date;
    };

    const formatYmd = function (date) {
        return date.getFullYear()
            + '-' + String(date.getMonth() + 1).padStart(2, '0')
            + '-' + String(date.getDate()).padStart(2, '0');
    };

    const formatSchedule = function () {
        if (!state.selectedDate || !timeInput || !timeInput.value) {
            return 'Pilih tanggal dan jam booking.';
        }

        const formatter = new Intl.DateTimeFormat('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });

        return formatter.format(state.selectedDate) + ' - ' + timeInput.value.substring(0, 5) + ' WIB';
    };

    const syncVehicleChoice = function () {
        if (!vehicleSelect) {
            return;
        }

        const value = vehicleSelect.value.trim();
        const parts = value.split('|');

        if (brandInput) {
            brandInput.value = parts[0] || '';
        }

        if (typeInput) {
            typeInput.value = parts[1] || '';
        }
    };

    const updateSummary = function () {
        const brand = brandInput ? brandInput.value.trim() : '';
        const type = typeInput ? typeInput.value.trim() : '';
        const year = yearInput ? yearInput.value.trim() : '';
        const plate = plateInput ? plateInput.value.trim().toUpperCase() : '';
        const motorText = (brand + ' ' + type + (year !== '' ? ' (' + year + ')' : '')).trim();
        const serviceName = serviceInput ? serviceInput.value.trim() : '';
        const serviceLabel = state.selectedService ? state.selectedService.dataset.serviceLabel || '' : '';
        const servicePrice = state.selectedService ? parseInt(state.selectedService.dataset.servicePrice || '0', 10) || 0 : 0;
        const totalEstimate = servicePrice + bookingFee;

        if (summaryMotor) {
            summaryMotor.textContent = motorText !== '' ? motorText : 'Belum dipilih';
        }

        if (summaryPlate) {
            summaryPlate.textContent = plate !== '' ? plate : '-';
        }

        if (summaryServiceName) {
            summaryServiceName.textContent = serviceName !== '' ? serviceName : 'Belum dipilih';
        }

        if (summaryServicePrice) {
            summaryServicePrice.textContent = serviceLabel !== '' ? serviceLabel : 'Belum dipilih';
        }

        if (summarySchedule) {
            summarySchedule.innerHTML = '<span class="material-symbols-outlined text-primary text-lg">event_available</span><span>' + formatSchedule() + '</span>';
        }

        if (summaryTotal) {
            summaryTotal.textContent = formatIdr(totalEstimate);
        }

        if (estimatedCostInput) {
            estimatedCostInput.value = String(totalEstimate);
        }
    };

    const updateStepVisuals = function () {
        stepPanels.forEach(function (panel) {
            const step = parseInt(panel.getAttribute('data-booking-step') || '1', 10) || 1;
            panel.classList.toggle('hidden', step !== state.currentStep);
        });

        stepIndicators.forEach(function (indicator) {
            const step = parseInt(indicator.getAttribute('data-step-indicator') || '1', 10) || 1;
            const circle = indicator.querySelector('[data-step-circle]');
            const label = indicator.querySelector('[data-step-label]');
            const isCurrent = step === state.currentStep;
            const isUnlocked = step <= state.unlockedStep;

            if (circle) {
                circle.className = isCurrent
                    ? 'w-12 h-12 rounded-full border-2 border-primary bg-primary text-on-primary flex items-center justify-center font-bold text-lg shadow-[0_0_15px_rgba(255,181,160,0.4)]'
                    : (isUnlocked
                        ? 'w-12 h-12 rounded-full border-2 border-primary bg-surface-accent text-primary flex items-center justify-center font-bold text-lg'
                        : 'w-12 h-12 rounded-full border-2 border-white/10 bg-surface-container flex items-center justify-center font-bold text-lg text-on-surface-variant');
            }

            if (label) {
                label.className = isCurrent
                    ? 'text-primary font-label-md text-label-md'
                    : (isUnlocked
                        ? 'text-white font-label-md text-label-md'
                        : 'text-on-surface-variant font-label-md text-label-md');
            }

            indicator.disabled = step > state.unlockedStep + 1;
        });

        if (progressBar) {
            progressBar.style.width = String((state.currentStep / 4) * 100) + '%';
        }

        if (stepPill) {
            stepPill.textContent = 'Langkah ' + state.currentStep + ' / 4';
        }

        if (stepNote) {
            stepNote.textContent = state.currentStep < 4
                ? 'Lengkapi langkah ' + state.currentStep + ' lalu lanjutkan ke tahap berikutnya.'
                : 'Layanan dibayar di bengkel setelah servis selesai.';
        }
    };

    const setStep = function (step) {
        state.currentStep = Math.max(1, Math.min(4, step));
        updateStepVisuals();
    };

    const validateStep = function (step) {
        if (step === 1) {
            if ((root.dataset.customerName || '').trim() === '' || (root.dataset.customerPhone || '').trim() === '') {
                notify('Lengkapi profil customer terlebih dahulu.');
                return false;
            }

            return true;
        }

        if (step === 2) {
            syncVehicleChoice();

            if (!brandInput || !typeInput || brandInput.value.trim() === '' || typeInput.value.trim() === '') {
                notify('Pilih merk dan tipe motor terlebih dahulu.');
                return false;
            }

            if (!yearInput || yearInput.value.trim() === '') {
                notify('Tahun produksi motor wajib diisi.');
                yearInput && yearInput.focus();
                return false;
            }

            if (!plateInput || plateInput.value.trim() === '') {
                notify('Nomor plat motor wajib diisi.');
                plateInput && plateInput.focus();
                return false;
            }

            return true;
        }

        if (step === 3) {
            if (!serviceInput || serviceInput.value.trim() === '') {
                notify('Pilih jenis layanan terlebih dahulu.');
                return false;
            }

            return true;
        }

        if (step === 4) {
            if (!dateInput || dateInput.value.trim() === '') {
                notify('Pilih tanggal booking terlebih dahulu.');
                return false;
            }

            if (!timeInput || timeInput.value.trim() === '') {
                notify('Pilih jam kedatangan terlebih dahulu.');
                return false;
            }

            return true;
        }

        return true;
    };

    const selectService = function (card) {
        if (!card || !serviceInput) {
            return;
        }

        serviceInput.value = card.dataset.serviceName || '';
        state.selectedService = card;

        serviceCards.forEach(function (entry) {
            const active = entry === card;
            entry.className = active
                ? 'bg-surface-card border-2 border-primary-container p-6 relative group cursor-pointer overflow-hidden text-left'
                : 'bg-surface-card border border-white/5 p-6 hover:border-primary transition-all cursor-pointer text-left';

            const badge = entry.querySelector('.absolute.top-0.right-0');
            if (active) {
                if (!badge) {
                    const selectedBadge = document.createElement('div');
                    selectedBadge.className = 'absolute top-0 right-0 bg-primary-container text-on-primary-container px-3 py-1 font-label-md text-[10px] uppercase tracking-tighter';
                    selectedBadge.textContent = 'Terpilih';
                    entry.appendChild(selectedBadge);
                }
            } else if (badge) {
                badge.parentNode && badge.parentNode.removeChild(badge);
            }

            const iconWrap = entry.querySelector('div > div');
            const icon = entry.querySelector('.material-symbols-outlined');
            const price = entry.querySelector('.font-data-mono');
            const indicator = entry.querySelector('.flex.items-center.justify-between.mt-6 .material-symbols-outlined');

            if (iconWrap) {
                iconWrap.className = active ? 'w-12 h-12 bg-surface-accent flex items-center justify-center' : 'w-12 h-12 bg-surface-container flex items-center justify-center';
            }

            if (icon) {
                icon.className = active ? 'material-symbols-outlined text-primary text-3xl' : 'material-symbols-outlined text-on-surface-variant text-3xl';
            }

            if (price) {
                price.className = active ? 'font-data-mono text-secondary text-lg' : 'font-data-mono text-on-surface-variant text-lg';
            }

            if (indicator) {
                indicator.textContent = active ? 'check_circle' : 'circle';
                indicator.className = active ? 'material-symbols-outlined text-primary' : 'material-symbols-outlined text-white/10';
                if (active) {
                    indicator.style.fontVariationSettings = "'FILL' 1";
                } else {
                    indicator.style.fontVariationSettings = '';
                }
            }
        });

        updateSummary();
    };

    const selectTime = function (button) {
        if (!button || !timeInput || button.dataset.slotDisabled === '1' || button.disabled) {
            return;
        }

        timeInput.value = button.dataset.timeValue || '';

        timeButtons.forEach(function (entry) {
            if (entry.dataset.slotDisabled === '1' || entry.disabled) {
                return;
            }

            const active = entry === button;
            entry.className = active
                ? 'py-3 border border-primary bg-surface-accent text-white font-data-mono text-sm'
                : 'py-3 border border-white/5 bg-surface-container text-on-surface-variant font-data-mono text-sm hover:border-primary';
        });

        updateSummary();
    };

    const renderCalendar = function () {
        if (!calendarGrid || !calendarLabel || !dateInput) {
            return;
        }

        calendarGrid.innerHTML = '';
        const monthStart = new Date(state.currentMonth.getFullYear(), state.currentMonth.getMonth(), 1);
        const monthEnd = new Date(state.currentMonth.getFullYear(), state.currentMonth.getMonth() + 1, 0);
        const daysInMonth = monthEnd.getDate();
        const firstWeekday = monthStart.getDay();
        const formatter = new Intl.DateTimeFormat('id-ID', { month: 'long', year: 'numeric' });

        calendarLabel.textContent = formatter.format(monthStart);

        for (let i = 0; i < firstWeekday; i += 1) {
            const blank = document.createElement('div');
            blank.className = 'aspect-square flex items-center justify-center text-white/20 text-sm';
            blank.textContent = '';
            calendarGrid.appendChild(blank);
        }

        for (let day = 1; day <= daysInMonth; day += 1) {
            const date = new Date(monthStart.getFullYear(), monthStart.getMonth(), day);
            date.setHours(0, 0, 0, 0);
            const value = formatYmd(date);
            const isPast = date.getTime() < today.getTime();
            const isSelected = state.selectedDate && value === formatYmd(state.selectedDate);

            if (isPast) {
                const disabledCell = document.createElement('div');
                disabledCell.className = 'aspect-square flex items-center justify-center text-white/20 text-sm';
                disabledCell.textContent = String(day);
                calendarGrid.appendChild(disabledCell);
                continue;
            }

            const button = document.createElement('button');
            button.type = 'button';
            button.dataset.calendarDate = value;
            button.className = isSelected
                ? 'aspect-square flex items-center justify-center text-white text-sm bg-primary text-on-primary font-bold shadow-lg'
                : 'aspect-square flex items-center justify-center text-white text-sm hover:bg-surface-accent cursor-pointer';
            button.textContent = String(day);
            button.addEventListener('click', function () {
                state.selectedDate = date;
                dateInput.value = value;
                renderCalendar();
                updateSummary();
            });
            calendarGrid.appendChild(button);
        }
    };

    nextButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const targetStep = parseInt(button.getAttribute('data-booking-next') || '1', 10) || 1;
            if (!validateStep(state.currentStep)) {
                return;
            }

            state.unlockedStep = Math.max(state.unlockedStep, targetStep);
            setStep(targetStep);
        });
    });

    backButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const targetStep = parseInt(button.getAttribute('data-booking-back') || '1', 10) || 1;
            setStep(targetStep);
        });
    });

    stepIndicators.forEach(function (indicator) {
        indicator.addEventListener('click', function () {
            const targetStep = parseInt(indicator.getAttribute('data-step-indicator') || '1', 10) || 1;

            if (targetStep > state.unlockedStep) {
                if (targetStep === state.currentStep + 1 && validateStep(state.currentStep)) {
                    state.unlockedStep = Math.max(state.unlockedStep, targetStep);
                } else {
                    return;
                }
            }

            setStep(targetStep);
        });
    });

    serviceCards.forEach(function (card) {
        card.addEventListener('click', function () {
            selectService(card);
        });
    });

    timeButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            selectTime(button);
        });
    });

    if (calendarPrev) {
        calendarPrev.addEventListener('click', function () {
            state.currentMonth = new Date(state.currentMonth.getFullYear(), state.currentMonth.getMonth() - 1, 1);
            renderCalendar();
        });
    }

    if (calendarNext) {
        calendarNext.addEventListener('click', function () {
            state.currentMonth = new Date(state.currentMonth.getFullYear(), state.currentMonth.getMonth() + 1, 1);
            renderCalendar();
        });
    }

    if (vehicleSelect) {
        vehicleSelect.addEventListener('change', function () {
            syncVehicleChoice();
            updateSummary();
        });
    }

    [yearInput, plateInput, complaintInput].forEach(function (field) {
        if (!field) {
            return;
        }

        field.addEventListener('input', function () {
            if (field === plateInput) {
                plateInput.value = plateInput.value.toUpperCase();
            }
            updateSummary();
        });
    });

    form.addEventListener('submit', function (event) {
        syncVehicleChoice();

        if (!validateStep(2) || !validateStep(3) || !validateStep(4)) {
            event.preventDefault();
            if (!validateStep(2)) {
                setStep(2);
            } else if (!validateStep(3)) {
                setStep(3);
            } else {
                setStep(4);
            }
        }
    });

    if (serviceInput && !serviceInput.value && serviceCards.length > 0) {
        selectService(serviceCards[0]);
    } else if (serviceInput && serviceInput.value) {
        const initialServiceCard = serviceCards.find(function (card) {
            return (card.dataset.serviceName || '') === serviceInput.value;
        });
        if (initialServiceCard) {
            selectService(initialServiceCard);
        }
    }

    if (dateInput) {
        if (!dateInput.value) {
            dateInput.value = initialDate;
        }
        state.selectedDate = parseYmd(dateInput.value) || parseYmd(defaultDate) || new Date(today.getFullYear(), today.getMonth(), today.getDate() + 1);
        state.currentMonth = new Date(state.selectedDate.getFullYear(), state.selectedDate.getMonth(), 1);
    }

    if (timeInput && !timeInput.value) {
        timeInput.value = initialTime;
    }

    syncVehicleChoice();
    renderCalendar();

    if (timeInput && timeInput.value) {
        const activeTime = timeButtons.find(function (button) {
            return (button.dataset.timeValue || '') === timeInput.value;
        });
        if (activeTime) {
            selectTime(activeTime);
        }
    }

    state.unlockedStep = initialStep;
    updateSummary();
    setStep(initialStep);
});
</script>
HTML;
}

function fitsMotorTransformAuthTemplate(string $html, string $view, array $context = []): string
{
    $actionMap = [
        'login' => fitsMotorUrl('/auth/login.php'),
        'register' => fitsMotorUrl('/auth/register.php'),
        'forgot-password' => fitsMotorUrl('/auth/forgot-password.php'),
    ];

    $emailValue = htmlspecialchars((string) ($context['email'] ?? ''), ENT_QUOTES, 'UTF-8');
    $nameValue = htmlspecialchars((string) ($context['name'] ?? ''), ENT_QUOTES, 'UTF-8');
    $phoneValue = htmlspecialchars((string) ($context['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
    $alertHtml = fitsMotorBuildAlertBox($context['message'] ?? null, ($context['message_type'] ?? 'error'));

    $html = fitsMotorPregReplaceFirst('/<div class="mb-6 bg-error-container\/20 border border-error p-4 rounded flex items-center gap-3">.*?<\/div>/s', $alertHtml !== '' ? $alertHtml : '<div id="login-error" class="hidden"></div>', $html);
    $html = fitsMotorPregReplaceFirst('/<form class="space-y-6">/', '<form class="space-y-6" method="post" action="' . $actionMap['login'] . '" autocomplete="on">', $html);
    $html = fitsMotorPregReplaceFirst('/placeholder="nama@email\.com" type="email"\/>/', 'name="email" value="' . $emailValue . '" placeholder="nama@email.com" type="email" required/>', $html);
    $html = fitsMotorPregReplaceFirst('/placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢" type="password"\/>/', 'name="password" placeholder="••••••••" type="password" required/>', $html);
    $html = fitsMotorPregReplaceFirst('/type="checkbox"\/>/', 'name="remember" type="checkbox"/>', $html);

    $registerFormAlert = $view === 'register' ? $alertHtml : '';
    $html = fitsMotorPregReplaceFirst('/<form class="space-y-4">/', $registerFormAlert . '<form class="space-y-4" method="post" action="' . $actionMap['register'] . '" autocomplete="on">', $html);
    $html = fitsMotorPregReplaceFirst('/placeholder="Masukkan nama lengkap" type="text"\/>/', 'name="name" value="' . $nameValue . '" placeholder="Masukkan nama lengkap" type="text" required/>', $html);
    $html = fitsMotorPregReplaceFirst('/placeholder="nama@email\.com" type="email"\/>/', 'name="email" value="' . $emailValue . '" placeholder="nama@email.com" type="email" required/>', $html);
    $html = fitsMotorPregReplaceFirst('/placeholder="8123456789" type="tel"\/>/', 'name="phone" value="' . $phoneValue . '" placeholder="8123456789" type="tel" required/>', $html);
    $html = fitsMotorPregReplaceFirst('/placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢" type="password"\/>/', 'name="password" placeholder="••••••••" type="password" required/>', $html);
    $html = fitsMotorPregReplaceFirst('/placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢" type="password"\/>/', 'name="password_confirmation" placeholder="••••••••" type="password" required/>', $html);
    $html = fitsMotorPregReplaceFirst('/required="" type="checkbox"\/>/', 'name="terms" value="1" required type="checkbox"/>', $html);

    $forgotAlert = $view === 'forgot-password' && ($context['message_type'] ?? '') === 'error' ? $alertHtml : '';
    $html = fitsMotorPregReplaceFirst('/<form class="space-y-4">/', $forgotAlert . '<form class="space-y-4" method="post" action="' . $actionMap['forgot-password'] . '" autocomplete="on">', $html);
    $html = fitsMotorPregReplaceFirst('/placeholder="nama@email\.com" type="email"\/>/', 'name="email" value="' . $emailValue . '" placeholder="nama@email.com" type="email" required/>', $html);

    return $html;
}

function fitsMotorTransformBookingTemplate(string $html): string
{
    $html = fitsMotorPregReplaceFirst(
        '/<!-- Booking Content Grid -->\s*<section class="max-w-\[1440px\] mx-auto px-8 py-16 grid grid-cols-1 lg:grid-cols-12 gap-gutter">/s',
        '<!-- Booking Content Grid --><form id="booking-form" method="post" action="' . fitsMotorUrl('/booking.php') . '"><input type="hidden" name="service_name" id="booking-service-name" value=""/><input type="hidden" name="booking_date" id="booking-date" value=""/><input type="hidden" name="booking_time" id="booking-time" value=""/><input type="hidden" name="vehicle_brand" id="vehicle-brand-hidden" value=""/><input type="hidden" name="vehicle_type" id="vehicle-type-hidden" value=""/><section class="max-w-[1440px] mx-auto px-8 py-16 grid grid-cols-1 lg:grid-cols-12 gap-gutter">',
        $html
    );
    $html = fitsMotorPregReplaceFirst('/<!-- Stats Bar \(Decorative Brand Component\) -->/s', '</section></form><!-- Stats Bar (Decorative Brand Component) -->', $html);
    $html = fitsMotorPregReplaceFirst('/<select class="w-full bg-surface-container border border-white\/10 p-4 text-white focus:border-primary-container outline-none transition-colors appearance-none">/', '<select id="vehicle-brand-select" class="w-full bg-surface-container border border-white/10 p-4 text-white focus:border-primary-container outline-none transition-colors appearance-none" name="vehicle_choice"><option value="">Pilih Motor Anda</option><option value="Honda|CBR250RR">Honda CBR250RR</option><option value="Kawasaki|ZX-25R">Kawasaki ZX-25R</option><option value="Yamaha|R25">Yamaha R25</option></select>', $html);
    $html = fitsMotorPregReplaceFirst('/placeholder="Contoh: 2022" type="text"\/>/', 'name="vehicle_year" placeholder="Contoh: 2022" type="text" required/>', $html);
    $html = fitsMotorPregReplaceFirst('/placeholder="B 1234 ABC" type="text"\/>/', 'name="vehicle_plate" placeholder="B 1234 ABC" type="text" required/>', $html);
    $html = fitsMotorPregReplaceFirst('/placeholder="Ceritakan masalah motor Anda\.\.\." rows="1"><\/textarea>/', 'name="complaint" placeholder="Ceritakan masalah motor Anda..." rows="1"></textarea>', $html);
    $html = preg_replace('/<div class="bg-surface-card border-2 border-primary-container p-6 relative group cursor-pointer overflow-hidden">/s', '<div data-service-card class="bg-surface-card border-2 border-primary-container p-6 relative group cursor-pointer overflow-hidden">', $html, 1) ?: $html;
    $html = preg_replace('/<div class="bg-surface-card border border-white\/5 p-6 hover:border-primary transition-all cursor-pointer">/s', '<div data-service-card class="bg-surface-card border border-white/5 p-6 hover:border-primary transition-all cursor-pointer">', $html) ?: $html;
    $html = preg_replace('/<button class="py-3 border (.*?) font-data-mono text-sm">(\d{2}:\d{2})(.*?)<\/button>/s', '<button data-time-slot class="py-3 border $1 font-data-mono text-sm">$2$3</button>', $html) ?: $html;
    $html = preg_replace('/<div class="aspect-square flex items-center justify-center text-white text-sm (?:bg-primary text-on-primary font-bold shadow-lg|hover:bg-surface-accent cursor-pointer)">(\d+)<\/div>/', '<div data-calendar-day class="aspect-square flex items-center justify-center text-white text-sm hover:bg-surface-accent cursor-pointer">$1</div>', $html) ?: $html;

    return $html;
}

function fitsMotorTransformAdminLoginTemplate(string $html, array $context = []): string
{
    $alertHtml = fitsMotorBuildAlertBox($context['message'] ?? null, ($context['message_type'] ?? 'error'));
    $emailValue = htmlspecialchars((string) ($context['email'] ?? ''), ENT_QUOTES, 'UTF-8');

    $html = fitsMotorPregReplaceFirst('/<form class="space-y-6">/', $alertHtml . '<form class="space-y-6" method="post" action="' . fitsMotorUrl('/admin/login.php') . '">', $html);
    $html = fitsMotorPregReplaceFirst('/placeholder="admin@fitsmotor\.com" type="email"\/>/', 'name="email" value="' . $emailValue . '" placeholder="admin@fitsmotor.com" type="email" required/>', $html);
    $html = fitsMotorPregReplaceFirst('/placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢" type="password"\/>/', 'name="password" placeholder="••••••••" type="password" required/>', $html);

    return $html;
}

function fitsMotorTransformAuthTemplateV2(string $html, string $view, array $context = []): string
{
    $actionMap = [
        'login' => fitsMotorUrl('/auth/login.php'),
        'register' => fitsMotorUrl('/auth/register.php'),
        'forgot-password' => fitsMotorUrl('/auth/forgot-password.php'),
    ];

    $emailValue = (string) ($context['email'] ?? '');
    $nameValue = (string) ($context['name'] ?? '');
    $phoneValue = (string) ($context['phone'] ?? '');
    $alertHtml = fitsMotorBuildAlertBox($context['message'] ?? null, ($context['message_type'] ?? 'error'));

    $html = fitsMotorPregReplaceFirst('/<div class="mb-6 bg-error-container\/20 border border-error p-4 rounded flex items-center gap-3">.*?<\/div>/s', $alertHtml !== '' ? $alertHtml : '<div id="login-error" class="hidden"></div>', $html);

    $html = fitsMotorTransformFormByIndex($html, 0, static function (string $formHtml) use ($actionMap, $emailValue): string {
        $formHtml = preg_replace('/<form\b[^>]*>/i', '<form class="space-y-6" method="post" action="' . $actionMap['login'] . '" autocomplete="on">', $formHtml, 1) ?? $formHtml;
        $formHtml = fitsMotorTransformInputByType($formHtml, 'email', 0, static fn (string $input): string => fitsMotorRewriteTagAttribute(fitsMotorRewriteTagAttribute(fitsMotorRewriteTagAttribute($input, 'name', 'email'), 'value', $emailValue), 'required'));
        $formHtml = fitsMotorTransformInputByType($formHtml, 'password', 0, static fn (string $input): string => fitsMotorRewriteTagAttribute(fitsMotorRewriteTagAttribute($input, 'name', 'password'), 'required'));
        $formHtml = fitsMotorTransformInputByType($formHtml, 'checkbox', 0, static fn (string $input): string => fitsMotorRewriteTagAttribute(fitsMotorRewriteTagAttribute($input, 'name', 'remember'), 'value', '1'));

        return $formHtml;
    });

    $html = fitsMotorTransformFormByIndex($html, 1, static function (string $formHtml) use ($actionMap, $alertHtml, $view, $nameValue, $emailValue, $phoneValue): string {
        $formHtml = preg_replace('/<form\b[^>]*>/i', ($view === 'register' ? $alertHtml : '') . '<form class="space-y-4" method="post" action="' . $actionMap['register'] . '" autocomplete="on">', $formHtml, 1) ?? $formHtml;
        $formHtml = fitsMotorTransformInputByType($formHtml, 'text', 0, static fn (string $input): string => fitsMotorRewriteTagAttribute(fitsMotorRewriteTagAttribute(fitsMotorRewriteTagAttribute($input, 'name', 'name'), 'value', $nameValue), 'required'));
        $formHtml = fitsMotorTransformInputByType($formHtml, 'email', 0, static fn (string $input): string => fitsMotorRewriteTagAttribute(fitsMotorRewriteTagAttribute(fitsMotorRewriteTagAttribute($input, 'name', 'email'), 'value', $emailValue), 'required'));
        $formHtml = fitsMotorTransformInputByType($formHtml, 'tel', 0, static fn (string $input): string => fitsMotorRewriteTagAttribute(fitsMotorRewriteTagAttribute(fitsMotorRewriteTagAttribute($input, 'name', 'phone'), 'value', $phoneValue), 'required'));
        $formHtml = fitsMotorTransformInputByType($formHtml, 'password', 0, static fn (string $input): string => fitsMotorRewriteTagAttribute(fitsMotorRewriteTagAttribute($input, 'name', 'password'), 'required'));
        $formHtml = fitsMotorTransformInputByType($formHtml, 'password', 1, static fn (string $input): string => fitsMotorRewriteTagAttribute(fitsMotorRewriteTagAttribute($input, 'name', 'password_confirmation'), 'required'));
        $formHtml = fitsMotorTransformInputByType($formHtml, 'checkbox', 0, static fn (string $input): string => fitsMotorRewriteTagAttribute(fitsMotorRewriteTagAttribute(fitsMotorRewriteTagAttribute($input, 'name', 'terms'), 'value', '1'), 'required'));

        return $formHtml;
    });

    $html = fitsMotorTransformFormByIndex($html, 2, static function (string $formHtml) use ($actionMap, $alertHtml, $view, $context, $emailValue): string {
        $showError = $view === 'forgot-password' && ($context['message_type'] ?? '') === 'error';
        $formHtml = preg_replace('/<form\b[^>]*>/i', ($showError ? $alertHtml : '') . '<form class="space-y-4" method="post" action="' . $actionMap['forgot-password'] . '" autocomplete="on">', $formHtml, 1) ?? $formHtml;
        $formHtml = fitsMotorTransformInputByType($formHtml, 'email', 0, static fn (string $input): string => fitsMotorRewriteTagAttribute(fitsMotorRewriteTagAttribute(fitsMotorRewriteTagAttribute($input, 'name', 'email'), 'value', $emailValue), 'required'));

        return $formHtml;
    });

    return $html;
}

function fitsMotorTransformAdminLoginTemplateV2(string $html, array $context = []): string
{
    $alertHtml = fitsMotorBuildAlertBox($context['message'] ?? null, ($context['message_type'] ?? 'error'));
    $emailValue = (string) ($context['email'] ?? '');

    $html = fitsMotorTransformFormByIndex($html, 0, static function (string $formHtml) use ($alertHtml, $emailValue): string {
        $formHtml = preg_replace('/<form\b[^>]*>/i', $alertHtml . '<form class="space-y-6" method="post" action="' . fitsMotorUrl('/admin/login.php') . '" autocomplete="on">', $formHtml, 1) ?? $formHtml;
        $formHtml = fitsMotorTransformInputByType($formHtml, 'email', 0, static fn (string $input): string => fitsMotorRewriteTagAttribute(fitsMotorRewriteTagAttribute(fitsMotorRewriteTagAttribute($input, 'name', 'email'), 'value', $emailValue), 'required'));
        $formHtml = fitsMotorTransformInputByType($formHtml, 'password', 0, static fn (string $input): string => fitsMotorRewriteTagAttribute(fitsMotorRewriteTagAttribute($input, 'name', 'password'), 'required'));

        return $formHtml;
    });

    return $html;
}

function fitsMotorTransformTrackingTemplate(string $html, string $view, array $context = []): string
{
    $message = trim((string) ($context['message'] ?? ''));
    $messageType = (string) ($context['message_type'] ?? 'error');
    $messagePanel = $message !== '' ? fitsMotorBuildContextPanel('Informasi', '<p>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>', $messageType === 'success' ? 'success' : 'error') : '';

    if ($view === 'order-tracking') {
        $queryValue = htmlspecialchars((string) ($context['query'] ?? ''), ENT_QUOTES, 'UTF-8');
        $trackingPanel = $context['tracking_panel'] ?? '';

        $html = fitsMotorPregReplaceFirst('/<div class="flex max-w-xl">/', '<form class="flex max-w-xl" method="get" action="' . fitsMotorUrl('/order-tracking.php') . '">', $html);
        $html = fitsMotorPregReplaceFirst('/placeholder="No\. Pesanan atau No\. HP" type="text"\/>/', 'name="query" value="' . $queryValue . '" placeholder="No. Pesanan atau No. HP" type="text" required/>', $html);
        $html = fitsMotorPregReplaceFirst('/<button class="bg-primary-container[^>]*>Cari<\/button>/', '<button class="bg-primary-container text-on-primary-fixed px-8 py-3 font-headline-md rounded-r-lg hover:bg-primary transition-colors" type="submit">Cari</button></form>', $html);
        $html = fitsMotorInjectInsideMainStart($html, $trackingPanel . $messagePanel);

        return $html;
    }

    if ($view === 'contact') {
        $nameValue = htmlspecialchars((string) ($context['name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $emailValue = htmlspecialchars((string) ($context['email'] ?? ''), ENT_QUOTES, 'UTF-8');
        $phoneValue = htmlspecialchars((string) ($context['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
        $topicValue = htmlspecialchars((string) ($context['topic'] ?? 'Layanan Servis'), ENT_QUOTES, 'UTF-8');
        $bodyValue = htmlspecialchars((string) ($context['body'] ?? ''), ENT_QUOTES, 'UTF-8');
        $inlineAlert = $message !== ''
            ? '<div class="mb-6 border px-4 py-3 rounded-lg ' . ($messageType === 'success' ? 'bg-[#38A169]/10 border-[#38A169]/40 text-white' : 'bg-danger-red/10 border-danger-red/40 text-white') . '">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</div>'
            : '';

        $html = fitsMotorPregReplaceFirst('/<form class="space-y-gutter bg-surface-card p-stack-lg rounded-xl border border-white\/5">/', $inlineAlert . '<form class="space-y-gutter bg-surface-card p-stack-lg rounded-xl border border-white/5" method="post" action="' . fitsMotorUrl('/contact.php') . '">', $html);
        $html = fitsMotorPregReplaceFirst('/<input class="w-full bg-background border border-outline\/30 focus:border-primary px-4 py-3 text-white rounded-lg transition-colors" type="text"\/>/', '<input class="w-full bg-background border border-outline/30 focus:border-primary px-4 py-3 text-white rounded-lg transition-colors" name="name" value="' . $nameValue . '" type="text" required/>', $html);
        $html = fitsMotorPregReplaceFirst('/<input class="w-full bg-background border border-outline\/30 focus:border-primary px-4 py-3 text-white rounded-lg transition-colors" type="email"\/>/', '<input class="w-full bg-background border border-outline/30 focus:border-primary px-4 py-3 text-white rounded-lg transition-colors" name="email" value="' . $emailValue . '" type="email" required/>', $html);
        $html = fitsMotorPregReplaceFirst('/<input class="w-full bg-background border border-outline\/30 focus:border-primary px-4 py-3 text-white rounded-lg transition-colors" type="tel"\/>/', '<input class="w-full bg-background border border-outline/30 focus:border-primary px-4 py-3 text-white rounded-lg transition-colors" name="phone" value="' . $phoneValue . '" type="tel" required/>', $html);
        $html = fitsMotorPregReplaceFirst('/<select class="w-full bg-background border border-outline\/30 focus:border-primary px-4 py-3 text-white rounded-lg transition-colors">.*?<\/select>/s', '<select class="w-full bg-background border border-outline/30 focus:border-primary px-4 py-3 text-white rounded-lg transition-colors" name="topic"><option' . ($topicValue === 'Layanan Servis' ? ' selected' : '') . '>Layanan Servis</option><option' . ($topicValue === 'Pembelian Part' ? ' selected' : '') . '>Pembelian Part</option><option' . ($topicValue === 'Status Pesanan' ? ' selected' : '') . '>Status Pesanan</option><option' . ($topicValue === 'Keluhan Pelanggan' ? ' selected' : '') . '>Keluhan Pelanggan</option></select>', $html);
        $html = fitsMotorPregReplaceFirst('/<textarea class="w-full bg-background border border-outline\/30 focus:border-primary px-4 py-3 text-white rounded-lg transition-colors" rows="4"><\/textarea>/', '<textarea class="w-full bg-background border border-outline/30 focus:border-primary px-4 py-3 text-white rounded-lg transition-colors" name="message" rows="4" required>' . $bodyValue . '</textarea>', $html);

        return $html;
    }

    return $html;
}

function fitsMotorPublicPageOptions(): array
{
    return fitsMotorApplyAdditionalTransform([
        'route_map' => fitsMotorPublicRoutes(),
        'button_map' => fitsMotorPublicButtonRoutes(),
        'extra_body_end' => fitsMotorStorefrontActionScript(),
    ], static fn (string $html): string => fitsMotorTransformPublicHeaderActions($html));
}

function fitsMotorLandingPageOptions(): array
{
    $options = fitsMotorPublicPageOptions();
    $options['extra_body_end'] .= fitsMotorBuildLandingAjaxCartScript();

    return fitsMotorApplyAdditionalTransform($options, static fn (string $html): string => fitsMotorTransformLandingPage($html));
}

function fitsMotorAuthPageOptions(string $view, array $context = []): array
{
    $options = [
        'route_map' => fitsMotorPublicRoutes(),
        'button_map' => fitsMotorPublicButtonRoutes(),
        'transform_html' => static fn (string $html): string => fitsMotorTransformAuthTemplateV2($html, $view, $context),
        'extra_css' => 'section:nth-of-type(2) p.text-danger-red{display:none !important;}',
    ];

    if ($view === 'login') {
        $options['hide_selectors'] = [
            'body > section:nth-of-type(2)',
            'body > section:nth-of-type(3)',
        ];
    } elseif ($view === 'register') {
        $options['hide_selectors'] = [
            'body > section:nth-of-type(1)',
            'body > section:nth-of-type(3)',
        ];
    } elseif ($view === 'forgot-password') {
        $options['hide_selectors'] = [
            'body > section:nth-of-type(1)',
            'body > section:nth-of-type(2)',
        ];
        $options['extra_css'] .= (($context['message_type'] ?? '') === 'success')
            ? "\nbody > section:nth-of-type(3) > div:nth-of-type(1){display:none !important;}"
            : "\nbody > section:nth-of-type(3) > div:nth-of-type(2){display:none !important;}";
    }

    return $options;
}

function fitsMotorCommercePageOptions(string $view): array
{
    $options = [
        'route_map' => fitsMotorPublicRoutes(),
        'button_map' => array_merge(
            fitsMotorPublicButtonRoutes(),
            [
                'LANJUT KE CHECKOUT' => fitsMotorUrl('/checkout.php'),
                'CEK STATUS PESANAN' => fitsMotorUrl('/order-tracking.php'),
                'HUBUNGI KAMI' => fitsMotorUrl('/contact.php'),
            ]
        ),
        'extra_body_end' => fitsMotorStorefrontActionScript(),
    ];

    if ($view === 'cart') {
        $options['hide_selectors'] = [
            'main > div:nth-of-type(1)',
            'main > div:nth-of-type(2)',
            'main > section:nth-of-type(2)',
        ];
    } elseif ($view === 'checkout') {
        $options['hide_selectors'] = [
            'main > div:nth-of-type(1)',
            'main > div:nth-of-type(2)',
            'main > section:nth-of-type(2)',
        ];
    } elseif ($view === 'payment-instruction') {
        $options['hide_selectors'] = [
            'main > div:nth-of-type(1)',
            'main > div:nth-of-type(2)',
            'main > section:nth-of-type(2)',
        ];
    }

    return fitsMotorApplyAdditionalTransform($options, static fn (string $html): string => fitsMotorTransformPublicHeaderActions($html));
}

function fitsMotorTrackingPageOptions(string $view, array $context = []): array
{
    $options = [
        'route_map' => fitsMotorPublicRoutes(),
        'button_map' => fitsMotorPublicButtonRoutes(),
        'extra_body_end' => fitsMotorStorefrontActionScript(),
        'transform_html' => static fn (string $html): string => fitsMotorTransformTrackingTemplate($html, $view, $context),
    ];

    if ($view === 'order-tracking') {
        $options['hide_selectors'] = [
            'main > section:nth-of-type(2)',
            'main > section:nth-of-type(3)',
        ];
    } elseif ($view === 'contact') {
        $options['hide_selectors'] = [
            'main > section:nth-of-type(1)',
            'main > section:nth-of-type(3)',
        ];
    } elseif ($view === '404') {
        $options['hide_selectors'] = [
            'main > section:nth-of-type(1)',
            'main > section:nth-of-type(2)',
        ];
    }

    return fitsMotorApplyAdditionalTransform($options, static fn (string $html): string => fitsMotorTransformPublicHeaderActions($html));
}

function fitsMotorCustomerPageOptions(string $view): array
{
    $options = [
        'route_map' => fitsMotorCustomerRoutes(),
        'button_map' => array_merge(
            fitsMotorPublicButtonRoutes(),
            [
                'Keluar' => fitsMotorUrl('/auth/logout.php'),
                'Lihat Detail' => fitsMotorUrl('/customer/order-detail.php'),
                'BAYAR SEKARANG' => fitsMotorUrl('/payment-instruction.php'),
                'BELI LAGI' => fitsMotorUrl('/products.php'),
                'RESCHEDULE' => fitsMotorUrl('/booking.php'),
                'Ubah Profil' => fitsMotorUrl('/customer/profile.php'),
                'JADIKAN UTAMA' => fitsMotorUrl('/customer/addresses.php'),
            ]
        ),
        'extra_body_end' => fitsMotorStorefrontActionScript(),
    ];

    $sections = [
        'dashboard' => '#dashboard',
        'orders' => '#pesanan',
        'bookings' => '#booking',
        'order-detail' => '#detail-pesanan',
        'addresses' => '#alamat',
        'profile' => '#profil',
    ];

    $hideSelectors = [];
    foreach ($sections as $selector) {
        $hideSelectors[] = 'main ' . $selector;
    }
    $options['hide_selectors'] = $hideSelectors;

    return fitsMotorApplyAdditionalTransform($options, static fn (string $html): string => fitsMotorTransformPublicHeaderActions($html));
}

function fitsMotorAdminPageOptions(string $view, array $context = []): array
{
    $options = [
        'route_map' => fitsMotorAdminRoutes(),
        'button_map' => array_merge(
            fitsMotorPublicButtonRoutes(),
            [
                'Keluar' => fitsMotorUrl('/admin/logout.php'),
                'DETAIL' => fitsMotorUrl('/admin/orders/detail.php'),
                'Tambah Produk' => fitsMotorUrl('/admin/products/create.php'),
                'Simpan Setting' => fitsMotorUrl('/admin/settings/index.php'),
                'Update Status' => fitsMotorUrl('/admin/orders/update-status.php'),
                'Simpan & Proses' => fitsMotorUrl('/admin/orders/update-status.php'),
                'Simpan Produk' => fitsMotorUrl('/admin/products/index.php'),
                'Simpan Perubahan' => fitsMotorUrl('/admin/settings/index.php'),
            ]
        ),
    ];

    if ($view === 'login') {
        $options['hide_selectors'] = [
            'body > div',
            'body > footer',
        ];
        $options['show_selectors'] = [
            'body > section:nth-of-type(1)' => 'flex',
        ];
        $options['transform_html'] = static fn (string $html): string => fitsMotorTransformAdminLoginTemplateV2($html, $context);
        return $options;
    }

    $sections = [
        'dashboard' => '#frame-dashboard',
        'products' => '#frame-products',
        'product-form' => '#frame-product-form',
        'order-detail' => '#frame-order-detail',
        'settings' => '#frame-settings',
    ];

    $fallbacks = [
        'orders' => 'dashboard',
        'bookings' => 'dashboard',
        'booking-detail' => 'dashboard',
        'customers' => 'dashboard',
        'reports' => 'dashboard',
        'categories' => 'products',
    ];

    $hideSelectors = [];
    foreach ($sections as $selector) {
        $hideSelectors[] = 'main ' . $selector;
    }

    $options['hide_selectors'] = $hideSelectors;

    return $options;
}

function fitsMotorBookingSuccessOptions(array $context = []): array
{
    $bookingUrl = fitsMotorUrl('/booking.php');
    $dashboardUrl = fitsMotorUrl('/customer/bookings.php');
    $bookingCode = trim((string) ($context['booking_code'] ?? ''));
    $bookingMeta = trim((string) ($context['booking_meta'] ?? ''));
    $overlayBody = $bookingCode !== ''
        ? '<p style="margin:0 0 16px;color:#e2e0fc;font-weight:700;">Kode booking: <span style="color:#ffb5a0;">' . htmlspecialchars($bookingCode, ENT_QUOTES, 'UTF-8') . '</span></p>'
            . ($bookingMeta !== '' ? '<p style="margin:0 0 24px;color:#a0aec0;line-height:1.7;">' . htmlspecialchars($bookingMeta, ENT_QUOTES, 'UTF-8') . '</p>' : '')
        : '<p style="margin:0 0 24px;color:#a0aec0;line-height:1.7;">Frame sukses tidak tersedia di output Stitch, jadi wrapper PHP menampilkan konfirmasi ini tanpa mengubah template asli.</p>';

    return fitsMotorApplyAdditionalTransform([
        'route_map' => fitsMotorPublicRoutes(),
        'button_map' => array_merge(
            fitsMotorPublicButtonRoutes(),
            [
                'KONFIRMASI BOOKING' => fitsMotorUrl('/booking-success.php'),
            ]
        ),
        'extra_body_end' => fitsMotorStorefrontActionScript() . '
<div style="position:fixed;inset:0;background:rgba(12,12,31,0.76);backdrop-filter:blur(8px);display:flex;align-items:center;justify-content:center;z-index:9999;padding:24px;">
    <div style="width:min(560px,100%);background:#1e1e32;border:1px solid rgba(255,255,255,0.08);padding:32px;color:#e2e0fc;box-shadow:0 20px 60px rgba(0,0,0,0.35);text-align:center;">
        <div style="width:72px;height:72px;margin:0 auto 20px;border-radius:999px;background:rgba(116,219,157,0.16);display:flex;align-items:center;justify-content:center;">
            <span class="material-symbols-outlined" style="font-size:40px;color:#74db9d;font-variation-settings:\'FILL\' 1;">check_circle</span>
        </div>
        <h2 style="margin:0 0 12px;font-size:32px;letter-spacing:0.06em;text-transform:uppercase;">Booking Berhasil</h2>
        ' . $overlayBody . '
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="' . $bookingUrl . '" style="display:inline-block;padding:14px 24px;background:#ff5625;color:#fff;text-decoration:none;font-weight:700;">Kembali ke Booking</a>
            <a href="' . $dashboardUrl . '" style="display:inline-block;padding:14px 24px;background:transparent;border:1px solid rgba(255,255,255,0.16);color:#fff;text-decoration:none;font-weight:700;">Riwayat Booking</a>
        </div>
    </div>
</div>',
    ], static fn (string $html): string => fitsMotorTransformPublicHeaderActions($html));
}

function fitsMotorBookingPageOptions(array $context = []): array
{
    $defaultDate = date('Y-m-d', strtotime('+1 day'));
    $messagePanel = trim((string) ($context['message'] ?? '')) !== ''
        ? '<div class="max-w-[1440px] mx-auto px-8 pt-6"><div class="border border-danger-red/40 bg-danger-red/10 text-white px-5 py-4 rounded-lg">' . htmlspecialchars((string) $context['message'], ENT_QUOTES, 'UTF-8') . '</div></div>'
        : '';

    return fitsMotorApplyAdditionalTransform([
        'route_map' => fitsMotorPublicRoutes(),
        'button_map' => fitsMotorPublicButtonRoutes(),
        'transform_html' => static fn (string $html): string => fitsMotorInjectInsideMainStart(fitsMotorTransformBookingTemplate($html), $messagePanel),
        'extra_body_end' => fitsMotorStorefrontActionScript() . fitsMotorBookingActionScript($defaultDate),
        'extra_head' => '',
    ], static fn (string $html): string => fitsMotorTransformPublicHeaderActions($html));
}
