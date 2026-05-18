<?php
declare(strict_types=1);

require_once __DIR__ . '/../../shared/page_presets.php';

requireAdminLogin();

$flashError = getFlash('error');
$flashSuccess = getFlash('success');
$orderCode = trim((string) queryInput('order_code', ''));
$order = $orderCode !== '' ? findOrderByCode($db, $orderCode) : (fetchAdminRecentOrders($db, 1)[0] ?? null);
$orderItems = $order !== null ? fetchOrderItems($db, (int) $order['id']) : [];

$itemsHtml = '';
foreach ($orderItems as $item) {
    $itemsHtml .= '<div class="flex items-center gap-4 py-4 border-b border-white/5">'
        . '<div class="w-16 h-16 bg-surface-container rounded-lg overflow-hidden border border-white/5">'
        . (trim((string) ($item['product_image'] ?? '')) !== ''
            ? '<img class="w-full h-full object-cover" src="' . htmlspecialchars((string) $item['product_image'], ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars((string) $item['product_name'], ENT_QUOTES, 'UTF-8') . '"/>'
            : '<div class="w-full h-full flex items-center justify-center text-[10px] tracking-[0.24em] text-white/40">FITS</div>')
        . '</div>'
        . '<div class="flex-1">'
        . '<p class="font-bold text-on-surface">' . htmlspecialchars((string) $item['product_name'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '<p class="text-[12px] text-on-surface-variant">Qty: ' . (int) $item['quantity'] . ' x ' . htmlspecialchars(formatRupiah((float) $item['price']), ENT_QUOTES, 'UTF-8') . '</p>'
        . '</div>'
        . '<p class="font-data-mono text-on-surface">' . htmlspecialchars(formatRupiah((float) $item['subtotal']), ENT_QUOTES, 'UTF-8') . '</p>'
        . '</div>';
}

if ($itemsHtml === '') {
    $itemsHtml = '<div class="text-on-surface-variant">Tidak ada item pesanan.</div>';
}

$feedbackHtml = '';
if ($flashError !== null || $flashSuccess !== null) {
    $feedbackHtml = '<div class="mb-6 border ' . ($flashSuccess !== null ? 'border-[#38A169]/40 bg-[#38A169]/10' : 'border-danger-red/40 bg-danger-red/10') . ' px-5 py-4 rounded-lg text-white">' . htmlspecialchars((string) ($flashSuccess ?? $flashError), ENT_QUOTES, 'UTF-8') . '</div>';
}

$detailHtml = '<section class="mt-16 pt-16 border-t border-white/5" id="frame-order-detail">'
    . $feedbackHtml
    . ($order !== null
        ? '<div class="flex justify-between items-end mb-8"><div><div class="flex items-center gap-3 mb-2"><h2 class="font-headline-lg text-headline-lg text-on-surface">Pesanan #' . htmlspecialchars((string) ($order['order_number'] ?? $order['order_code']), ENT_QUOTES, 'UTF-8') . '</h2><span class="bg-secondary-container/10 text-secondary-container border border-secondary-container/20 px-3 py-1 rounded text-[12px] font-bold uppercase">' . htmlspecialchars(orderStatusLabel((string) ($order['order_status'] ?: $order['status'])), ENT_QUOTES, 'UTF-8') . '</span></div><p class="text-on-surface-variant font-body-md">Dipesan pada ' . htmlspecialchars(date('d M Y, H:i', strtotime((string) $order['created_at'])), ENT_QUOTES, 'UTF-8') . ' WIB</p></div><div class="flex gap-4"><button type="button" onclick="window.print()" class="bg-surface-container-high px-4 py-2 rounded-lg font-label-md flex items-center gap-2 hover:text-primary transition-all"><span class="material-symbols-outlined text-[18px]">print</span> Cetak Invoice</button><a href="#admin-order-action" class="bg-primary-container text-white px-6 py-2 rounded-lg font-headline-md uppercase inline-flex items-center">Update Status</a></div></div>'
            . '<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter"><div class="lg:col-span-2 space-y-gutter"><div class="bg-surface-card border border-white/5 rounded-xl p-6"><h3 class="font-headline-md text-headline-md text-on-surface mb-6">Daftar Item</h3><div class="space-y-4">' . $itemsHtml . '</div><div class="mt-8 pt-8 border-t border-white/5 space-y-2"><div class="flex justify-between text-on-surface-variant"><span>Subtotal</span><span class="font-data-mono">' . htmlspecialchars(formatRupiah((float) ($order['subtotal'] ?? $order['total_amount'])), ENT_QUOTES, 'UTF-8') . '</span></div><div class="flex justify-between text-on-surface-variant"><span>Ongkos Kirim</span><span class="font-data-mono">' . htmlspecialchars(formatRupiah((float) ($order['shipping_cost'] ?? 0)), ENT_QUOTES, 'UTF-8') . '</span></div><div class="flex justify-between text-headline-md font-headline-md text-primary pt-2"><span>TOTAL</span><span>' . htmlspecialchars(formatRupiah((float) ($order['total'] ?? $order['total_amount'])), ENT_QUOTES, 'UTF-8') . '</span></div></div></div></div>'
            . '<div class="space-y-gutter"><div class="bg-surface-card border border-white/5 rounded-xl p-6"><h3 class="font-label-md text-label-md text-on-surface-variant uppercase mb-4 tracking-widest">Informasi Customer</h3><div class="space-y-4"><div><p class="font-bold text-on-surface">' . htmlspecialchars((string) $order['customer_name'], ENT_QUOTES, 'UTF-8') . '</p><p class="text-[14px] text-on-surface-variant">' . htmlspecialchars((string) ($order['customer_email'] ?? '-'), ENT_QUOTES, 'UTF-8') . '</p><p class="text-[14px] text-on-surface-variant">' . htmlspecialchars((string) ($order['customer_phone'] ?? '-'), ENT_QUOTES, 'UTF-8') . '</p></div><div class="pt-4 border-t border-white/5"><p class="text-[12px] text-on-surface-variant uppercase mb-1">Alamat Pengiriman</p><p class="text-[14px] text-on-surface">' . htmlspecialchars(trim((string) (($order['delivery_address'] ?? '') . ' ' . ($order['district'] ?? '') . ' ' . ($order['city_name'] ?? '') . ' ' . ($order['province_name'] ?? '') . ' ' . ($order['postal_code'] ?? ''))), ENT_QUOTES, 'UTF-8') . '</p></div></div></div>'
            . '<div id="admin-order-action" class="bg-surface-accent border border-primary-container/20 rounded-xl p-6"><h3 class="font-label-md text-label-md text-primary uppercase mb-4 tracking-widest">Action Required</h3><form method="post" action="' . htmlspecialchars(fitsMotorUrl('/admin/orders/update-status.php'), ENT_QUOTES, 'UTF-8') . '" class="space-y-4"><input type="hidden" name="order_code" value="' . htmlspecialchars((string) $order['order_code'], ENT_QUOTES, 'UTF-8') . '"/><div><label class="block text-[12px] text-on-surface-variant mb-2">Input Nomor Resi</label><input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none font-data-mono" name="tracking_number" placeholder="JNE123456789" type="text" value="' . htmlspecialchars((string) ($order['tracking_number'] ?? ''), ENT_QUOTES, 'UTF-8') . '"/></div><div><label class="block text-[12px] text-on-surface-variant mb-2">Status Pesanan</label><select class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none" name="status"><option value="pending"' . (($order['status'] ?? '') === 'pending' ? ' selected' : '') . '>pending</option><option value="diproses"' . (($order['status'] ?? '') === 'diproses' ? ' selected' : '') . '>diproses</option><option value="siap_diambil"' . (($order['status'] ?? '') === 'siap_diambil' ? ' selected' : '') . '>siap_diambil</option><option value="selesai"' . (($order['status'] ?? '') === 'selesai' ? ' selected' : '') . '>selesai</option><option value="dibatalkan"' . (($order['status'] ?? '') === 'dibatalkan' ? ' selected' : '') . '>dibatalkan</option></select></div><div><label class="block text-[12px] text-on-surface-variant mb-2">Status Pembayaran</label><select class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none" name="payment_status"><option value="belum_bayar"' . (($order['payment_status'] ?? '') === 'belum_bayar' ? ' selected' : '') . '>belum_bayar</option><option value="sudah_bayar"' . (($order['payment_status'] ?? '') === 'sudah_bayar' ? ' selected' : '') . '>sudah_bayar</option></select></div><div><label class="block text-[12px] text-on-surface-variant mb-2">Catatan Admin</label><input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface focus:border-primary-container outline-none" name="admin_notes" type="text" value="' . htmlspecialchars((string) ($order['admin_notes'] ?? ''), ENT_QUOTES, 'UTF-8') . '"/></div><button class="w-full bg-primary-container text-white py-3 rounded-lg font-bold uppercase transition-all hover:bg-inverse-primary" type="submit">Simpan &amp; Proses</button></form></div></div></div>'
        : '<p class="text-on-surface-variant">Belum ada pesanan yang dapat ditampilkan.</p>')
    . '</section>';

$options = fitsMotorAdminPageOptions('order-detail');
$options['hide_selectors'] = [
    'main #frame-dashboard',
    'main #frame-products',
    'main #frame-product-form',
    'main #frame-settings',
];
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static function (string $html) use ($detailHtml): string {
        $updated = preg_replace('/<section class="mt-16 pt-16 border-t border-white\/5" id="frame-order-detail">.*?<\/section>/is', $detailHtml, $html, 1);

        return is_string($updated) ? $updated : $html;
    }
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('admin_panel_lengkap_fits_motor/code.php'),
    $options
);
