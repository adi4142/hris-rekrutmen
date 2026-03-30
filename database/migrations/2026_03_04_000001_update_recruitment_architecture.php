<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateRecruitmentArchitecture extends Migration
{
    public function up()
    {
        // 1. Tambah Quota ke Job Vacancies
        Schema::table('job_vacancies', function (Blueprint $table) {
            if (!$this->columnExists('job_vacancies', 'quota')) {
                $table->integer('quota')->default(1)->after('salary_nominal');
            }
        });

        // 2. Buat tabel Tahapan Lowongan (Wajib ditaati pelamar lowongan tsb)
        if (!Schema::hasTable('job_vacancy_stages')) {
            Schema::create('job_vacancy_stages', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('vacancies_id');
                $table->unsignedBigInteger('selection_id');
                $table->integer('order')->default(1);
                $table->timestamps();

                $table->foreign('vacancies_id')->references('vacancies_id')->on('job_vacancies')->onDelete('cascade');
                $table->foreign('selection_id')->references('selection_id')->on('selection')->onDelete('cascade');
            });
        }

        // 3. Update Job Applications untuk track progress
        Schema::table('job_applications', function (Blueprint $table) {
            if (!$this->columnExists('job_applications', 'current_stage_id')) {
                $table->unsignedBigInteger('current_stage_id')->nullable()->after('status');
                // current_stage_id akan merujuk ke job_vacancy_stages.id
            }
        });
    }

    public function down()
    {
        Schema::table('job_vacancies', function (Blueprint $table) {
            if ($this->columnExists('job_vacancies', 'quota')) {
                $table->dropColumn('quota');
            }
        });
        
        Schema::dropIfExists('job_vacancy_stages');

        Schema::table('job_applications', function (Blueprint $table) {
            if ($this->columnExists('job_applications', 'current_stage_id')) {
                $table->dropColumn('current_stage_id');
            }
        });
    }

    private function columnExists($table, $column)
    {
        $db = DB::connection()->getDatabaseName();
        $columns = DB::select("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '$db' AND TABLE_NAME = '$table' AND COLUMN_NAME = '$column'");
        return count($columns) > 0;
    }
}
