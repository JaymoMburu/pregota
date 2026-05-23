<?php
// Post-deploy hook — called by GitHub Actions after FTP upload
// Clears caches and optionally runs migrations
// NEVER commit the actual secret — it lives in GitHub Secrets only

define('LARAVEL_START', microtime(true));

$secret = $_GET['secret'] ?? '';
if (! hash_equals(getenv('DEPLOY_HOOK_SECRET') ?: '', $secret)) {
    // Fall back to checking against a value baked into .env via a custom key
    // The secret is passed as a query param and must match DEPLOY_HOOK_SECRET in .env
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    $envSecret = config('app.deploy_hook_secret') ?: env('DEPLOY_HOOK_SECRET');
    if (! $envSecret || ! hash_equals($envSecret, $secret)) {
        http_response_code(403);
        die('Forbidden');
    }
} else {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
}

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$log    = [];

// Always clear and rebuild caches
foreach (['config:clear', 'route:clear', 'view:clear', 'config:cache', 'route:cache', 'view:cache'] as $cmd) {
    ob_start();
    $status  = $kernel->call($cmd);
    $output  = trim(ob_get_clean());
    $log[]   = ($status === 0 ? '✓' : '✗') . " {$cmd}" . ($output ? ": {$output}" : '');
}

// Optionally run migrations
if (isset($_GET['migrate'])) {
    ob_start();
    $status = $kernel->call('migrate', ['--force' => true]);
    $output = trim(ob_get_clean());
    $log[]  = ($status === 0 ? '✓' : '✗') . " migrate" . ($output ? ":\n{$output}" : '');
}

// Optionally show last Laravel log entries
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
