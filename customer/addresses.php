<?php
declare(strict_types=1);

require_once __DIR__ . '/../shared/page_presets.php';

requireCustomerLogin();

$customerId = (int) currentUserId();
$customer = findUserById($db, $customerId);
$action = trim((string) queryInput('action', ''));
$addressId = (int) queryInput('id', requestInput('id', 0));
$message = null;
$messageTone = 'default';

if ($action === 'primary' && $addressId > 0) {
    if (setPrimaryAddress($db, $customerId, $addressId)) {
        redirectWithMessage('/customer/addresses.php', 'success', 'Alamat utama berhasil diperbarui.');
    }

    redirectWithMessage('/customer/addresses.php', 'error', 'Alamat utama gagal diperbarui.');
}

if ($action === 'delete' && $addressId > 0) {
    if (deleteAddressRecord($db, $customerId, $addressId)) {
        redirectWithMessage('/customer/addresses.php', 'success', 'Alamat berhasil dihapus.');
    }

    redirectWithMessage('/customer/addresses.php', 'error', 'Alamat gagal dihapus.');
}

$flashError = getFlash('error');
$flashSuccess = getFlash('success');
if ($flashError !== null || $flashSuccess !== null) {
    $message = (string) ($flashError ?? $flashSuccess);
    $messageTone = $flashError !== null ? 'error' : 'success';
}

$editAddress = $action === 'edit' && $addressId > 0 ? findAddressById($db, $addressId) : null;
$values = [
    'label' => (string) ($editAddress['label'] ?? ''),
    'recipient_name' => (string) ($editAddress['recipient_name'] ?? ($customer['name'] ?? ($_SESSION['user_name'] ?? ''))),
    'phone' => (string) ($editAddress['phone'] ?? ($customer['phone'] ?? '')),
    'province_id' => (string) ($editAddress['province_id'] ?? ''),
    'province_name' => (string) ($editAddress['province_name'] ?? ''),
    'city_id' => (string) ($editAddress['city_id'] ?? ''),
    'city_name' => (string) ($editAddress['city_name'] ?? ''),
    'district' => (string) ($editAddress['district'] ?? ''),
    'address' => (string) ($editAddress['address'] ?? ''),
    'postal_code' => (string) ($editAddress['postal_code'] ?? ''),
    'is_primary' => isset($editAddress['is_primary']) && (int) $editAddress['is_primary'] === 1 ? '1' : '0',
];

if (isPostRequest()) {
    $values = [
        'label' => trim((string) requestInput('label', '')),
        'recipient_name' => trim((string) requestInput('recipient_name', '')),
        'phone' => normalizePhoneNumber((string) requestInput('phone', '')),
        'province_id' => trim((string) requestInput('province_id', '')),
        'province_name' => trim((string) requestInput('province_name', '')),
        'city_id' => trim((string) requestInput('city_id', '')),
        'city_name' => trim((string) requestInput('city_name', '')),
        'district' => trim((string) requestInput('district', '')),
        'address' => trim((string) requestInput('address', '')),
        'postal_code' => trim((string) requestInput('postal_code', '')),
        'is_primary' => (string) requestInput('is_primary', '0'),
    ];

    if ($values['label'] === '' || $values['recipient_name'] === '' || $values['phone'] === '' || $values['province_name'] === '' || $values['city_name'] === '' || $values['address'] === '') {
        $message = 'Label, penerima, telepon, provinsi, kota, dan alamat lengkap wajib diisi.';
        $messageTone = 'error';
    } else {
        $payload = $values + ['is_primary' => $values['is_primary'] === '1'];
        $saved = $editAddress !== null
            ? updateAddressRecord($db, $customerId, (int) $editAddress['id'], $payload)
            : createAddressRecord($db, $customerId, $payload) !== null;

        if (!$saved) {
            $message = 'Alamat gagal disimpan.';
            $messageTone = 'error';
        } else {
            redirectWithMessage('/customer/addresses.php', 'success', $editAddress !== null ? 'Alamat berhasil diperbarui.' : 'Alamat berhasil ditambahkan.');
        }
    }
}

$addresses = fetchCustomerAddresses($db, $customerId);
$listHtml = '';

