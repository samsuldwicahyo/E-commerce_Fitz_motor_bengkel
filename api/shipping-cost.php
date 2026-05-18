<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$courier = strtoupper(trim((string) requestInput('courier', queryInput('courier', 'JNE'))));
$destination = (string) requestInput('city_id', queryInput('city_id', ''));
$cityName = trim((string) requestInput('city_name', queryInput('city_name', '')));
$provinceName = trim((string) requestInput('province_name', queryInput('province_name', '')));
$district = trim((string) requestInput('district', queryInput('district', '')));
$postalCode = trim((string) requestInput('postal_code', queryInput('postal_code', '')));
$weight = max(1, (int) requestInput('weight', queryInput('weight', 1000)));

if (RAJAONGKIR_API_KEY !== '' && RAJAONGKIR_BASE_URL !== '' && $destination !== '' && str_contains(RAJAONGKIR_BASE_URL, 'rajaongkir.komerce.id/api/v1')) {
    $requestApi = static function (string $url, string $method = 'GET', array $headers = [], ?array $payload = null): ?array {
        $curl = curl_init();
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 20,
        ];

        if ($method === 'POST') {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = http_build_query($payload ?? []);
        }

        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        $statusCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if (!is_string($response) || $statusCode < 200 || $statusCode >= 300) {
            return null;
        }

        $decoded = json_decode($response, true);

        return is_array($decoded) ? $decoded : null;
    };

    $resolveDestinationId = static function (string $searchValue) use ($requestApi, $cityName, $provinceName, $district, $postalCode): ?int {
        $searchValue = trim($searchValue);

        if ($searchValue === '') {
            return null;
        }

        if (ctype_digit($searchValue)) {
            return (int) $searchValue;
        }

        $decoded = $requestApi(
            rtrim(RAJAONGKIR_BASE_URL, '/') . '/destination/domestic-destination?search=' . urlencode($searchValue) . '&limit=20&offset=0',
            'GET',
            ['key: ' . RAJAONGKIR_API_KEY]
        );
        $rows = $decoded['data'] ?? null;

        if (!is_array($rows) || $rows === []) {
            return null;
        }

        $normalizedCity = strtoupper($cityName);
        $normalizedProvince = strtoupper($provinceName);
        $normalizedDistrict = strtoupper($district);
        $normalizedPostalCode = strtoupper($postalCode);

        usort($rows, static function (array $left, array $right) use ($normalizedCity, $normalizedProvince, $normalizedDistrict, $normalizedPostalCode): int {
            $score = static function (array $item) use ($normalizedCity, $normalizedProvince, $normalizedDistrict, $normalizedPostalCode): int {
                $points = 0;

                if ($normalizedPostalCode !== '' && strtoupper((string) ($item['zip_code'] ?? '')) === $normalizedPostalCode) {
                    $points += 6;
                }

                if ($normalizedDistrict !== '' && strtoupper((string) ($item['district_name'] ?? '')) === $normalizedDistrict) {
                    $points += 4;
                }

                if ($normalizedCity !== '' && strtoupper((string) ($item['city_name'] ?? '')) === $normalizedCity) {
                    $points += 3;
                }

                if ($normalizedProvince !== '' && strtoupper((string) ($item['province_name'] ?? '')) === $normalizedProvince) {
                    $points += 2;
                }

                return $points;
            };

            return $score($right) <=> $score($left);
        });

        return isset($rows[0]['id']) ? (int) $rows[0]['id'] : null;
    };

    $originLookup = trim((string) RAJAONGKIR_ORIGIN_CITY);
    $destinationLookup = trim($postalCode . ' ' . $district . ' ' . ($cityName !== '' ? $cityName : $destination) . ' ' . $provinceName);
    $originId = $resolveDestinationId($originLookup);
    $destinationId = $resolveDestinationId($destinationLookup !== '' ? $destinationLookup : ($cityName !== '' ? $cityName : $destination));

    if ($originId !== null && $destinationId !== null) {
        $decoded = $requestApi(
            rtrim(RAJAONGKIR_BASE_URL, '/') . '/calculate/domestic-cost',
            'POST',
            [
                'key: ' . RAJAONGKIR_API_KEY,
                'Content-Type: application/x-www-form-urlencoded',
            ],
            [
                'origin' => $originId,
                'destination' => $destinationId,
                'weight' => $weight,
                'courier' => strtolower($courier),
                'price' => 'lowest',
            ]
        );
        $results = $decoded['data'] ?? null;

        if (is_array($results) && $results !== []) {
            $services = array_map(
                static fn (array $item): array => [
                    'service' => $item['service'] ?? '',
                    'description' => $item['description'] ?? '',
                    'etd' => $item['etd'] ?? '',
                    'cost' => $item['cost'] ?? 0,
                ],
                $results
            );

            jsonResponse([
                'success' => true,
                'courier' => $courier,
                'services' => $services,
                'source' => 'rajaongkir-v2',
            ]);
        }
    }
}

if (RAJAONGKIR_API_KEY !== '' && RAJAONGKIR_BASE_URL !== '' && $destination !== '' && !str_contains(RAJAONGKIR_BASE_URL, 'rajaongkir.komerce.id/api/v1')) {
    $postData = http_build_query([
        'origin' => RAJAONGKIR_ORIGIN_CITY,
        'destination' => $destination,
        'weight' => $weight,
        'courier' => strtolower($courier),
    ]);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => rtrim(RAJAONGKIR_BASE_URL, '/') . '/cost',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_HTTPHEADER => [
            'key: ' . RAJAONGKIR_API_KEY,
            'content-type: application/x-www-form-urlencoded',
        ],
        CURLOPT_TIMEOUT => 20,
    ]);
    $response = curl_exec($curl);
    $statusCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if (is_string($response) && $statusCode >= 200 && $statusCode < 300) {
        $decoded = json_decode($response, true);
        $results = $decoded['rajaongkir']['results'][0]['costs'] ?? null;

        if (is_array($results)) {
            $services = array_map(
                static fn (array $item): array => [
                    'service' => $item['service'] ?? '',
                    'description' => $item['description'] ?? '',
                    'etd' => $item['cost'][0]['etd'] ?? '',
                    'cost' => $item['cost'][0]['value'] ?? 0,
                ],
                $results
            );

            jsonResponse([
                'success' => true,
                'courier' => $courier,
                'services' => $services,
                'source' => 'rajaongkir',
            ]);
        }
    }
}

$services = [
    'JNE' => [
        ['service' => 'REG', 'etd' => '3-4 hari', 'cost' => 18000],
        ['service' => 'YES', 'etd' => '1-2 hari', 'cost' => 35000],
        ['service' => 'OKE', 'etd' => '5-7 hari', 'cost' => 12000],
    ],
    'SICEPAT' => [
        ['service' => 'REG', 'etd' => '2-3 hari', 'cost' => 19000],
        ['service' => 'BEST', 'etd' => '1 hari', 'cost' => 32000],
    ],
    'ANTERAJA' => [
        ['service' => 'REG', 'etd' => '3-5 hari', 'cost' => 17000],
        ['service' => 'NEXTDAY', 'etd' => '1-2 hari', 'cost' => 30000],
    ],
];

jsonResponse([
    'success' => true,
    'courier' => $courier,
    'services' => $services[$courier] ?? $services['JNE'],
    'source' => 'mock',
]);
