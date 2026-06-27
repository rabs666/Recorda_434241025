<?php

if (isset($_GET['debug_key'])) {
    header('Content-Type: text/plain');
    echo "getenv('APP_KEY'): " . var_export(getenv('APP_KEY'), true) . "\n";
    echo "\$_ENV['APP_KEY']: " . var_export($_ENV['APP_KEY'] ?? null, true) . "\n";
    echo "\$_SERVER['APP_KEY']: " . var_export($_SERVER['APP_KEY'] ?? null, true) . "\n";
    echo ".env file exists: " . var_export(file_exists(__DIR__.'/../.env'), true) . "\n";
    if (file_exists(__DIR__.'/../.env')) {
        $lines = file(__DIR__.'/../.env');
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), 'APP_KEY')) {
                echo "Found in .env: " . trim($line) . "\n";
            }
        }
    }
    exit;
}

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
