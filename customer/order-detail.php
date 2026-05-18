<?php
declare(strict_types=1);

require_once __DIR__ . '/../shared/page_presets.php';

requireCustomerLogin();

$customerId = (int) currentUserId();
$orderCode = trim((string) queryInput('order_code', ''));
$order = $orderCode !== '' ? findOrderByCode($db, $orderCode) : fetchLatestCustomerOrder($db, $customerId);
$flashSuccess = getFlash('success');
$flashError = getFlash('error');

if ($order !== null && (int) ($order['customer_id'] ?? 0) !== $customerId) {
    $order = null;
}

$items = $order !== null ? fetchOrderItems($db, (int) $order['id']) : [];
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
$formatDateTime = static fn (?string $value): string => $value !== null && trim($value) !== '' ? date('d M Y H:i', strtotime($value)) : '-';
$addressSummary = $order !== null
    ? trim((string) (($order['delivery_address'] ?? '') . ' ' . ($order['district'] ?? '') . ' ' . ($order['city_name'] ?? '') . ' ' . ($order['province_name'] ?? '') . ' ' . ($order['postal_code'] ?? '')))
    : '';
$subtotal = (float) ($order['subtotal'] ?? 0);
$shippingCost = (float) ($order['shipping_cost'] ?? 0);
$voucherDiscount = (float) ($order['voucher_discount'] ?? 0);
$grandTotal = (float) ($order['total'] ?? $order['total_amount'] ?? 0);
$paymentIsPaid = $order !== null && (string) ($order['payment_status'] ?? '') === 'sudah_bayar';
$statusTone = $paymentIsPaid ? 'success' : 'warning';
$statusBoxClass = $paymentIsPaid
    ? 'bg-[#38A169]/10 border border-[#38A169]/30'
    : 'bg-[#ffb020]/10 border border-[#ffb020]/30';
$statusTextClass = $paymentIsPaid ? 'text-[#74db9d]' : 'text-secondary';
$noticeHtml = '';

if ($flashSuccess !== null || $flashError !== null) {
    $isSuccess = $flashSuccess !== null;
    $noticeHtml = '<div class="max-w-[1440px] mx-auto px-8 pt-6"><div class="border '
        . ($isSuccess ? 'border-[#38A169]/40 bg-[#38A169]/10' : 'border-danger-red/40 bg-danger-red/10')
        . ' text-white px-5 py-4 rounded-lg">'
        . $escape((string) ($flashSuccess ?? $flashError))
        . '</div></div>';
}

$itemsRowsHtml = '';

foreach ($items as $item) {
    $itemsRowsHtml .= '<tr>'
        . '<td class="px-4 py-4 border-b border-white/5 text-white">' . $escape((string) $item['product_name']) . '</td>'
        . '<td class="px-4 py-4 border-b border-white/5 text-center text-white">' . (int) $item['quantity'] . '</td>'
        . '<td class="px-4 py-4 border-b border-white/5 text-right text-white">' . $escape(formatRupiah((float) $item['price'])) . '</td>'
        . '<td class="px-4 py-4 border-b border-white/5 text-right text-white">' . $escape(formatRupiah((float) $item['subtotal'])) . '</td>'
        . '</tr>';
}

if ($itemsRowsHtml === '') {
    $itemsRowsHtml = '<tr><td class="px-4 py-5 border-b border-white/5 text-text-secondary text-center" colspan="4">Tidak ada item pesanan.</td></tr>';
}

$topActionHtml = $paymentIsPaid
    ? '<span class="inline-flex items-center gap-2 bg-[#38A169]/15 border border-[#38A169]/35 text-[#74db9d] px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-[0.18em]"><span class="material-symbols-outlined text-sm">verified</span> Pembayaran Terkonfirmasi</span>'
    : '<a class="inline-flex items-center justify-center border border-primary-container text-primary-container px-4 py-3 rounded-lg font-bold uppercase text-sm hover:bg-primary-container hover:text-white transition-all" href="' . $escape(fitsMotorUrl('/payment-instruction.php?order_code=' . urlencode((string) ($order['order_code'] ?? '')))) . '">Lihat Instruksi Pembayaran</a>';

