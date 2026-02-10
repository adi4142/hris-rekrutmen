<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateGenderEnumToLowercase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update existing data to lowercase first
        DB::statement("UPDATE job_applicants SET gender = 'male' WHERE gender = 'Male'");
        DB::statement("UPDATE job_applicants SET gender = 'female' WHERE gender = 'Female'");
        
        DB::statement("UPDATE employees SET gender = 'male' WHERE gender = 'Male'");
        DB::statement("UPDATE employees SET gender = 'female' WHERE gender = 'Female'");

        // Change ENUM definition (using raw SQL because ->change() often fails with ENUM)
        DB::statement("ALTER TABLE job_applicants MODIFY COLUMN gender ENUM('male', 'female')");
        DB::statement("ALTER TABLE employees MODIFY COLUMN gender ENUM('male', 'female')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE job_applicants MODIFY COLUMN gender ENUM('Male', 'Female')");
        DB::statement("ALTER TABLE employees MODIFY COLUMN gender ENUM('Male', 'Female')");
        
        DB::statement("UPDATE job_applicants SET gender = 'Male' WHERE gender = 'male'");
        DB::statement("UPDATE job_applicants SET gender = 'Female' WHERE gender = 'female'");
        
        DB::statement("UPDATE employees SET gender = 'Male' WHERE gender = 'male'");
        DB::statement("UPDATE employees SET gender = 'Female' WHERE gender = 'female'");
    }
}
