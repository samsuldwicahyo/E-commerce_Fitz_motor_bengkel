<?php
declare(strict_types=1);

require_once __DIR__ . '/../shared/page_presets.php';

if (isAdminLoggedIn()) {
    redirectTo('/admin/dashboard.php');
}

if (isCustomerLoggedIn()) {
    redirectTo('/customer/dashboard.php');
}

$context = [
    'email' => oldInput('email'),
    'message' => null,
    'message_type' => 'error',
];

if (isPostRequest()) {
    $email = strtolower(trim((string) requestInput('email', '')));
    $password = (string) requestInput('password', '');

    $context['email'] = $email;
    setOldInput(['email' => $email]);

    if ($email === '' || $password === '') {
        $context['message'] = 'Email admin dan password wajib diisi.';
    } else {
        $role = loginUser($email, $password, $db);

        if ($role === 'admin') {
            clearOldInput();
            redirectTo('/admin/dashboard.php');
        }

        if ($role === 'customer') {
            logoutUser('/admin/login.php');
        }

        $context['message'] = 'Akun admin tidak ditemukan atau password salah.';
    }
}

fitsMotorRenderPage(
    fitsMotorTemplatePath('admin_panel_lengkap_fits_motor/code.php'),
    fitsMotorAdminPageOptions('login', $context)
);

