<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$province = trim((string) queryInput('province_id', 'jateng'));

if (RAJAONGKIR_API_KEY !== '' && RAJAONGKIR_BASE_URL !== '' && $province !== '') {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => rtrim(RAJAONGKIR_BASE_URL, '/') . '/city?province=' . urlencode($province),
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
                'cities' => $results,
                'source' => 'rajaongkir',
            ]);
        }
    }
}

$cities = [
    'jateng' => [
        ['id' => 'banjarnegara', 'name' => 'Banjarnegara'],
        ['id' => 'semarang', 'name' => 'Semarang'],
        ['id' => 'solo', 'name' => 'Surakarta'],
    ],
    'jabar' => [
        ['id' => 'bandung', 'name' => 'Bandung'],
        ['id' => 'bekasi', 'name' => 'Bekasi'],
    ],
    'dki' => [
        ['id' => 'jaksel', 'name' => 'Jakarta Selatan'],
        ['id' => 'jakbar', 'name' => 'Jakarta Barat'],
    ],
    'jatim' => [
        ['id' => 'surabaya', 'name' => 'Surabaya'],
        ['id' => 'malang', 'name' => 'Malang'],
    ],
];

jsonResponse([
    'success' => true,
    'cities' => $cities[$province] ?? $cities['jateng'],
    'source' => 'mock',
]);
