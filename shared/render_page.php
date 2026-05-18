<?php
declare(strict_types=1);

function fitsMotorProjectRootPath(): string
{
    static $projectRootPath;

    if ($projectRootPath !== null) {
        return $projectRootPath;
    }

    $projectRootPath = realpath(dirname(__DIR__)) ?: dirname(__DIR__);

    return $projectRootPath;
}

function fitsMotorTemplatePath(string $relativePath): string
{
    $normalizedRelativePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, ltrim($relativePath, '/\\'));
    $basePath = fitsMotorProjectRootPath() . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'stitch' . DIRECTORY_SEPARATOR . $normalizedRelativePath;

    if (is_file($basePath)) {
        return $basePath;
    }

    if (str_ends_with($normalizedRelativePath, DIRECTORY_SEPARATOR . 'code.php')) {
        $phpPath = substr($basePath, 0, -strlen('code.php')) . 'code.php';

        if (is_file($phpPath)) {
            return $phpPath;
        }
    }

    $altPhpPath = preg_replace('/\.html$/i', '.php', $basePath);

    if (is_string($altPhpPath) && is_file($altPhpPath)) {
        return $altPhpPath;
    }

    return $basePath;
}

function fitsMotorBasePath(): string
{
    static $basePath;

    if ($basePath !== null) {
        return $basePath;
    }

    $projectRoot = str_replace('\\', '/', realpath(dirname(__DIR__)) ?: dirname(__DIR__));
    $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
    $documentRoot = $documentRoot !== '' ? str_replace('\\', '/', realpath($documentRoot) ?: $documentRoot) : '';

    if ($documentRoot !== '' && str_starts_with($projectRoot, $documentRoot)) {
        $relative = trim(substr($projectRoot, strlen($documentRoot)), '/');
        $basePath = $relative === '' ? '' : '/' . $relative;
        return $basePath;
    }

    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $segments = array_values(array_filter(explode('/', trim($scriptName, '/'))));
    $basePath = isset($segments[0]) ? '/' . $segments[0] : '';

    return $basePath;
}

function fitsMotorUrl(string $path = ''): string
{
    if ($path === '') {
        return fitsMotorBasePath() !== '' ? fitsMotorBasePath() . '/' : '/';
    }

    if (preg_match('/^(?:https?:)?\/\//i', $path) === 1) {
        return $path;
    }

    return rtrim(fitsMotorBasePath(), '/') . '/' . ltrim($path, '/');
}

function fitsMotorAbsoluteUrl(string $path = ''): string
{
    if ($path !== '' && preg_match('/^(?:https?:)?\/\//i', $path) === 1) {
        return $path;
    }

    $baseUrl = defined('BASE_URL') ? rtrim((string) BASE_URL, '/') : '';

    if ($baseUrl !== '' && preg_match('/^https?:\/\//i', $baseUrl) === 1) {
        if ($path === '') {
            return $baseUrl . '/';
        }

        return $baseUrl . '/' . ltrim($path, '/');
    }

    $scheme = 'http';

    if (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443)
    ) {
        $scheme = 'https';
    }

    $host = trim((string) ($_SERVER['HTTP_HOST'] ?? 'localhost'));
    $relativeUrl = fitsMotorUrl($path);

    return $scheme . '://' . $host . $relativeUrl;
}