foreach ($addresses as $address) {
    $badge = (int) $address['is_primary'] === 1 ? '<span class="ml-2 px-2 py-1 text-xs bg-tertiary/20 text-tertiary rounded-full">UTAMA</span>' : '';
    $listHtml .= '<div class="border border-white/10 rounded-lg p-4 bg-surface-container">'
        . '<div class="flex items-center justify-between gap-3 mb-2"><strong class="text-white">' . htmlspecialchars((string) $address['label'], ENT_QUOTES, 'UTF-8') . '</strong>' . $badge . '</div>'
        . '<p>' . htmlspecialchars((string) $address['recipient_name'], ENT_QUOTES, 'UTF-8') . ' - ' . htmlspecialchars((string) $address['phone'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '<p>' . htmlspecialchars(trim((string) $address['address'] . ', ' . $address['district'] . ', ' . $address['city_name'] . ', ' . $address['province_name']), ENT_QUOTES, 'UTF-8') . '</p>'
        . '<div class="flex gap-3 mt-3">'
        . '<a class="text-primary" href="' . htmlspecialchars(fitsMotorUrl('/customer/addresses.php?action=edit&id=' . urlencode((string) $address['id'])), ENT_QUOTES, 'UTF-8') . '">Edit</a>'
        . ((int) $address['is_primary'] !== 1 ? '<a class="text-secondary" href="' . htmlspecialchars(fitsMotorUrl('/customer/addresses.php?action=primary&id=' . urlencode((string) $address['id'])), ENT_QUOTES, 'UTF-8') . '">Jadikan Utama</a>' : '')
        . '<a class="text-danger-red" href="' . htmlspecialchars(fitsMotorUrl('/customer/addresses.php?action=delete&id=' . urlencode((string) $address['id'])), ENT_QUOTES, 'UTF-8') . '" onclick="return confirm(\'Hapus alamat ini?\')">Hapus</a>'
        . '</div></div>';
}

$panelHtml = fitsMotorBuildContextPanel(
    $editAddress !== null ? 'Edit Alamat' : 'Kelola Alamat',
    ($message !== null ? '<p>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>' : '')
        . '<form method="post" action="' . htmlspecialchars(fitsMotorUrl('/customer/addresses.php' . ($editAddress !== null ? '?action=edit&id=' . urlencode((string) $editAddress['id']) : '')), ENT_QUOTES, 'UTF-8') . '" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">'
        . '<input class="bg-surface-container border border-white/10 p-3 text-white" name="label" value="' . htmlspecialchars($values['label'], ENT_QUOTES, 'UTF-8') . '" placeholder="Label Alamat (Rumah/Kantor)" required/>'
        . '<input class="bg-surface-container border border-white/10 p-3 text-white" name="recipient_name" value="' . htmlspecialchars($values['recipient_name'], ENT_QUOTES, 'UTF-8') . '" placeholder="Nama Penerima" required/>'
        . '<input class="bg-surface-container border border-white/10 p-3 text-white" name="phone" value="' . htmlspecialchars($values['phone'], ENT_QUOTES, 'UTF-8') . '" placeholder="Nomor HP / WA" required/>'
        . '<input class="bg-surface-container border border-white/10 p-3 text-white" name="postal_code" value="' . htmlspecialchars($values['postal_code'], ENT_QUOTES, 'UTF-8') . '" placeholder="Kode Pos"/>'
        . '<input class="bg-surface-container border border-white/10 p-3 text-white" name="province_id" value="' . htmlspecialchars($values['province_id'], ENT_QUOTES, 'UTF-8') . '" placeholder="Kode Provinsi"/>'
        . '<input class="bg-surface-container border border-white/10 p-3 text-white" name="province_name" value="' . htmlspecialchars($values['province_name'], ENT_QUOTES, 'UTF-8') . '" placeholder="Nama Provinsi" required/>'
        . '<input class="bg-surface-container border border-white/10 p-3 text-white" name="city_id" value="' . htmlspecialchars($values['city_id'], ENT_QUOTES, 'UTF-8') . '" placeholder="Kode Kota"/>'
        . '<input class="bg-surface-container border border-white/10 p-3 text-white" name="city_name" value="' . htmlspecialchars($values['city_name'], ENT_QUOTES, 'UTF-8') . '" placeholder="Nama Kota / Kabupaten" required/>'
        . '<input class="bg-surface-container border border-white/10 p-3 text-white md:col-span-2" name="district" value="' . htmlspecialchars($values['district'], ENT_QUOTES, 'UTF-8') . '" placeholder="Kecamatan"/>'
        . '<textarea class="bg-surface-container border border-white/10 p-3 text-white md:col-span-2" name="address" placeholder="Alamat Lengkap" required>' . htmlspecialchars($values['address'], ENT_QUOTES, 'UTF-8') . '</textarea>'
        . '<label class="flex items-center gap-2 text-white md:col-span-2"><input type="checkbox" name="is_primary" value="1"' . ($values['is_primary'] === '1' ? ' checked' : '') . '/> Jadikan alamat utama</label>'
        . '<div class="md:col-span-2 flex gap-3"><button class="bg-primary-container text-white font-bold uppercase px-5 py-3" type="submit">' . ($editAddress !== null ? 'Simpan Perubahan' : 'Tambah Alamat') . '</button>' . ($editAddress !== null ? '<a class="px-5 py-3 border border-white/10 text-white" href="' . htmlspecialchars(fitsMotorUrl('/customer/addresses.php'), ENT_QUOTES, 'UTF-8') . '">Batal</a>' : '') . '</div>'
        . '</form>'
        . '<div class="grid grid-cols-1 gap-4">' . ($listHtml !== '' ? $listHtml : '<p>Belum ada alamat tersimpan.</p>') . '</div>',
    $messageTone
);

$options = fitsMotorCustomerPageOptions('addresses');
$options = fitsMotorApplyAdditionalTransform(
    $options,
    static fn (string $html): string => fitsMotorInjectInsideMainStart($html, $panelHtml)
);

fitsMotorRenderPage(
    fitsMotorTemplatePath('dashboard_akun_customer_fits_motor/code.php'),
    $options
);

