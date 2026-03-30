<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOfferingDetailsToJobApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_applications', function (Blueprint $column) {
            $column->text('offering_job_desc')->nullable();
            $column->decimal('offering_salary', 15, 2)->nullable();
            $column->string('offering_working_hours')->nullable();
            $column->string('offering_leave_quota')->nullable();
            $column->timestamp('offering_accepted_at')->nullable();
            $column->timestamp('offering_rejected_at')->nullable();
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
            $table->dropColumn([
                'offering_job_desc',
                'offering_salary',
                'offering_working_hours',
                'offering_leave_quota',
                'offering_accepted_at',
                'offering_rejected_at'
            ]);
        });
    }
}
