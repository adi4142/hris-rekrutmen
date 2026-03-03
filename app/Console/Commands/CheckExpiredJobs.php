<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\JobVacancie;
use Carbon\Carbon;

class CheckExpiredJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */ 
protected $signature = 'jobs:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */

public function handle()
{
    $today = Carbon::today();

    $affected = JobVacancie::whereDate('expired_at', '<=', $today)
        ->where('status', 'open')
        ->update(['status' => 'closed']);

    $this->info("Updated {$affected} expired jobs.");
}
}