function fitsMotorNormalizeLabel(string $label): string
{
    $label = html_entity_decode(strip_tags($label), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $label = preg_replace('/\x{2192}/u', '', $label) ?? $label;
    $label = preg_replace('/\s+/u', ' ', $label) ?? $label;

    return trim($label);
}

function fitsMotorIsIconToken(string $token): bool
{
    if ($token === '' || preg_match('/^[A-Za-z0-9_]+$/', $token) !== 1) {
        return false;
    }

    return str_contains($token, '_') || preg_match('/^[a-z0-9]+$/', $token) === 1;
}

function fitsMotorResolveRoute(array $routeMap, string $label): ?string
{
    $normalized = fitsMotorNormalizeLabel($label);
    $candidates = [$normalized];
    $parts = array_values(array_filter(explode(' ', $normalized), static fn (string $part): bool => $part !== ''));

    $trimmedParts = $parts;

    while (count($trimmedParts) > 1 && fitsMotorIsIconToken($trimmedParts[0])) {
        array_shift($trimmedParts);
    }

    while (count($trimmedParts) > 1 && fitsMotorIsIconToken($trimmedParts[count($trimmedParts) - 1])) {
        array_pop($trimmedParts);
    }

    if ($trimmedParts !== []) {
        $candidates[] = implode(' ', $trimmedParts);
    }

    foreach (array_unique($candidates) as $candidate) {
        if (isset($routeMap[$candidate])) {
            return $routeMap[$candidate];
        }
    }

    return null;
}

function fitsMotorPublicRoutes(): array
{
    return [
        'Beranda' => fitsMotorUrl('/index.php'),
        'Produk' => fitsMotorUrl('/products.php'),
        'Booking' => fitsMotorUrl('/booking.php'),
        'Tentang' => fitsMotorUrl('/about.php'),
        'Kontak' => fitsMotorUrl('/contact.php'),
        'Masuk' => fitsMotorUrl('/auth/login.php'),
        'Konsultasi WA' => whatsappDirectUrl(WA_NUMBER, WA_MESSAGE),
        'Lihat Semua Produk' => fitsMotorUrl('/products.php'),
        'Layanan Servis' => fitsMotorUrl('/booking.php'),
        'Suku Cadang' => fitsMotorUrl('/products.php'),
        'Modifikasi' => fitsMotorUrl('/about.php'),
        'Aksesori' => fitsMotorUrl('/products.php'),
        'Garansi' => fitsMotorUrl('/contact.php'),
        'Karir' => fitsMotorUrl('/about.php'),
        'Kebijakan Privasi' => fitsMotorUrl('/about.php'),
        'Syarat & Ketentuan' => fitsMotorUrl('/about.php'),
        'Daftar Sekarang' => fitsMotorUrl('/auth/register.php'),
        'Lupa Password?' => fitsMotorUrl('/auth/forgot-password.php'),
    ];
}

function fitsMotorPublicButtonRoutes(): array
{
    return [
        'Masuk' => fitsMotorUrl('/auth/login.php'),
        'Konsultasi WA' => whatsappDirectUrl(WA_NUMBER, WA_MESSAGE),
        'Masuk dengan WhatsApp OTP' => whatsappDirectUrl(WA_NUMBER, WA_MESSAGE),
        'BELANJA SEKARANG' => fitsMotorUrl('/products.php'),
        'BOOKING SERVIS' => fitsMotorUrl('/booking.php'),
        'Lihat Semua Produk' => fitsMotorUrl('/products.php'),
        'Lihat Semua' => fitsMotorUrl('/customer/orders.php'),
        'LANJUT KE CHECKOUT' => fitsMotorUrl('/checkout.php'),
        'CEK STATUS PESANAN' => fitsMotorUrl('/order-tracking.php'),
        'HUBUNGI KAMI' => fitsMotorUrl('/contact.php'),
        'Kembali ke Login' => fitsMotorUrl('/auth/login.php'),
    ];
}

function fitsMotorCustomerRoutes(): array
{
    return array_merge(
        fitsMotorPublicRoutes(),
        [
            'Dashboard' => fitsMotorUrl('/customer/dashboard.php'),
            'Pesanan Saya' => fitsMotorUrl('/customer/orders.php'),
            'Booking Saya' => fitsMotorUrl('/customer/bookings.php'),
            'Alamat' => fitsMotorUrl('/customer/addresses.php'),
            'Profil' => fitsMotorUrl('/customer/profile.php'),
            'Lihat Semua' => fitsMotorUrl('/customer/orders.php'),
            'Keluar' => fitsMotorUrl('/auth/logout.php'),
        ]
    );
}

function fitsMotorAdminRoutes(): array
{
    return array_merge(
        fitsMotorPublicRoutes(),
        [
            'Dashboard' => fitsMotorUrl('/admin/dashboard.php'),
            'Semua Produk' => fitsMotorUrl('/admin/products/index.php'),
            'Tambah Produk' => fitsMotorUrl('/admin/products/create.php'),
            'Kategori' => fitsMotorUrl('/admin/categories/index.php'),
            'Brand' => fitsMotorUrl('/admin/categories/index.php?type=brand'),
            'Semua Pesanan' => fitsMotorUrl('/admin/orders/index.php'),
            'Booking Servis' => fitsMotorUrl('/admin/bookings/index.php'),
            'Data Pelanggan' => fitsMotorUrl('/admin/customers/index.php'),
            'Laporan Penjualan' => fitsMotorUrl('/admin/reports/sales.php'),
            'Setting Toko' => fitsMotorUrl('/admin/settings/index.php'),
            'Lihat Semua' => fitsMotorUrl('/admin/orders/index.php'),
        ]
    );
}

function fitsMotorReplaceLinks(string $html, array $routeMap): string
{
    $updatedHtml = preg_replace_callback(
        '/<a\b(?<before>[^>]*?)href=(["\'])(?<href>.*?)\2(?<after>[^>]*)>(?<label>.*?)<\/a>/is',
        static function (array $matches) use ($routeMap): string {
            $route = fitsMotorResolveRoute($routeMap, $matches['label']);

            if ($route === null) {
                return $matches[0];
            }

            $href = htmlspecialchars($route, ENT_QUOTES, 'UTF-8');

            return '<a' . $matches['before'] . 'href="' . $href . '"' . $matches['after'] . '>' . $matches['label'] . '</a>';
        },
        $html
    );

    return is_string($updatedHtml) ? $updatedHtml : $html;
}

function fitsMotorReplaceKnownPlaceholderUrls(string $html): string
{
    $whatsAppUrl = htmlspecialchars(whatsappDirectUrl(WA_NUMBER, WA_MESSAGE), ENT_QUOTES, 'UTF-8');
    $updatedHtml = preg_replace('/href=(["\'])https:\/\/wa\.me\/fitsmotor\1/i', 'href="' . $whatsAppUrl . '"', $html);

    return is_string($updatedHtml) ? $updatedHtml : $html;
}

function fitsMotorInjectBeforeTag(string $html, string $tag, string $content): string
{
    if ($content === '') {
        return $html;
    }

    $updatedHtml = preg_replace('/' . preg_quote($tag, '/') . '/i', $content . $tag, $html, 1);

    return is_string($updatedHtml) ? $updatedHtml : $html . $content;
}

function fitsMotorBuildCss(array $hideSelectors, array $showSelectors, string $extraCss): string
{
    $rules = [];

    foreach ($hideSelectors as $selector) {
        $rules[] = $selector . '{display:none !important;}';
    }

    foreach ($showSelectors as $selector => $display) {
        $rules[] = $selector . '{display:' . $display . ' !important;}';
    }

    if ($extraCss !== '') {
        $rules[] = $extraCss;
    }

    if ($rules === []) {
        return '';
    }

    return "<style>\n" . implode("\n", $rules) . "\n</style>\n";
}

function fitsMotorIconStylesheets(): string
{
    return implode(
        "\n",
        [
            '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>',
            '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>',
            '<link rel="stylesheet" href="' . htmlspecialchars(fitsMotorUrl('/assets/css/style.css'), ENT_QUOTES, 'UTF-8') . '"/>',
            '<style>.material-icons{font-weight:normal;font-style:normal;font-size:24px;display:inline-block;line-height:1;text-transform:none;letter-spacing:normal;word-wrap:normal;white-space:nowrap;direction:ltr;-webkit-font-feature-settings:"liga";-webkit-font-smoothing:antialiased;}</style>',
        ]
    ) . "\n";
}

function fitsMotorBuildUtilityScript(): string
{
    $cartUrl = fitsMotorUrl('/cart.php');

    return <<<HTML
<script>
document.addEventListener('DOMContentLoaded', function () {
    const cartUrl = '{$cartUrl}';

    const normalize = function (text) {
        return (text || '').replace(/\\u2192/g, '').replace(/\\s+/g, ' ').trim().toUpperCase();
    };

    const notify = function (message, tone) {
        const text = (message || '').trim();

        if (!text) {
            return;
        }

        const toast = document.createElement('div');
        const success = tone === 'success';

        toast.className = 'fixed top-6 right-6 z-[9999] max-w-sm border px-5 py-4 shadow-2xl text-white transition-all duration-300';
        toast.style.background = success ? '#16213E' : '#2c1420';
        toast.style.borderColor = success ? 'rgba(56,161,105,0.55)' : 'rgba(229,62,62,0.55)';
        toast.style.boxShadow = success
            ? '0 12px 28px rgba(56,161,105,0.18)'
            : '0 12px 28px rgba(229,62,62,0.18)';
        toast.innerHTML = '<div style="display:flex;align-items:flex-start;gap:12px;">'
            + '<span class="material-symbols-outlined" style="color:' + (success ? '#38A169' : '#ff8f8f') + ';font-variation-settings:\\'FILL\\' 1;">'
            + (success ? 'check_circle' : 'warning')
            + '</span>'
            + '<div><p style="margin:0;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;font-size:12px;">'
            + (success ? 'Berhasil' : 'Perhatian')
            + '</p><p style="margin:4px 0 0;color:#e2e0fc;line-height:1.5;">'
            + text.replace(/[&<>]/g, function (char) {
                return ({'&':'&amp;','<':'&lt;','>':'&gt;'})[char];
            })
            + '</p></div></div>';

        document.body.appendChild(toast);

        window.setTimeout(function () {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-8px)';
        }, 2600);

        window.setTimeout(function () {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 3200);
    };

    window.fitsMotorNotify = notify;

    const cartBadgeSelectors = '[data-fits-cart-trigger], a[aria-label="Keranjang"], button[aria-label="Keranjang"]';

    const updateCartBadge = function (count) {
        const nextCount = Math.max(0, parseInt(count, 10) || 0);

        document.querySelectorAll(cartBadgeSelectors).forEach(function (trigger) {
            let badge = trigger.querySelector('[data-fits-cart-badge]');

            if (nextCount <= 0) {
                if (badge && badge.parentNode) {
                    badge.parentNode.removeChild(badge);
                }
                return;
            }

            if (!badge) {
                badge = document.createElement('span');
                badge.dataset.fitsCartBadge = '1';
                badge.className = 'absolute -right-2 -top-2 min-w-[18px] h-[18px] px-1 rounded-full bg-primary-container text-white text-[10px] font-bold inline-flex items-center justify-center';
                trigger.appendChild(badge);
            }

            badge.textContent = String(Math.min(nextCount, 99));
        });
    };

    window.fitsMotorUpdateCartBadge = updateCartBadge;

    const bindOnce = function (element, key) {
        if (!element) {
            return false;
        }

        const datasetKey = 'fitsBound' + key;

        if (element.dataset[datasetKey] === '1') {
            return false;
        }

        element.dataset[datasetKey] = '1';

        return true;
    };

    document.querySelectorAll('.material-symbols-outlined, .material-icons').forEach(function (icon) {
        const token = normalize(icon.dataset.icon || icon.textContent);

        if ((token === 'VISIBILITY' || token === 'VISIBILITY_OFF') && bindOnce(icon, 'Visibility')) {
            const scope = icon.closest('.relative') || icon.parentElement;
            const input = scope ? scope.querySelector('input[type="password"], input[type="text"]') : null;

            if (input && (input.type === 'password' || input.type === 'text')) {
                icon.style.cursor = 'pointer';
                icon.addEventListener('click', function (event) {
                    event.preventDefault();

                    const showPassword = input.type === 'password';
                    input.type = showPassword ? 'text' : 'password';
                    icon.textContent = showPassword ? 'visibility_off' : 'visibility';
                });
            }
        }

        if (token === 'CONTENT_COPY' && bindOnce(icon, 'Copy')) {
            const trigger = icon.closest('button') || icon;

            trigger.style.cursor = 'pointer';
            trigger.addEventListener('click', async function (event) {
                event.preventDefault();

                const container = trigger.closest('div');
                const copyTarget = container ? Array.from(container.querySelectorAll('span, p')).find(function (node) {
                    const value = (node.textContent || '').trim();
                    return value !== '' && value !== icon.textContent.trim();
                }) : null;

                const value = copyTarget ? copyTarget.textContent.trim() : '';

                if (!value) {
                    return;
                }

                try {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        await navigator.clipboard.writeText(value);
                    }
                } catch (error) {
                    window.prompt('Salin nomor ini:', value);
                    return;
                }

                notify('Teks berhasil disalin.', 'success');
            });
        }

        if (token === 'SHOPPING_CART') {
            const trigger = icon.closest('button, a, [role="button"]') || icon;
            const label = normalize(trigger.textContent).replace(/\\b\\d+\\b/g, '').trim();

            if (/KERANJANG|TAMBAH|BELI/.test(label)) {
                return;
            }

            if (!/SHOPPING_CART/.test(label) && trigger !== icon) {
                return;
            }

            if (!bindOnce(trigger, 'CartShortcut')) {
                return;
            }

            trigger.style.cursor = 'pointer';
            trigger.addEventListener('click', function (event) {
                event.preventDefault();
                window.location.href = cartUrl;
            });
        }

        if (token === 'NOTIFICATIONS' && bindOnce(icon, 'Notifications')) {
            const trigger = icon.closest('button, a, [role="button"]') || icon;

            trigger.style.cursor = 'pointer';
            trigger.addEventListener('click', function (event) {
                event.preventDefault();
                notify('Belum ada notifikasi baru.', 'success');
            });
        }
    });

    document.querySelectorAll('button').forEach(function (button) {
        const label = normalize(button.textContent);

        if (label === 'LIHAT DETAIL PRODUK' && bindOnce(button, 'Accordion')) {
            const panel = button.nextElementSibling;

            if (panel) {
                panel.style.display = panel.style.display === 'none' ? '' : panel.style.display;
            }

            button.addEventListener('click', function (event) {
                event.preventDefault();

                if (!panel) {
                    return;
                }

                panel.style.display = panel.style.display === 'none' ? '' : 'none';
            });
        }
    });
});
</script>
HTML;
}

