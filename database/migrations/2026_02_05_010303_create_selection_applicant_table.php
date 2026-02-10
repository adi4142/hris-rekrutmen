<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSelectionApplicantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selection_applicant', function (Blueprint $table) {
            $table->bigIncrements('selection_applicant_id');
            $table->foreignId('selection_id')->references('selection_id')->on('selection');
            $table->foreignId('application_id')->references('application_id')->on('job_applications');
            $table->enum('status', ['passed', 'failed', 'process', 'unprocess'])->default('unprocess');
            $table->integer('score')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('selection_applicant');
    }
}
