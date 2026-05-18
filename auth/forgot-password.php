<?php
declare(strict_types=1);

require_once __DIR__ . '/../shared/page_presets.php';

$context = [
    'email' => oldInput('email'),
    'message' => null,
    'message_type' => 'error',
];

if (isPostRequest()) {
    $email = strtolower(trim((string) requestInput('email', '')));
    $context['email'] = $email;
    setOldInput(['email' => $email]);

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $context['message'] = 'Masukkan email yang valid.';
    } else {
        $user = findUserByEmail($db, $email);
        clearOldInput();
        $context['email'] = '';
        $context['message_type'] = 'success';
        $context['message'] = $user !== null
            ? 'Link reset password simulasi berhasil dikirim. Untuk demo lokal, gunakan login langsung.'
            : 'Jika email terdaftar, link reset password akan dikirim.';
    }
}

fitsMotorRenderPage(
    fitsMotorTemplatePath('login_register_fits_motor/code.php'),
    fitsMotorAuthPageOptions('forgot-password', $context)
);

