<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$all_users = App\User::get(['user_id', 'name', 'email', 'status', 'email_verification_code'])->toArray();
file_put_contents('check_users_output.json', json_encode($all_users, JSON_PRETTY_PRINT));
