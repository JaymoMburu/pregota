<?php
define('LARAVEL_START', microtime(true));

// Read secret directly from .env — works even when config is cached
$envFile   = __DIR__ . '/../.env';
$envSecret = '';
if (file_exists($envFile)) {
    foreach (file($envFile) as $line) {
        $line = trim($line);
        if (strpos($line, 'DEPLOY_HOOK_SECRET=') === 0) {
            $envSecret = trim(substr($line, strlen('DEPLOY_HOOK_SECRET=')));
            break;
        }
    }
}

$secret = $_GET['secret'] ?? '';
if (! $envSecret || ! hash_equals($envSecret, $secret)) {
    http_response_code(403);
    die('Forbidden');
}

require __DIR__ . '/../vendor/autoload.php';
$app    = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$log = [];

foreach (['config:clear', 'route:clear', 'view:clear', 'config:cache', 'route:cache', 'view:cache'] as $cmd) {
    ob_start();
    $status = $kernel->call($cmd);
    $output = trim(ob_get_clean());
    $log[]  = ($status === 0 ? '✓' : '✗') . " {$cmd}" . ($output ? ": {$output}" : '');
}

if (isset($_GET['migrate'])) {
    ob_start();
    $status = $kernel->call('migrate', ['--force' => true]);
    $output = trim(ob_get_clean());
    $log[]  = ($status === 0 ? '✓' : '✗') . " migrate" . ($output ? ":\n{$output}" : '');
}

if (isset($_GET['log'])) {
    $logFile = __DIR__ . '/../storage/logs/laravel.log';
    if (file_exists($logFile)) {
        $lines = array_slice(file($logFile), -60);
        $log[] = "\n=== Last 60 log lines ===\n" . implode('', $lines);
    } else {
        $log[] = "\n=== No laravel.log found ===";
    }
}

header('Content-Type: text/plain');
echo implode("\n", $log) . "\n";
