<?php
declare(strict_types=1);

require_once __DIR__ . '/shared/page_presets.php';

requireCustomerLogin();

$customerId = (int) currentUserId();

if (isPostRequest()) {
    $action = trim((string) requestInput('action', ''));
    $productId = (int) requestInput('product_id', 0);
    $quantity = max(1, (int) requestInput('quantity', 1));

    if ($productId <= 0) {
        redirectWithMessage('/cart.php', 'error', 'Produk keranjang tidak valid.');
    }

    if ($action === 'remove') {
        $removed = removeCartItem($db, $customerId, $productId);
        redirectWithMessage('/cart.php', $removed ? 'success' : 'error', $removed ? 'Item berhasil dihapus dari keranjang.' : 'Item gagal dihapus dari keranjang.');
    }

    if ($action === 'update') {
        $updated = updateCartItem($db, $customerId, $productId, $quantity);
        redirectWithMessage('/cart.php', $updated ? 'success' : 'error', $updated ? 'Jumlah produk berhasil diperbarui.' : 'Jumlah produk gagal diperbarui.');
    }
}

$flashError = getFlash('error');
$flashSuccess = getFlash('success');
$items = fetchCartItems($db, $customerId);
$summary = fetchCartSummary($db, $customerId);
$cartActionUrl = fitsMotorUrl('/cart.php');
$productsUrl = fitsMotorUrl('/products.php');
$checkoutUrl = fitsMotorUrl('/checkout.php');
$feedbackTone = $flashSuccess !== null ? 'success' : 'error';
$feedbackMessage = (string) ($flashSuccess ?? $flashError ?? '');
$feedbackHtml = $feedbackMessage !== ''
    ? '<div class="mb-6 border px-4 py-3 rounded-lg ' . ($feedbackTone === 'success' ? 'bg-[#38A169]/10 border-[#38A169]/40 text-white' : 'bg-danger-red/10 border-danger-red/40 text-white') . '">'
        . htmlspecialchars($feedbackMessage, ENT_QUOTES, 'UTF-8')
        . '</div>'
    : '';

$cartItemsHtml = '';

if ($items === []) {
    $cartItemsHtml = '<div class="bg-surface-card border border-white/5 p-8 rounded-lg">'
        . $feedbackHtml
        . '<p class="text-white font-title-lg mb-2">Keranjang Anda masih kosong.</p>'
        . '<p class="text-text-secondary mb-6">Tambahkan produk dari katalog untuk melanjutkan checkout.</p>'
        . '<a href="' . htmlspecialchars($productsUrl, ENT_QUOTES, 'UTF-8') . '" class="inline-flex items-center gap-2 bg-primary-container text-white px-6 py-3 bebas text-xl rounded-lg hover:brightness-110 transition-all">Belanja Produk</a>'
        . '</div>';
} else {
    foreach ($items as $item) {
        $unitPrice = (float) (($item['discount_price'] ?? null) !== null ? $item['discount_price'] : $item['price']);
        $quantity = (int) $item['quantity'];
        $minusQuantity = max(1, $quantity - 1);
        $plusQuantity = min(max(1, (int) ($item['stock'] ?? ($quantity + 1))), $quantity + 1);
        $image = trim((string) ($item['image'] ?? ''));

        $cartItemsHtml .= '<div class="bg-surface-card border border-white/5 p-6 rounded-lg flex items-center gap-6">'
            . '<div class="w-24 h-24 bg-surface-container rounded-lg overflow-hidden flex-shrink-0">'
            . ($image !== ''
                ? '<img class="w-full h-full object-cover" src="' . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars((string) $item['name'], ENT_QUOTES, 'UTF-8') . '"/>'
                : '<div class="w-full h-full flex items-center justify-center text-[10px] tracking-[0.24em] text-white/50">FITS MOTOR</div>')
            . '</div>'
            . '<div class="flex-grow">'
            . '<h3 class="font-title-lg text-title-lg text-white font-bold"><a href="' . htmlspecialchars(fitsMotorUrl('/product-detail.php?slug=' . urlencode((string) ($item['slug'] ?? ''))), ENT_QUOTES, 'UTF-8') . '" class="hover:text-primary-container transition-colors">' . htmlspecialchars((string) $item['name'], ENT_QUOTES, 'UTF-8') . '</a></h3>'
            . '<p class="text-text-secondary text-body-md">' . htmlspecialchars(trim((string) (($item['brand'] ?? '-') . ' - ' . ($item['compatibility'] ?? $item['category'] ?? '-'))), ENT_QUOTES, 'UTF-8') . '</p>'
            . '<div class="mt-4 flex items-center justify-between gap-4">'
            . '<div class="flex items-center bg-surface-container rounded overflow-hidden">'
            . '<form method="post" action="' . htmlspecialchars($cartActionUrl, ENT_QUOTES, 'UTF-8') . '"><input type="hidden" name="action" value="update"/><input type="hidden" name="product_id" value="' . (int) $item['product_id'] . '"/><input type="hidden" name="quantity" value="' . $minusQuantity . '"/><button type="submit" class="px-3 py-1 hover:bg-surface-accent text-primary text-xl">-</button></form>'
            . '<span class="px-4 font-data-mono text-data-mono text-white">' . $quantity . '</span>'
            . '<form method="post" action="' . htmlspecialchars($cartActionUrl, ENT_QUOTES, 'UTF-8') . '"><input type="hidden" name="action" value="update"/><input type="hidden" name="product_id" value="' . (int) $item['product_id'] . '"/><input type="hidden" name="quantity" value="' . $plusQuantity . '"/><button type="submit" class="px-3 py-1 hover:bg-surface-accent text-primary text-xl">+</button></form>'
            . '</div>'
            . '<span class="text-primary-container font-headline-md text-2xl bebas">' . htmlspecialchars(formatIdrText($unitPrice * $quantity), ENT_QUOTES, 'UTF-8') . '</span>'
            . '</div>'
            . '</div>'
            . '<form method="post" action="' . htmlspecialchars($cartActionUrl, ENT_QUOTES, 'UTF-8') . '">'
            . '<input type="hidden" name="action" value="remove"/>'
            . '<input type="hidden" name="product_id" value="' . (int) $item['product_id'] . '"/>'
            . '<button type="submit" class="text-on-surface-variant hover:text-danger-red transition-colors">'
            . '<span class="material-symbols-outlined">delete</span>'
            . '</button>'
            . '</form>'
            . '</div>';
    }

    $cartItemsHtml = $feedbackHtml . '<div class="space-y-4">' . $cartItemsHtml . '</div>';
}

