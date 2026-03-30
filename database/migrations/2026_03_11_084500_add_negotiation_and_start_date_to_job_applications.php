<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNegotiationAndStartDateToJobApplications extends Migration
{
    public function up()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->date('offering_start_date')->nullable()->after('offering_salary');
            $table->decimal('expected_salary', 15, 2)->nullable()->after('offering_start_date');
            $table->text('negotiation_reason')->nullable()->after('expected_salary');
            $table->text('hr_negotiation_note')->nullable()->after('negotiation_reason');
        });
    }

    public function down()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn(['offering_start_date', 'expected_salary', 'negotiation_reason', 'hr_negotiation_note']);
        });
    }
}
