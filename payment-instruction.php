<?php
declare(strict_types=1);

require_once __DIR__ . '/shared/page_presets.php';

requireCustomerLogin();

$customerId = (int) currentUserId();
$orderCode = trim((string) queryInput('order_code', ($_SESSION['last_order_code'] ?? '')));
$order = $orderCode !== '' ? findOrderByCode($db, $orderCode) : fetchLatestCustomerOrder($db, $customerId);

if ($order !== null && (int) ($order['customer_id'] ?? 0) !== $customerId) {
    $order = null;
}

if ($order !== null && (string) ($order['payment_status'] ?? '') === 'sudah_bayar') {
    setFlash('success', 'Pembayaran sudah diterima. Invoice pesanan telah diperbarui.');
    redirectTo('/customer/order-detail.php?order_code=' . urlencode((string) ($order['order_code'] ?? '')));
}

$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
$paymentSectionHtml = '<section class="max-w-2xl mx-auto mb-20"><div class="bg-surface-card border border-primary-container rounded-lg p-10 text-center shadow-2xl relative overflow-hidden"><div class="absolute -top-20 -right-20 w-64 h-64 bg-primary/10 rounded-full blur-3xl"></div><div class="relative z-10"><div class="inline-flex items-center gap-2 bg-error-container/20 text-error px-4 py-2 rounded-full mb-8"><span class="material-symbols-outlined text-sm">schedule</span><span class="text-xs font-bold font-data-mono">SILAKAN SELESAIKAN PEMBAYARAN</span></div><h2 class="font-headline-md text-headline-md text-white mb-2 bebas">PEMBAYARAN VIRTUAL ACCOUNT</h2><p class="text-text-secondary mb-8">Data pembayaran belum tersedia.</p><div class="flex flex-col md:flex-row gap-4"><a href="' . $escape(fitsMotorUrl('/order-tracking.php')) . '" class="flex-grow bg-primary-container text-white py-4 bebas text-xl hover:brightness-110 transition-all">CEK STATUS PESANAN</a><a href="' . $escape(fitsMotorUrl('/contact.php')) . '" class="flex-grow border-2 border-white/10 text-white py-4 bebas text-xl hover:bg-white/5 transition-all">HUBUNGI KAMI</a></div></div></div></section>';
$pollScript = '';

