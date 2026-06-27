<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLocal = in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) || $_SERVER['SERVER_NAME'] === 'localhost';

if ($isLocal) {
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'reportes_cerroazul');
    define('DB_USER', 'root');
    define('DB_PASS', '');
} else {
    define('DB_HOST', 'sql207.ezyro.com');
    define('DB_NAME', 'ezyro_42273482_reportes_cerroazul');
    define('DB_USER', 'ezyro_42273482');
    define('DB_PASS', 'b5732b352afd');
}

define('API_TOKEN', 'ae69a4dddfa40925303652b461c09b8922437252ccd277a83ebe1c1d10b4d9c3');