function fitsMotorBuildButtonScript(array $buttonMap): string
{
    if ($buttonMap === []) {
        return '';
    }

    $payload = json_encode($buttonMap, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    if (!is_string($payload)) {
        return '';
    }

    return <<<HTML
<script>
document.addEventListener('DOMContentLoaded', function () {
    const routes = {$payload};
    const isIconToken = function (token) {
        return /^[A-Za-z0-9_]+$/.test(token) && (token.indexOf('_') !== -1 || /^[a-z0-9]+$/.test(token));
    };
    const normalize = function (text) {
        const parts = text.replace(/\\u2192/g, '').replace(/\\s+/g, ' ').trim().split(' ').filter(Boolean);

        while (parts.length > 1 && isIconToken(parts[0])) {
            parts.shift();
        }

        while (parts.length > 1 && isIconToken(parts[parts.length - 1])) {
            parts.pop();
        }

        return parts.join(' ');
    };

    document.querySelectorAll('button').forEach(function (button) {
        if (button.closest('form')) {
            return;
        }

        const label = normalize(button.textContent || '');
        const target = routes[label];

        if (!target) {
            return;
        }

        button.type = 'button';
        button.addEventListener('click', function () {
            window.location.href = target;
        });
    });
});
</script>
HTML;
}

function fitsMotorRenderPage(string $templatePath, array $options = []): void
{
    $html = file_get_contents($templatePath);

    if ($html === false) {
        http_response_code(500);
        echo 'Template tidak dapat dimuat.';
        return;
    }

    $routeMap = $options['route_map'] ?? fitsMotorPublicRoutes();
    $hideSelectors = $options['hide_selectors'] ?? [];
    $showSelectors = $options['show_selectors'] ?? [];
    $extraCss = $options['extra_css'] ?? '';
    $extraHead = $options['extra_head'] ?? '';
    $extraBodyEnd = $options['extra_body_end'] ?? '';
    $buttonMap = $options['button_map'] ?? [];
    $transformHtml = $options['transform_html'] ?? null;

    if (is_callable($transformHtml)) {
        $html = (string) $transformHtml($html);
    }

    $html = fitsMotorReplaceLinks($html, $routeMap);
    $html = fitsMotorReplaceKnownPlaceholderUrls($html);
    $html = fitsMotorInjectBeforeTag($html, '</head>', fitsMotorIconStylesheets());
    $html = fitsMotorInjectBeforeTag($html, '</head>', fitsMotorBuildCss($hideSelectors, $showSelectors, $extraCss));
    $html = fitsMotorInjectBeforeTag($html, '</head>', $extraHead);
    $html = fitsMotorInjectBeforeTag($html, '</body>', fitsMotorBuildUtilityScript());
    $html = fitsMotorInjectBeforeTag($html, '</body>', fitsMotorBuildButtonScript($buttonMap));
    $html = fitsMotorInjectBeforeTag($html, '</body>', $extraBodyEnd);

    echo $html;
}

