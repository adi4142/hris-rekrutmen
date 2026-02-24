<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

$targetColumns = ['email_verified_at', 'email_verification_code', 'status'];
echo "Checking columns in users table:\n";
foreach ($targetColumns as $col) {
    echo "$col: " . (Schema::hasColumn('users', $col) ? "EXISTS" : "MISSING") . "\n";
}

$tables = ['activity_logs', 'system_settings'];
echo "\nChecking tables:\n";
foreach ($tables as $table) {
    echo "$table: " . (Schema::hasTable($table) ? "EXISTS" : "MISSING") . "\n";
}
