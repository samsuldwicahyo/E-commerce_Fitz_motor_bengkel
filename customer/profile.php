<?php
declare(strict_types=1);

require_once __DIR__ . '/../shared/page_presets.php';

requireCustomerLogin();

$customerId = (int) currentUserId();
$customer = findUserById($db, $customerId);

if ($customer === null) {
    redirectWithMessage('/auth/login.php', 'error', 'Akun customer tidak ditemukan.');
}

$message = null;
$messageTone = 'default';
$values = [
    'name' => (string) $customer['name'],
    'email' => (string) $customer['email'],
    'phone' => (string) $customer['phone'],
];

if (isPostRequest()) {
    $values = [
        'name' => trim((string) requestInput('name', '')),
        'email' => (string) $customer['email'],
        'phone' => normalizePhoneNumber((string) requestInput('phone', '')),
    ];

    if ($values['name'] === '' || $values['phone'] === '') {
        $message = 'Nama dan nomor HP wajib diisi.';
        $messageTone = 'error';
    } else {
        $updated = updateCustomerProfile($db, $customerId, [
            'name' => $values['name'],
            'phone' => $values['phone'],
        ]);

        if (!$updated) {
            $message = 'Profil gagal diperbarui.';
            $messageTone = 'error';
        } else {
            $_SESSION['user_name'] = $values['name'];
            $_SESSION['user_email'] = $values['email'];
            $message = 'Profil berhasil diperbarui.';
            $messageTone = 'success';
        }
    }
}

$panelHtml = fitsMotorBuildContextPanel(
    'Edit Profil',
    ($message !== null ? '<p>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>' : '')
        . '<form method="post" action="' . htmlspecialchars(fitsMotorUrl('/customer/profile.php'), ENT_QUOTES, 'UTF-8') . '" class="grid grid-cols-1 md:grid-cols-2 gap-4">'
        . '<input class="bg-surface-container border border-white/10 p-3 text-white" name="name" value="' . htmlspecialchars($values['name'], ENT_QUOTES, 'UTF-8') . '" placeholder="Nama Lengkap" required/>'
        . '<input class="bg-surface-container border border-white/10 p-3 text-white opacity-70" value="' . htmlspecialchars($values['email'], ENT_QUOTES, 'UTF-8') . '" placeholder="Email" readonly/>'
        . '<input class="bg-surface-container border border-white/10 p-3 text-white md:col-span-2" name="phone" value="' . htmlspecialchars($values['phone'], ENT_QUOTES, 'UTF-8') . '" placeholder="Nomor HP / WhatsApp" required/>'
        . '<div class="md:col-span-2 flex gap-3"><button class="bg-primary-container text-white font-bold uppercase px-5 py-3" type="submit">Simpan</button><a class="px-5 py-3 border border-white/10 text-white" href="' . htmlspecialchars(fitsMotorUrl('/customer/dashboard.php'), ENT_QUOTES, 'UTF-8') . '">Kembali</a></div>'
        . '</form>',
    $messageTone
);

$options = fitsMotorCustomerPageOptions('profile');
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static fn (string $html): string => fitsMotorInjectInsideMainStart($html, $panelHtml)
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('dashboard_akun_customer_fits_motor/code.php'),
    $options
);

