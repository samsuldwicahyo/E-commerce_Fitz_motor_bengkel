<?php
declare(strict_types=1);

require_once __DIR__ . '/../../shared/page_presets.php';

requireAdminLogin();

$settings = fetchAllSettings($db);
$message = null;
$messageTone = 'default';
$keys = [
    'shop_name' => 'Nama Toko',
    'shop_phone' => 'Nomor Telepon / WA',
    'shop_address' => 'Alamat Toko',
    'shop_email' => 'Email Toko',
    'shop_hours' => 'Jam Operasional',
    'rajaongkir_origin_city_id' => 'Kota Asal RajaOngkir',
    'wa_default_message' => 'Pesan Default WhatsApp',
    'tripay_mode' => 'Tripay Base URL',
    'instagram_url' => 'Instagram',
    'tiktok_url' => 'TikTok',
    'facebook_url' => 'Facebook',
];

if (isPostRequest()) {
    $payload = [];
    foreach (array_keys($keys) as $keyName) {
        $payload[$keyName] = trim((string) requestInput($keyName, ''));
    }

    if (saveSettings($db, $payload)) {
        $settings = fetchAllSettings($db);
        $message = 'Setting toko berhasil diperbarui.';
        $messageTone = 'success';
    } else {
        $message = 'Setting toko gagal diperbarui.';
        $messageTone = 'error';
    }
}

$feedbackHtml = '';
if ($message !== null) {
    $feedbackHtml = '<div class="mb-6 border ' . ($messageTone === 'success' ? 'border-[#38A169]/40 bg-[#38A169]/10' : 'border-danger-red/40 bg-danger-red/10') . ' px-5 py-4 rounded-lg text-white">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</div>';
}

