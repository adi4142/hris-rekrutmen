<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\JobApplication;
use App\User;
use Carbon\Carbon;

class DeleteRejectedApplicants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'applicants:delete-rejected';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus akun pelamar yang seluruh lamarannya ditolak setelah 24 jam';

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
     * @return mixed
     */
    public function handle()
    {
        $oneDayAgo = Carbon::now()->subDay();

        // Cari lamaran yang statusnya final (rejected/hired) dan diupdate lebih dari 1 hari yang lalu
        $finalApplications = JobApplication::whereIn('status', ['rejected', 'hired'])
            ->where('updated_at', '<=', $oneDayAgo)
            ->get();

        $deletedCount = 0;

        foreach ($finalApplications as $application) {
            $applicant = $application->jobApplicant;
            if ($applicant && $applicant->user_id) {
                $user = User::find($applicant->user_id);
                if ($user) {
                    // Cek apakah user ini memiliki lamaran lain yang masih aktif (belum final)
                    $hasActiveApplications = JobApplication::where('job_applicant_id', $applicant->job_applicant_id)
                        ->whereNotIn('status', ['rejected', 'hired'])
                        ->exists();

                    if (!$hasActiveApplications) {
                        $email = $user->email;
                        
                        // Lepas relasi user_id agar data pelamar tidak ikut terhapus cascade
                        $applicant->update(['user_id' => null]);
                        
                        // Hapus akun login saja
                        $user->delete();
                        
                        $deletedCount++;
                        $this->info("Akun login {$email} dihapus. Data lamaran tetap tersimpan sebagai arsip.");
                    }
                }
            }
        }

        $this->info("Total akun login yang dihapus: {$deletedCount}");
    }
}
