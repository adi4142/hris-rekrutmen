<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRecruitmentBatchSystem extends Migration
{
    public function up()
    {
        // 1. Create recruitment_batches table
        if (!Schema::hasTable('recruitment_batches')) {
            Schema::create('recruitment_batches', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('vacancies_id');
                $table->string('name'); 
                $table->date('date');
                $table->string('status')->default('active'); 
                $table->timestamps();

                $table->foreign('vacancies_id')->references('vacancies_id')->on('job_vacancies')->onDelete('cascade');
            });
        }

        // 2. Create recruitment_batch_stages table
        if (!Schema::hasTable('recruitment_batch_stages')) {
            Schema::create('recruitment_batch_stages', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('batch_id');
                $table->unsignedBigInteger('selection_id');
                $table->time('start_time')->nullable();
                $table->time('end_time')->nullable();
                $table->string('location')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();

                $table->foreign('batch_id')->references('id')->on('recruitment_batches')->onDelete('cascade');
                $table->foreign('selection_id')->references('selection_id')->on('selection')->onDelete('cascade');
            });
        }

        // 3. Update job_applications
        Schema::table('job_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('job_applications', 'batch_id')) {
                $table->unsignedBigInteger('batch_id')->nullable()->after('vacancies_id');
                $table->foreign('batch_id')->references('id')->on('recruitment_batches')->onDelete('set null');
            }
        });

        // 4. Update selection_applicant
        Schema::table('selection_applicant', function (Blueprint $table) {
            if (!Schema::hasColumn('selection_applicant', 'batch_stage_id')) {
                $table->unsignedBigInteger('batch_stage_id')->nullable()->after('application_id');
            }
        });

        // 5. Drop unused JobVacancyStage table
        Schema::dropIfExists('job_vacancy_stages');
    }

    public function down()
    {
        Schema::table('selection_applicant', function (Blueprint $table) {
            $table->dropColumn('batch_stage_id');
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropForeign(['batch_id']);
            $table->dropColumn('batch_id');
        });

        Schema::dropIfExists('recruitment_batch_stages');
        Schema::dropIfExists('recruitment_batches');
    }
}