$formSectionHtml = '<section class="mt-16 pt-16 border-t border-white/5 pb-32" id="frame-settings">'
    . $feedbackHtml
    . '<h2 class="font-headline-lg text-headline-lg text-on-surface mb-8">Setting Toko</h2>'
    . '<div class="grid grid-cols-1 md:grid-cols-4 gap-gutter"><div class="space-y-2"><a class="block w-full text-left px-4 py-3 rounded-lg bg-primary-container/10 text-primary font-label-md border-r-2 border-primary" href="#settings-section-info">Informasi Toko</a><a class="block w-full text-left px-4 py-3 rounded-lg text-on-surface-variant hover:bg-white/5 font-label-md" href="#settings-section-wa">WhatsApp Integrasi</a><a class="block w-full text-left px-4 py-3 rounded-lg text-on-surface-variant hover:bg-white/5 font-label-md" href="#settings-section-shipping">Metode Pengiriman</a><a class="block w-full text-left px-4 py-3 rounded-lg text-on-surface-variant hover:bg-white/5 font-label-md" href="#settings-section-payment">Payment Gateway</a><a class="block w-full text-left px-4 py-3 rounded-lg text-on-surface-variant hover:bg-white/5 font-label-md" href="#settings-section-social">Sosial Media</a></div>'
    . '<div class="md:col-span-3"><form method="post" action="' . htmlspecialchars(fitsMotorUrl('/admin/settings/index.php'), ENT_QUOTES, 'UTF-8') . '" class="bg-surface-card border border-white/5 rounded-xl p-8 space-y-8"><div id="settings-section-info" class="grid grid-cols-1 md:grid-cols-2 gap-6"><div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Nama Toko</label><input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface" name="shop_name" type="text" value="' . htmlspecialchars((string) ($settings['shop_name'] ?? ''), ENT_QUOTES, 'UTF-8') . '"/></div><div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Nomor Telepon / WA</label><input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface" name="shop_phone" type="text" value="' . htmlspecialchars((string) ($settings['shop_phone'] ?? ''), ENT_QUOTES, 'UTF-8') . '"/></div><div class="md:col-span-2"><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Alamat Fisik Workshop</label><textarea class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface" name="shop_address" rows="3">' . htmlspecialchars((string) ($settings['shop_address'] ?? ''), ENT_QUOTES, 'UTF-8') . '</textarea></div><div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Email Bisnis</label><input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface" name="shop_email" type="email" value="' . htmlspecialchars((string) ($settings['shop_email'] ?? ''), ENT_QUOTES, 'UTF-8') . '"/></div><div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Jam Operasional</label><input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface" name="shop_hours" type="text" value="' . htmlspecialchars((string) ($settings['shop_hours'] ?? ''), ENT_QUOTES, 'UTF-8') . '"/></div></div><div id="settings-section-wa" class="pt-6 border-t border-white/5"><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Pesan Default WhatsApp</label><textarea class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface" name="wa_default_message" rows="3">' . htmlspecialchars((string) ($settings['wa_default_message'] ?? ''), ENT_QUOTES, 'UTF-8') . '</textarea></div><div id="settings-section-shipping" class="pt-6 border-t border-white/5 grid grid-cols-1 md:grid-cols-2 gap-6"><div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Kota Asal RajaOngkir</label><input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface font-data-mono" name="rajaongkir_origin_city_id" type="text" value="' . htmlspecialchars((string) ($settings['rajaongkir_origin_city_id'] ?? ''), ENT_QUOTES, 'UTF-8') . '" placeholder="Contoh: 152"/></div><div class="rounded-xl border border-white/5 bg-surface-container-low p-4 text-on-surface-variant text-[13px] leading-6">Atur kota asal pengiriman untuk perhitungan ongkir otomatis dan sinkronisasi dashboard pengiriman.</div></div><div id="settings-section-payment" class="pt-6 border-t border-white/5 grid grid-cols-1 md:grid-cols-2 gap-6"><div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Tripay Base URL</label><input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface font-data-mono" name="tripay_mode" type="text" value="' . htmlspecialchars((string) ($settings['tripay_mode'] ?? ''), ENT_QUOTES, 'UTF-8') . '" placeholder="https://tripay.co.id/api"/></div><div class="rounded-xl border border-white/5 bg-surface-container-low p-4 text-on-surface-variant text-[13px] leading-6">Gunakan endpoint sandbox atau production sesuai mode integrasi pembayaran yang sedang aktif.</div></div><div id="settings-section-social" class="pt-6 border-t border-white/5 grid grid-cols-1 md:grid-cols-3 gap-6"><div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Instagram</label><input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface" name="instagram_url" type="text" value="' . htmlspecialchars((string) ($settings['instagram_url'] ?? ''), ENT_QUOTES, 'UTF-8') . '" placeholder="https://instagram.com/fitsmotor"/></div><div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">TikTok</label><input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface" name="tiktok_url" type="text" value="' . htmlspecialchars((string) ($settings['tiktok_url'] ?? ''), ENT_QUOTES, 'UTF-8') . '" placeholder="https://tiktok.com/@fitsmotor"/></div><div><label class="block font-label-md text-label-md text-on-surface-variant mb-2">Facebook</label><input class="w-full bg-surface-container-lowest border border-white/10 rounded-lg p-3 text-on-surface" name="facebook_url" type="text" value="' . htmlspecialchars((string) ($settings['facebook_url'] ?? ''), ENT_QUOTES, 'UTF-8') . '" placeholder="https://facebook.com/fitsmotor"/></div></div><div class="pt-6 border-t border-white/5 flex justify-end"><button class="bg-primary-container text-white px-8 py-3 rounded-lg font-headline-md uppercase tracking-wider" type="submit">Simpan Perubahan</button></div></form></div></div>'
    . '</section>';

$options = fitsMotorAdminPageOptions('settings');
$options['hide_selectors'] = [
    'main #frame-dashboard',
    'main #frame-products',
    'main #frame-product-form',
    'main #frame-order-detail',
];
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static fn (string $html): string => fitsMotorReplaceSectionById($html, 'frame-settings', $formSectionHtml)
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('admin_panel_lengkap_fits_motor/code.php'),
    $options
);
