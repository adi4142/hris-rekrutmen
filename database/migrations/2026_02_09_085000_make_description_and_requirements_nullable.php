<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeDescriptionAndRequirementsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Menggunakan DB::statement untuk menghindari error Doctrine DBAL pada Laravel 7
        \DB::statement('ALTER TABLE job_vacancies MODIFY description TEXT NULL');
        \DB::statement('ALTER TABLE job_vacancies MODIFY requirements TEXT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('ALTER TABLE job_vacancies MODIFY description TEXT NOT NULL');
        \DB::statement('ALTER TABLE job_vacancies MODIFY requirements TEXT NOT NULL');
    }
}
