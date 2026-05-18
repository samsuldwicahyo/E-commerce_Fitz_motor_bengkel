<?php
declare(strict_types=1);

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/repositories.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function isCustomerLoggedIn(): bool
{
    return isset($_SESSION['user_id'], $_SESSION['role']) && $_SESSION['role'] === 'customer';
}

function isAdminLoggedIn(): bool
{
    return isset($_SESSION['user_id'], $_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireCustomerLogin(): void
{
    if (!isCustomerLoggedIn()) {
        header('Location: ' . appUrl('/auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI'] ?? '/customer/dashboard.php')));
        exit;
    }
}

function requireAdminLogin(): void
{
    if (!isAdminLoggedIn()) {
        redirectTo('/admin/login.php');
    }
}

function loginUser(string $email, string $password, ?PDO $db): string|false
{
    if (!$db instanceof PDO) {
        return false;
    }

    $user = findUserByEmail($db, $email);

    if (!$user || !password_verify($password, (string) ($user['password'] ?? ''))) {
        return false;
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['role'] = $user['role'];

    return (string) $user['role'];
}

function logoutUser(string $redirectPath = '/auth/login.php'): never
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool) $params['secure'], (bool) $params['httponly']);
    }

    session_destroy();
    redirectTo($redirectPath);
}
