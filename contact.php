<?php
declare(strict_types=1);

require_once __DIR__ . '/shared/page_presets.php';

$context = [
    'name' => oldInput('name'),
    'email' => oldInput('email'),
    'phone' => oldInput('phone'),
    'topic' => oldInput('topic', 'Layanan Servis'),
    'body' => oldInput('message'),
    'message' => null,
    'message_type' => 'error',
];

if (isPostRequest()) {
    $payload = [
        'name' => trim((string) requestInput('name', '')),
        'email' => trim((string) requestInput('email', '')),
        'phone' => trim((string) requestInput('phone', '')),
        'topic' => trim((string) requestInput('topic', '')),
        'message' => trim((string) requestInput('message', '')),
    ];

    $context = array_merge($context, [
        'name' => $payload['name'],
        'email' => $payload['email'],
        'phone' => $payload['phone'],
        'topic' => $payload['topic'] !== '' ? $payload['topic'] : 'Layanan Servis',
        'body' => $payload['message'],
    ]);

    setOldInput([
        'name' => $payload['name'],
        'email' => $payload['email'],
        'phone' => $payload['phone'],
        'topic' => $context['topic'],
        'message' => $payload['message'],
    ]);

    if ($payload['name'] === '' || $payload['email'] === '' || $payload['phone'] === '' || $payload['message'] === '') {
        $context['message'] = 'Semua field kontak wajib diisi.';
    } elseif (!filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) {
        $context['message'] = 'Alamat email tidak valid.';
    } else {
        $saved = createContact($db, $payload);
        if ($saved === null) {
            $context['message'] = 'Pesan gagal disimpan.';
        } else {
            clearOldInput();
            $context = [
                'name' => '',
                'email' => '',
                'phone' => '',
                'topic' => 'Layanan Servis',
                'body' => '',
                'message' => 'Pesan Anda sudah kami simpan. Tim FITS MOTOR akan menghubungi Anda.',
                'message_type' => 'success',
            ];
        }
    }
}

fitsMotorRenderPage(
    fitsMotorTemplatePath('pelacakan_kontak_fits_motor/code.php'),
    fitsMotorTrackingPageOptions('contact', $context)
);

