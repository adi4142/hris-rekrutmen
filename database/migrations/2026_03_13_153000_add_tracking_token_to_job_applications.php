<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddTrackingTokenToJobApplications extends Migration
{
    public function up()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('job_applications', 'tracking_token')) {
                $table->string('tracking_token', 64)->nullable()->unique()->after('status');
            }
        });

        // Backfill token untuk lamaran yang sudah ada
        $apps = \DB::table('job_applications')->whereNull('tracking_token')->get();
        foreach ($apps as $app) {
            \DB::table('job_applications')
                ->where('application_id', $app->application_id)
                ->update(['tracking_token' => Str::random(48)]);
        }
    }

    public function down()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn('tracking_token');
        });
    }
}
