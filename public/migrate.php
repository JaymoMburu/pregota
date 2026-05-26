<?php
// Temporary migration runner — DELETE THIS FILE after use
define('LARAVEL_START', microtime(true));

$secret = $_GET['secret'] ?? '';
if ($secret !== 'pregota-migrate-2026') {
    http_response_code(403);
    die('Forbidden');
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

ob_start();
$status = $kernel->call('migrate', ['--force' => true]);
$output = ob_get_clean();

echo '<pre style="font-family:monospace;font-size:13px;padding:20px">';
echo "Exit status: {$status}\n\n";
echo htmlspecialchars($output);
echo '</pre>';
