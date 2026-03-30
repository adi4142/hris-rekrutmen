<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToRecruitmentTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('job_applicants', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('job_applications', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('job_applicants', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
