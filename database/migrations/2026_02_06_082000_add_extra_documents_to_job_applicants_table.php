<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraDocumentsToJobApplicantsTable extends Migration
{
    public function up()
    {
        Schema::table('job_applicants', function (Blueprint $table) {
            if (!Schema::hasColumn('job_applicants', 'cover_letter')) {
                $table->string('cover_letter')->nullable();
            }
            if (!Schema::hasColumn('job_applicants', 'portfolio')) {
                $table->string('portfolio')->nullable();
            }
            if (!Schema::hasColumn('job_applicants', 'last_diploma')) {
                $table->string('last_diploma')->nullable();
            }
            if (!Schema::hasColumn('job_applicants', 'transcript')) {
                $table->string('transcript')->nullable();
            }
            if (!Schema::hasColumn('job_applicants', 'supporting_certificates')) {
                $table->string('supporting_certificates')->nullable();
            }
            if (!Schema::hasColumn('job_applicants', 'work_experience')) {
                $table->text('work_experience')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('job_applicants', function (Blueprint $table) {
            $table->dropColumn([
                'cover_letter',
                'portfolio',
                'last_diploma',
                'transcript',
                'supporting_certificates',
                'work_experience',
            ]);
        });
    }
}
