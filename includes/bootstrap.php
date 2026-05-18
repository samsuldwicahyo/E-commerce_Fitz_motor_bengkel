<?php
declare(strict_types=1);

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/repositories.php';
require_once __DIR__ . '/auth.php';

ensureDirectoryExists(UPLOAD_PATH);
ensureDirectoryExists(UPLOAD_PATH . 'products' . DIRECTORY_SEPARATOR);
ensureDirectoryExists(UPLOAD_PATH . 'banners' . DIRECTORY_SEPARATOR);
ensureDirectoryExists(UPLOAD_PATH . 'team' . DIRECTORY_SEPARATOR);

if ($db instanceof PDO) {
    ensureDatabaseSchema($db);
    ensureSeedData($db);
}
