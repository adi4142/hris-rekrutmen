<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpiredAtToJobVacanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::table('job_vacancies', function (Blueprint $table) {
        $table->date('expired_at')->nullable()->after('description');
    });
}

public function down()
{
    Schema::table('job_vacancies', function (Blueprint $table) {
        $table->dropColumn('expired_at');
    });
}
}
