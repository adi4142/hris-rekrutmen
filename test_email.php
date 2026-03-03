<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationMail;

try {
    Mail::to('test@example.com')->send(new EmailVerificationMail(
        'Test User',
        'Pelamar',
        'dummytoken'
    ));
    echo "Mail sending test passed!\n";
} catch (\Exception $e) {
    echo "Mail sending test failed: " . $e->getMessage() . "\n";
}
