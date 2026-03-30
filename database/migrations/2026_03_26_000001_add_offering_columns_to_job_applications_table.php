<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOfferingColumnsToJobApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_applications', function (Blueprint $blueprint) {
            if (!Schema::hasColumn('job_applications', 'offering_job_desc')) { $blueprint->text('offering_job_desc')->nullable(); }
            if (!Schema::hasColumn('job_applications', 'offering_salary')) { $blueprint->integer('offering_salary')->nullable(); }
            if (!Schema::hasColumn('job_applications', 'offering_start_date')) { $blueprint->date('offering_start_date')->nullable(); }
            if (!Schema::hasColumn('job_applications', 'offering_working_hours')) { $blueprint->string('offering_working_hours')->nullable(); }
            if (!Schema::hasColumn('job_applications', 'offering_leave_quota')) { $blueprint->string('offering_leave_quota')->nullable(); }
            if (!Schema::hasColumn('job_applications', 'offering_accepted_at')) { $blueprint->timestamp('offering_accepted_at')->nullable(); }
            if (!Schema::hasColumn('job_applications', 'offering_rejected_at')) { $blueprint->timestamp('offering_rejected_at')->nullable(); }
            if (!Schema::hasColumn('job_applications', 'expected_salary')) { $blueprint->integer('expected_salary')->nullable(); }
            if (!Schema::hasColumn('job_applications', 'negotiation_reason')) { $blueprint->text('negotiation_reason')->nullable(); }
            if (!Schema::hasColumn('job_applications', 'hr_negotiation_note')) { $blueprint->text('hr_negotiation_note')->nullable(); }
            if (!Schema::hasColumn('job_applications', 'offering_letter_no')) { $blueprint->string('offering_letter_no')->nullable(); }
            if (!Schema::hasColumn('job_applications', 'offering_letter_file')) { $blueprint->string('offering_letter_file')->nullable(); }
            if (!Schema::hasColumn('job_applications', 'offering_status')) { $blueprint->string('offering_status')->nullable(); }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_applications', function (Blueprint $blueprint) {
            $blueprint->dropColumn([
                'offering_job_desc',
                'offering_salary',
                'offering_start_date',
                'offering_working_hours',
                'offering_leave_quota',
                'offering_accepted_at',
                'offering_rejected_at',
                'expected_salary',
                'negotiation_reason',
                'hr_negotiation_note',
                'offering_letter_no',
                'offering_letter_file',
                'offering_status',
            ]);
        });
    }
}
