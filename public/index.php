<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
$app = (require_once __DIR__.'/../bootstrap/app.php');
if (getenv('LARAVEL_STORAGE_PATH')) {
    $_ENV['LARAVEL_STORAGE_PATH'] = getenv('LARAVEL_STORAGE_PATH');
    $app->useBootstrapPath($_ENV['LARAVEL_STORAGE_PATH'] . '/bootstrap');
}
$app->handleRequest(Request::capture());