$subtotalText = htmlspecialchars(formatIdrText((float) ($summary['subtotal'] ?? 0)), ENT_QUOTES, 'UTF-8');
$totalText = $subtotalText;
$checkoutButtonHtml = $items === []
    ? '<a href="' . htmlspecialchars($productsUrl, ENT_QUOTES, 'UTF-8') . '" class="w-full bg-primary-container text-white py-4 bebas text-2xl flex items-center justify-center gap-2 hover:brightness-110 active:scale-95 transition-all">BELANJA SEKARANG <span class="material-symbols-outlined">arrow_forward</span></a>'
    : '<a href="' . htmlspecialchars($checkoutUrl, ENT_QUOTES, 'UTF-8') . '" class="w-full bg-primary-container text-white py-4 bebas text-2xl flex items-center justify-center gap-2 hover:brightness-110 active:scale-95 transition-all">LANJUT KE CHECKOUT <span class="material-symbols-outlined">arrow_forward</span></a>';

$cartSectionHtml = '<section class="grid grid-cols-1 lg:grid-cols-12 gap-gutter mb-20">'
    . '<div class="lg:col-span-8">'
    . '<h1 class="font-headline-lg text-headline-lg mb-8 bebas uppercase">KERANJANG BELANJA</h1>'
    . $cartItemsHtml
    . '</div>'
    . '<div class="lg:col-span-4">'
    . '<div class="sticky top-24 bg-surface-card border-t-2 border-primary-container p-8 rounded-lg shadow-2xl">'
    . '<h2 class="font-headline-md text-headline-md text-white mb-6 bebas">Ringkasan Pesanan</h2>'
    . '<div class="space-y-4 mb-8">'
    . '<div class="flex justify-between text-body-md"><span class="text-text-secondary">Subtotal</span><span class="text-white font-data-mono">' . $subtotalText . '</span></div>'
    . '<div class="flex justify-between text-body-md"><span class="text-text-secondary">Pengiriman</span><span class="text-text-secondary">Belum Dihitung</span></div>'
    . '<div class="flex justify-between text-body-md"><span class="text-text-secondary">Diskon</span><span class="text-tertiary-container">- ' . htmlspecialchars(formatIdrText(0), ENT_QUOTES, 'UTF-8') . '</span></div>'
    . '<div class="pt-4 border-t border-white/5 flex justify-between items-end"><span class="text-white font-bold">TOTAL</span><span class="text-primary-container font-headline-lg text-3xl bebas">' . $totalText . '</span></div>'
    . '</div>'
    . '<div class="mb-6">'
    . '<label class="text-label-md font-label-md text-text-secondary mb-2 block uppercase">Kode Voucher</label>'
    . '<div class="flex gap-2">'
    . '<input class="flex-grow bg-surface-container border border-white/10 rounded px-4 py-2 text-white focus:border-primary-container outline-none transition-all" placeholder="MASUKKAN KODE" type="text"/>'
    . '<button type="button" data-cart-voucher class="bg-surface-accent text-white px-4 py-2 bebas text-lg hover:bg-opacity-80">APPLY</button>'
    . '</div>'
    . '</div>'
    . $checkoutButtonHtml
    . '</div>'
    . '</div>'
    . '</section>';

$options = fitsMotorCommercePageOptions('cart');
$options['extra_body_end'] .= <<<HTML
<script>
document.addEventListener('DOMContentLoaded', function () {
    const voucherButton = document.querySelector('[data-cart-voucher]');

    if (!voucherButton) {
        return;
    }

    voucherButton.addEventListener('click', function () {
        if (window.fitsMotorNotify) {
            window.fitsMotorNotify('Fitur voucher belum diaktifkan pada toko ini.');
            return;
        }

        window.alert('Fitur voucher belum diaktifkan pada toko ini.');
    });
});
</script>
HTML;
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static function (string $html) use ($cartSectionHtml): string {
        $updated = preg_replace('/<!-- FRAME 1: CART PAGE -->\s*<section\b[^>]*>.*?<\/section>/is', '<!-- FRAME 1: CART PAGE -->' . $cartSectionHtml, $html, 1);

        return is_string($updated) ? $updated : $html;
    }
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('keranjang_checkout_fits_motor/code.php'),
    $options
);