if ($order !== null) {
    $orderNumber = (string) ($order['order_number'] ?? $order['order_code']);
    $paymentMethodCode = strtoupper((string) ($order['payment_method'] ?? 'transfer'));
    $paymentMethodLabel = $paymentMethodCode === 'COD' ? 'PEMBAYARAN COD' : 'INSTRUKSI PEMBAYARAN';
    $referenceLabel = trim((string) ($order['tripay_reference'] ?? ''));
    $referenceLabel = $referenceLabel !== '' ? $referenceLabel : ((string) ($order['tracking_number'] ?? '') !== '' ? (string) $order['tracking_number'] : 'Menunggu referensi');
    $paymentStatus = paymentStatusLabel((string) ($order['payment_status'] ?? 'pending'));
    $courierLabel = trim((string) (($order['courier'] ?? '-') . ' ' . ($order['courier_service'] ?? '')));
    $addressLabel = trim((string) (($order['delivery_address'] ?? '') . ' ' . ($order['district'] ?? '') . ' ' . ($order['city_name'] ?? '') . ' ' . ($order['province_name'] ?? '') . ' ' . ($order['postal_code'] ?? '')));
    $instructionLines = [];

    if ($paymentMethodCode === 'COD') {
        $instructionLines[] = 'Pesanan menggunakan pembayaran COD. Pembayaran dilakukan saat pesanan diterima.';
    } elseif (strtoupper((string) ($order['tripay_reference'] ?? '')) !== '' && str_contains(strtoupper((string) ($order['tripay_reference'] ?? '')), 'DEV-')) {
        $instructionLines[] = 'Transaksi sudah dibuat di Tripay. Gunakan referensi pembayaran di atas untuk memantau status transaksi.';
        $instructionLines[] = 'Jika Anda membayar lewat QRIS, scan kode QR di halaman Tripay yang terbuka setelah checkout.';
        $instructionLines[] = 'Setelah callback diterima, halaman ini akan otomatis pindah ke invoice detail pesanan.';
    } else {
        $instructionLines[] = 'Gunakan nomor referensi pembayaran di atas untuk menyelesaikan pembayaran sesuai metode yang dipilih.';
        $instructionLines[] = 'Setelah pembayaran berhasil diverifikasi, status invoice akan otomatis diperbarui.';
    }

    $instructionItemsHtml = '';

    foreach ($instructionLines as $line) {
        $instructionItemsHtml .= '<li>' . $escape($line) . '</li>';
    }

    $paymentSectionHtml = '<section class="max-w-2xl mx-auto mb-20" data-payment-instruction-page="1">'
        . '<div class="bg-surface-card border border-primary-container rounded-lg p-10 text-center shadow-2xl relative overflow-hidden">'
        . '<div class="absolute -top-20 -right-20 w-64 h-64 bg-primary/10 rounded-full blur-3xl"></div>'
        . '<div class="relative z-10">'
        . '<div id="payment-status-chip" class="inline-flex items-center gap-2 bg-error-container/20 text-error px-4 py-2 rounded-full mb-8"><span class="material-symbols-outlined text-sm">schedule</span><span id="payment-status-chip-text" class="text-xs font-bold font-data-mono">MENUNGGU PEMBAYARAN</span></div>'
        . '<h2 class="font-headline-md text-headline-md text-white mb-2 bebas">' . $escape($paymentMethodLabel) . '</h2>'
        . '<p class="text-text-secondary mb-8">Status: <span id="payment-status-label">' . $escape($paymentStatus) . '</span> | Pesanan ' . $escape($orderNumber) . '</p>'
        . '<div class="bg-surface-container-low p-8 rounded-lg border border-white/5 mb-8">'
        . '<div class="mb-6">'
        . '<p class="text-xs text-text-secondary uppercase mb-2 tracking-widest">Nomor Referensi Pembayaran</p>'
        . '<div class="flex items-center justify-center gap-4">'
        . '<span class="font-headline-md text-3xl md:text-4xl text-primary tracking-widest bebas break-all" id="payment-reference">' . $escape($referenceLabel) . '</span>'
        . '<button type="button" class="p-2 text-primary-container hover:bg-white/5 rounded-full transition-colors"><span class="material-symbols-outlined">content_copy</span></button>'
        . '</div>'
        . '</div>'
        . '<div class="pt-6 border-t border-white/5">'
        . '<p class="text-xs text-text-secondary uppercase mb-2 tracking-widest">Total Pembayaran</p>'
        . '<p class="text-primary-container font-headline-lg text-4xl bebas">' . $escape(formatIdrText((float) ($order['total'] ?? $order['total_amount'] ?? 0))) . '</p>'
        . '</div>'
        . '</div>'
        . '<div class="text-left space-y-4 mb-10">'
        . '<h4 class="font-bold text-white text-sm uppercase">Informasi Pesanan</h4>'
        . '<ol class="text-sm text-text-secondary space-y-2 list-decimal ml-4">'
        . '<li>Metode pembayaran: <span class="text-white">' . $escape((string) ($order['payment_method'] ?? 'transfer')) . '</span></li>'
        . '<li>Status pesanan: <span id="order-status-label" class="text-white">' . $escape(orderStatusLabel((string) ($order['order_status'] ?? $order['status'] ?? 'pending'))) . '</span></li>'
        . '<li>Kurir pengiriman: <span class="text-white">' . $escape($courierLabel !== '' ? $courierLabel : '-') . '</span></li>'
        . '<li>Alamat kirim: <span class="text-white">' . $escape($addressLabel !== '' ? $addressLabel : '-') . '</span></li>'
        . '</ol>'
        . '</div>'
        . '<div class="text-left bg-surface-container/70 border border-white/5 rounded-lg p-5 mb-8">'
        . '<h4 class="font-bold text-white text-sm uppercase mb-4">Petunjuk</h4>'
        . '<ol class="text-sm text-text-secondary space-y-2 list-decimal ml-4">' . $instructionItemsHtml . '</ol>'
        . '</div>'
        . '<div class="flex flex-col md:flex-row gap-4">'
        . '<a href="' . $escape(fitsMotorUrl('/customer/order-detail.php?order_code=' . urlencode((string) ($order['order_code'] ?? '')))) . '" class="flex-grow bg-primary-container text-white py-4 bebas text-xl hover:brightness-110 transition-all">LIHAT DETAIL PESANAN</a>'
        . '<a href="' . $escape(fitsMotorUrl('/contact.php')) . '" class="flex-grow border-2 border-white/10 text-white py-4 bebas text-xl hover:bg-white/5 transition-all">HUBUNGI KAMI</a>'
        . '</div>'
        . '</div>'
        . '</div>'
        . '</section>';

    $statusApiUrl = fitsMotorUrl('/api/order-status.php?order_code=' . urlencode((string) ($order['order_code'] ?? '')));
    $detailUrl = fitsMotorUrl('/customer/order-detail.php?order_code=' . urlencode((string) ($order['order_code'] ?? '')));
    $pollScript = <<<HTML
<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusApiUrl = '{$statusApiUrl}';
    const detailUrl = '{$detailUrl}';
    const paymentStatusLabel = document.getElementById('payment-status-label');
    const orderStatusLabel = document.getElementById('order-status-label');
    const chip = document.getElementById('payment-status-chip');
    const chipText = document.getElementById('payment-status-chip-text');
    let redirected = false;

    const applyPaidState = function (result) {
        if (paymentStatusLabel) {
            paymentStatusLabel.textContent = result.payment_status_label || 'Sudah Dibayar';
        }

        if (orderStatusLabel) {
            orderStatusLabel.textContent = result.order_status_label || 'Diproses';
        }

        if (chip) {
            chip.className = 'inline-flex items-center gap-2 bg-[#38A169]/15 text-[#74db9d] px-4 py-2 rounded-full mb-8';
        }

        if (chipText) {
            chipText.textContent = 'PEMBAYARAN SUDAH DITERIMA';
        }
    };

    const checkStatus = async function () {
        if (redirected) {
            return;
        }

        try {
            const response = await fetch(statusApiUrl, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                return;
            }

            const result = await response.json();

            if (!result.success) {
                return;
            }

            if ((result.payment_status || '') === 'sudah_bayar') {
                applyPaidState(result);
                redirected = true;

                if (window.fitsMotorNotify) {
                    window.fitsMotorNotify('Pembayaran sudah diterima. Mengalihkan ke detail pesanan.', 'success');
                }

                window.setTimeout(function () {
                    window.location.href = result.redirect_url || detailUrl;
                }, 900);
                return;
            }

            if (paymentStatusLabel && result.payment_status_label) {
                paymentStatusLabel.textContent = result.payment_status_label;
            }

            if (orderStatusLabel && result.order_status_label) {
                orderStatusLabel.textContent = result.order_status_label;
            }
        } catch (error) {
        }
    };

    window.setInterval(checkStatus, 8000);
    checkStatus();
});
</script>
HTML;
}

$options = fitsMotorCommercePageOptions('payment-instruction');
$options['extra_body_end'] .= $pollScript;
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static function (string $html) use ($paymentSectionHtml): string {
        $updated = preg_replace('/<!-- FRAME 7: PAYMENT INSTRUCTION -->\s*<section\b[^>]*>.*?<\/section>/is', '<!-- FRAME 7: PAYMENT INSTRUCTION -->' . $paymentSectionHtml, $html, 1);

        return is_string($updated) ? $updated : $html;
    }
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('keranjang_checkout_fits_motor/code.php'),
    $options
);
