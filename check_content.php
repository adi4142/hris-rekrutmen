<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Roles:\n";
$roles = DB::table('roles')->get();
foreach ($roles as $role) {
    echo "- ID: {$role->roles_id}, Name: {$role->name}\n";
}

echo "\nSystem Settings:\n";
$settings = DB::table('system_settings')->get();
foreach ($settings as $s) {
    echo "- {$s->key}: {$s->value}\n";
}
