<?php
declare(strict_types=1);

require_once __DIR__ . '/../shared/page_presets.php';

if (isCustomerLoggedIn()) {
    redirectTo('/customer/dashboard.php');
}

if (isAdminLoggedIn()) {
    redirectTo('/admin/dashboard.php');
}

$context = [
    'name' => oldInput('name'),
    'email' => oldInput('email'),
    'phone' => oldInput('phone'),
    'message' => null,
    'message_type' => 'error',
];

if (isPostRequest()) {
    $name = trim((string) requestInput('name', ''));
    $email = strtolower(trim((string) requestInput('email', '')));
    $phone = normalizePhoneNumber((string) requestInput('phone', ''));
    $password = (string) requestInput('password', '');
    $passwordConfirmation = (string) requestInput('password_confirmation', '');
    $terms = (string) requestInput('terms', '');

    $context['name'] = $name;
    $context['email'] = $email;
    $context['phone'] = $phone;

    setOldInput([
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
    ]);

    if ($name === '' || $email === '' || $phone === '' || $password === '' || $passwordConfirmation === '') {
        $context['message'] = 'Semua field wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $context['message'] = 'Format email tidak valid.';
    } elseif (findUserByEmail($db, $email) !== null) {
        $context['message'] = 'Email sudah terdaftar.';
    } elseif ($password !== $passwordConfirmation) {
        $context['message'] = 'Konfirmasi password tidak cocok.';
    } elseif (strlen($password) < 6) {
        $context['message'] = 'Password minimal 6 karakter.';
    } elseif ($terms !== '1') {
        $context['message'] = 'Anda harus menyetujui syarat dan ketentuan.';
    } else {
        $user = createUser($db, [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'customer',
        ]);

        if ($user === null || loginUser($email, $password, $db) !== 'customer') {
            $context['message'] = 'Registrasi gagal disimpan ke database.';
        } else {
            clearOldInput();
            redirectTo('/customer/dashboard.php');
        }
    }
}

fitsMotorRenderPage(
    fitsMotorTemplatePath('login_register_fits_motor/code.php'),
    fitsMotorAuthPageOptions('register', $context)
);

