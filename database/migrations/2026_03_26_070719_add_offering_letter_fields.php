<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOfferingLetterFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('job_applications', 'offering_letter_no')) {
                $table->string('offering_letter_no')->nullable()->after('offering_status');
            }
            if (!Schema::hasColumn('job_applications', 'offering_letter_file')) {
                $table->string('offering_letter_file')->nullable()->after('offering_letter_no');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn(['offering_letter_no', 'offering_letter_file']);
        });
    }
}
