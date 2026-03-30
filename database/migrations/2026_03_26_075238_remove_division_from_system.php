<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDivisionFromSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->dropForeign(['division_id']);
            $table->dropColumn('division_id');
        });

        Schema::dropIfExists('divisions');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->id('division_id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->unsignedBigInteger('division_id')->nullable()->after('departement_id');
            $table->foreign('division_id')->references('division_id')->on('divisions');
        });
    }
}
