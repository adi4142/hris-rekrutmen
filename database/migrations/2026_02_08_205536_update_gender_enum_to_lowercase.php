<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateGenderEnumToLowercase extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Update existing data to lowercase first (job_applicants only - no employees table in recruitment db)
        DB::statement("UPDATE job_applicants SET gender = 'male' WHERE gender = 'Male'");
        DB::statement("UPDATE job_applicants SET gender = 'female' WHERE gender = 'Female'");

        // Change ENUM definition
        DB::statement("ALTER TABLE job_applicants MODIFY COLUMN gender ENUM('male', 'female')");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement("ALTER TABLE job_applicants MODIFY COLUMN gender ENUM('Male', 'Female')");

        DB::statement("UPDATE job_applicants SET gender = 'Male' WHERE gender = 'male'");
        DB::statement("UPDATE job_applicants SET gender = 'Female' WHERE gender = 'female'");
    }
}
