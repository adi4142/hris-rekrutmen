<?php

use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Updating job_applicants data...\n";
    DB::statement("UPDATE job_applicants SET gender = 'male' WHERE gender = 'Male'");
    DB::statement("UPDATE job_applicants SET gender = 'female' WHERE gender = 'Female'");
    
    echo "Updating employees data...\n";
    DB::statement("UPDATE employees SET gender = 'male' WHERE gender = 'Male'");
    DB::statement("UPDATE employees SET gender = 'female' WHERE gender = 'Female'");

    echo "Changing ENUM definition for job_applicants...\n";
    DB::statement("ALTER TABLE job_applicants MODIFY COLUMN gender ENUM('male', 'female')");
    
    echo "Changing ENUM definition for employees...\n";
    DB::statement("ALTER TABLE employees MODIFY COLUMN gender ENUM('male', 'female')");

    echo "Success! Database updated to allow 'male' and 'female' (lowercase).\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
