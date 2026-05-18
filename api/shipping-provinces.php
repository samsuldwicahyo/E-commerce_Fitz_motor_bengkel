<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

if (RAJAONGKIR_API_KEY !== '' && RAJAONGKIR_BASE_URL !== '') {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => rtrim(RAJAONGKIR_BASE_URL, '/') . '/province',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['key: ' . RAJAONGKIR_API_KEY],
        CURLOPT_TIMEOUT => 15,
    ]);
    $response = curl_exec($curl);
    $statusCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if (is_string($response) && $statusCode >= 200 && $statusCode < 300) {
        $decoded = json_decode($response, true);
        $results = $decoded['rajaongkir']['results'] ?? null;

        if (is_array($results)) {
            jsonResponse([
                'success' => true,
                'provinces' => $results,
                'source' => 'rajaongkir',
            ]);
        }
    }
}

jsonResponse([
    'success' => true,
    'provinces' => [
        ['id' => 'jateng', 'name' => 'Jawa Tengah'],
        ['id' => 'jabar', 'name' => 'Jawa Barat'],
        ['id' => 'dki', 'name' => 'DKI Jakarta'],
        ['id' => 'jatim', 'name' => 'Jawa Timur'],
    ],
    'source' => 'mock',
]);
