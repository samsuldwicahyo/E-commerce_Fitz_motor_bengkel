<?php
declare(strict_types=1);

require_once __DIR__ . '/../shared/page_presets.php';

$redirect = trim((string) queryInput('redirect', ''));

if (isCustomerLoggedIn()) {
    if ($redirect !== '' && str_starts_with($redirect, '/')) {
        header('Location: ' . $redirect);
        exit;
    }

    redirectTo('/customer/dashboard.php');
}

if (isAdminLoggedIn()) {
    redirectTo('/admin/dashboard.php');
}

$flashError = getFlash('error');
$flashSuccess = getFlash('success');

$context = [
    'email' => oldInput('email'),
    'message' => $flashError ?? $flashSuccess,
    'message_type' => $flashError !== null ? 'error' : 'success',
];

if (isPostRequest()) {
    $email = strtolower(trim((string) requestInput('email', '')));
    $password = (string) requestInput('password', '');

    $context['email'] = $email;

    if ($email === '' || $password === '') {
        $context['message'] = 'Email dan password wajib diisi.';
        $context['message_type'] = 'error';
    } else {
        $role = loginUser($email, $password, $db);

        if ($role === 'customer') {
            clearOldInput();
            $pendingCartAction = $_SESSION['pending_cart_action'] ?? null;
            unset($_SESSION['pending_cart_action']);

            if (is_array($pendingCartAction)) {
                $pendingProductId = (int) ($pendingCartAction['product_id'] ?? 0);
                $pendingProductName = trim((string) ($pendingCartAction['product_name'] ?? ''));
                $pendingQuantity = max(1, (int) ($pendingCartAction['quantity'] ?? 1));
                $pendingBuyNow = (bool) ($pendingCartAction['buy_now'] ?? false);
                $pendingProduct = $pendingProductId > 0 ? findProductById($db, $pendingProductId) : null;

                if ($pendingProduct === null && $pendingProductName !== '') {
                    $pendingProduct = resolveProductByTemplateName($db, $pendingProductName);
                }

                if ($pendingProduct !== null && (int) ($pendingProduct['stock'] ?? 0) > 0) {
                    addCartItem(
                        $db,
                        (int) currentUserId(),
                        (int) $pendingProduct['id'],
                        min($pendingQuantity, max(1, (int) ($pendingProduct['stock'] ?? 1)))
                    );

                    redirectTo($pendingBuyNow ? '/checkout.php' : '/cart.php');
                }
            }

            if ($redirect !== '' && str_starts_with($redirect, '/')) {
                header('Location: ' . $redirect);
                exit;
            }

            redirectTo('/customer/dashboard.php');
        }

        if ($role === 'admin') {
            clearOldInput();
            redirectTo('/admin/dashboard.php');
        }

        setOldInput(['email' => $email]);
        $context['message'] = 'Email atau password salah.';
        $context['message_type'] = 'error';
    }
}

fitsMotorRenderPage(
    fitsMotorTemplatePath('login_register_fits_motor/code.php'),
    fitsMotorAuthPageOptions('login', $context)
);

