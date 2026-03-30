<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOfferingLetterFieldsToJobApplicationsTable extends Migration
{
    public function up()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('job_applications', 'offering_start_date')) {
                $table->date('offering_start_date')->nullable();
            }
            if (!Schema::hasColumn('job_applications', 'offering_letter_no')) {
                $table->string('offering_letter_no', 50)->nullable()->unique();
            }
            if (!Schema::hasColumn('job_applications', 'offering_letter_file')) {
                $table->string('offering_letter_file')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn(['offering_start_date', 'offering_letter_no', 'offering_letter_file']);
        });
    }
}