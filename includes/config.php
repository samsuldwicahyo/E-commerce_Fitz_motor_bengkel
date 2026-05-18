<?php
declare(strict_types=1);

defined('APP_NAME') || define('APP_NAME', 'FITS MOTOR');
defined('APP_ENV') || define('APP_ENV', 'local');
defined('DB_DRIVER') || define('DB_DRIVER', 'mysql');
defined('DB_HOST') || define('DB_HOST', '127.0.0.1');
defined('DB_PORT') || define('DB_PORT', '3306');
defined('DB_NAME') || define('DB_NAME', 'fits_motor');
defined('DB_USER') || define('DB_USER', 'root');
defined('DB_PASS') || define('DB_PASS', '');
defined('DB_CHARSET') || define('DB_CHARSET', 'utf8mb4');

defined('RAJAONGKIR_API_KEY') || define('RAJAONGKIR_API_KEY', getenv('RAJAONGKIR_API_KEY') ?: 'ALpNu4046ce40378db7ae558hzndRcf2');
defined('RAJAONGKIR_BASE_URL') || define('RAJAONGKIR_BASE_URL', getenv('RAJAONGKIR_BASE_URL') ?: 'https://rajaongkir.komerce.id/api/v1');
defined('RAJAONGKIR_ORIGIN_CITY') || define('RAJAONGKIR_ORIGIN_CITY', getenv('RAJAONGKIR_ORIGIN_CITY') ?: 'Banjarnegara');
defined('TRIPAY_API_KEY') || define('TRIPAY_API_KEY', getenv('TRIPAY_API_KEY') ?: 'DEV-vZj058aO5FeoPPSFtEOOcXVEYVQccJL7Hfu9cvOY');
defined('TRIPAY_PRIVATE_KEY') || define('TRIPAY_PRIVATE_KEY', getenv('TRIPAY_PRIVATE_KEY') ?: 'dxvSZ-RbaAJ-J6LKG-LM6Hj-2r8Cn');
defined('TRIPAY_MERCHANT_CODE') || define('TRIPAY_MERCHANT_CODE', getenv('TRIPAY_MERCHANT_CODE') ?: 'T47105');
defined('TRIPAY_BASE_URL') || define('TRIPAY_BASE_URL', getenv('TRIPAY_BASE_URL') ?: 'https://tripay.co.id/api-sandbox');
defined('TRIPAY_CALLBACK_URL') || define('TRIPAY_CALLBACK_URL', getenv('TRIPAY_CALLBACK_URL') ?: 'https://determines-average-cbs-demand.trycloudflare.com/webhooks/tripay');
defined('KOMERCE_PAYMENT_API_KEY') || define('KOMERCE_PAYMENT_API_KEY', getenv('KOMERCE_PAYMENT_API_KEY') ?: 'yIIzFcTk6ce40378db7ae558EveAd8rh');
defined('KOMERCE_QRISLY_API_KEY') || define('KOMERCE_QRISLY_API_KEY', getenv('KOMERCE_QRISLY_API_KEY') ?: 'yIIzFcTk6ce40378db7ae558EveAd8rh');
defined('FONNTE_API_KEY') || define('FONNTE_API_KEY', '');
defined('FONNTE_TOKEN') || define('FONNTE_TOKEN', FONNTE_API_KEY);
defined('WA_NUMBER') || define('WA_NUMBER', '628123456789');
defined('WA_MESSAGE') || define('WA_MESSAGE', 'Halo Fits Motor! Saya ingin konsultasi.');

$documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
$defaultBaseUrl = 'http://localhost';

if (is_string($documentRoot) && $documentRoot !== '') {
    $defaultBaseUrl .= '/Fits_motor';
}

defined('BASE_URL') || define('BASE_URL', rtrim($defaultBaseUrl, '/'));
defined('UPLOAD_PATH') || define('UPLOAD_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR);
defined('UPLOAD_URL') || define('UPLOAD_URL', rtrim(BASE_URL, '/') . '/uploads/');