$detailHtml = $order !== null
    ? $noticeHtml . '<section class="max-w-[1440px] mx-auto px-8 pt-8">'
        . '<div class="bg-surface-card border border-white/5 rounded-2xl shadow-2xl overflow-hidden">'
        . '<div class="px-8 py-7 border-b border-white/5 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">'
        . '<div>'
        . '<p class="text-primary-container uppercase tracking-[0.22em] text-xs font-bold mb-3">Invoice Pesanan</p>'
        . '<h2 class="font-headline-lg text-headline-lg text-white mb-2">Detail Pesanan</h2>'
        . '<p class="text-text-secondary">Invoice ini otomatis mengikuti status pembayaran terbaru dari sistem.</p>'
        . '</div>'
        . '<div class="flex flex-wrap gap-3">'
        . $topActionHtml
        . '<button type="button" onclick="window.print()" class="inline-flex items-center justify-center bg-surface-container-high text-white px-4 py-3 rounded-lg font-bold uppercase text-sm gap-2 hover:text-primary-container transition-all"><span class="material-symbols-outlined text-[18px]">print</span>Cetak Invoice</button>'
        . '</div>'
        . '</div>'
        . '<div class="p-8">'
        . '<div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_340px] gap-8">'
        . '<div>'
        . '<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">'
        . '<div class="bg-surface-container/70 border border-white/5 rounded-xl p-4"><p class="text-text-secondary text-xs uppercase tracking-[0.2em] mb-2">No. Pesanan</p><p class="text-white font-semibold">' . $escape((string) ($order['order_number'] ?? $order['order_code'])) . '</p></div>'
        . '<div class="bg-surface-container/70 border border-white/5 rounded-xl p-4"><p class="text-text-secondary text-xs uppercase tracking-[0.2em] mb-2">Tanggal</p><p class="text-white font-semibold">' . $escape($formatDateTime((string) ($order['created_at'] ?? ''))) . '</p></div>'
        . '<div class="bg-surface-container/70 border border-white/5 rounded-xl p-4"><p class="text-text-secondary text-xs uppercase tracking-[0.2em] mb-2">Status</p><p class="text-white font-semibold">' . $escape(orderStatusLabel((string) ($order['order_status'] ?: $order['status']))) . '</p></div>'
        . '<div class="bg-surface-container/70 border border-white/5 rounded-xl p-4"><p class="text-text-secondary text-xs uppercase tracking-[0.2em] mb-2">Pembayaran</p><p class="' . ($paymentIsPaid ? 'text-[#74db9d]' : 'text-secondary') . ' font-semibold">' . $escape(paymentStatusLabel((string) ($order['payment_status'] ?? ''))) . '</p></div>'
        . '<div class="bg-surface-container/70 border border-white/5 rounded-xl p-4"><p class="text-text-secondary text-xs uppercase tracking-[0.2em] mb-2">Kurir</p><p class="text-white font-semibold">' . $escape(trim((string) (($order['courier'] ?? '-') . ' ' . ($order['courier_service'] ?? '')))) . '</p></div>'
        . '<div class="bg-surface-container/70 border border-white/5 rounded-xl p-4"><p class="text-text-secondary text-xs uppercase tracking-[0.2em] mb-2">Resi</p><p class="text-white font-semibold">' . $escape((string) (($order['tracking_number'] ?? '') !== '' ? $order['tracking_number'] : '-')) . '</p></div>'
        . '</div>'
        . '<div class="bg-surface-container/70 border border-white/5 rounded-xl p-5 mb-8">'
        . '<p class="text-text-secondary text-xs uppercase tracking-[0.2em] mb-3">Alamat Kirim</p>'
        . '<p class="text-white leading-relaxed">' . $escape($addressSummary !== '' ? $addressSummary : '-') . '</p>'
        . '</div>'
        . '<div class="bg-surface-container/70 border border-white/5 rounded-xl overflow-hidden mb-8">'
        . '<div class="px-5 py-4 border-b border-white/5 flex items-center justify-between gap-4"><h3 class="text-white font-semibold">Item Pesanan</h3><span class="text-text-secondary text-sm">' . count($items) . ' item</span></div>'
        . '<div class="overflow-x-auto"><table class="w-full text-left">'
        . '<thead><tr class="text-text-secondary text-sm uppercase tracking-[0.16em]"><th class="px-4 py-3">Produk</th><th class="px-4 py-3 text-center">Qty</th><th class="px-4 py-3 text-right">Harga</th><th class="px-4 py-3 text-right">Subtotal</th></tr></thead>'
        . '<tbody>' . $itemsRowsHtml . '</tbody>'
        . '</table></div>'
        . '</div>'
        . '</div>'
        . '<aside class="space-y-5">'
        . '<div class="bg-surface-container/70 border border-white/5 rounded-xl p-5">'
        . '<p class="text-text-secondary text-xs uppercase tracking-[0.2em] mb-3">Ringkasan Invoice</p>'
        . '<div class="space-y-3 text-sm">'
        . '<div class="flex items-center justify-between"><span class="text-text-secondary">Subtotal</span><strong class="text-white">' . $escape(formatRupiah($subtotal)) . '</strong></div>'
        . '<div class="flex items-center justify-between"><span class="text-text-secondary">Ongkir</span><strong class="text-white">' . $escape(formatRupiah($shippingCost)) . '</strong></div>'
        . '<div class="flex items-center justify-between"><span class="text-text-secondary">Diskon</span><strong class="text-white">' . $escape(formatRupiah($voucherDiscount)) . '</strong></div>'
        . '<div class="flex items-center justify-between pt-3 border-t border-white/5"><span class="text-white font-bold">Total</span><strong class="text-primary-container text-2xl bebas">' . $escape(formatRupiah($grandTotal)) . '</strong></div>'
        . '</div>'
        . '</div>'
        . '<div class="bg-surface-container/70 border border-white/5 rounded-xl p-5">'
        . '<p class="text-text-secondary text-xs uppercase tracking-[0.2em] mb-3">Informasi Pembayaran</p>'
        . '<div class="space-y-3 text-sm">'
        . '<div><span class="text-text-secondary block mb-1">Metode</span><strong class="text-white">' . $escape(strtoupper((string) ($order['payment_method'] ?? 'transfer'))) . '</strong></div>'
        . '<div><span class="text-text-secondary block mb-1">Referensi</span><strong class="text-white break-all">' . $escape((string) (($order['tripay_reference'] ?? '') !== '' ? $order['tripay_reference'] : '-')) . '</strong></div>'
        . '<div><span class="text-text-secondary block mb-1">Dibayar Pada</span><strong class="text-white">' . $escape($formatDateTime((string) ($order['paid_at'] ?? ''))) . '</strong></div>'
        . '</div>'
        . '</div>'
        . '<div class="' . $statusBoxClass . ' rounded-xl p-5">'
        . '<p class="' . $statusTextClass . ' font-semibold mb-2">' . ($paymentIsPaid ? 'Pembayaran sudah diterima.' : 'Menunggu pembayaran.') . '</p>'
        . '<p class="text-white/75 text-sm leading-relaxed">' . ($paymentIsPaid ? 'Tidak ada tombol lanjut bayar lagi di invoice ini. Status pesanan akan terus mengikuti update terbaru dari sistem dan admin.' : 'Gunakan instruksi pembayaran untuk menyelesaikan transaksi, lalu halaman detail pesanan akan ikut berubah setelah pembayaran dikonfirmasi.') . '</p>'
        . '</div>'
        . '<div class="flex flex-col gap-3">'
        . '<a class="inline-flex items-center justify-center border border-white/10 text-white px-4 py-3 rounded-lg font-bold uppercase text-sm" href="' . $escape(fitsMotorUrl('/customer/orders.php')) . '">Kembali ke Riwayat</a>'
        . '</div>'
        . '</aside>'
        . '</div>'
        . '</div>'
        . '</div>'
        . '</section>'
    : fitsMotorBuildContextPanel(
        'Detail Pesanan',
        '<p>Belum ada detail pesanan yang bisa ditampilkan untuk akun ini.</p>',
        'warning'
    );

$printCss = '<style>@media print{header,footer,nav,.no-print,[data-fits-cart-trigger]{display:none !important;}body{background:#fff !important;color:#000 !important;}main,.bg-surface-card,.bg-surface-container\\/70{background:#fff !important;color:#000 !important;box-shadow:none !important;border-color:#d1d5db !important;} .text-white,.text-primary-container,.text-secondary,.text-\\[\\#74db9d\\],.text-text-secondary{color:#000 !important;}}</style>';

$options = fitsMotorCustomerPageOptions('order-detail');
$options['extra_head'] = ($options['extra_head'] ?? '') . $printCss;
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static fn (string $html): string => fitsMotorInjectInsideMainStart($html, $detailHtml)
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('dashboard_akun_customer_fits_motor/code.php'),
    $options
);
