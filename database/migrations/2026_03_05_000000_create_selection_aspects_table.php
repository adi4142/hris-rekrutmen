<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSelectionAspectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('selection_aspects')) {
            Schema::create('selection_aspects', function (Blueprint $table) {
                $table->bigIncrements('aspect_id');
                $table->unsignedBigInteger('selection_id');
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamps();

                $table->foreign('selection_id')->references('selection_id')->on('selection')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('selection_applicant_scores')) {
            Schema::create('selection_applicant_scores', function (Blueprint $table) {
                $table->bigIncrements('score_id');
                $table->unsignedBigInteger('selection_applicant_id');
                $table->unsignedBigInteger('aspect_id');
                $table->decimal('score', 8, 2)->default(0);
                $table->timestamps();

                $table->foreign('selection_applicant_id', 'fk_sel_app_scores_app_id')->references('selection_applicant_id')->on('selection_applicant')->onDelete('cascade');
                $table->foreign('aspect_id')->references('aspect_id')->on('selection_aspects')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP TABLE IF EXISTS selection_applicant_scores");
        DB::statement("DROP TABLE IF EXISTS selection_aspects");
    }

}
