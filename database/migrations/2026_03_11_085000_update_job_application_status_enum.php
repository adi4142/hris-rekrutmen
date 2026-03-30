<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateJobApplicationStatusEnum extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE job_applications MODIFY COLUMN status ENUM('applied', 'process', 'rejected', 'accepted', 'pending', 'approved', 'offering', 'offering_sent', 'negotiation_requested', 'hired') NOT NULL DEFAULT 'applied'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE job_applications MODIFY COLUMN status ENUM('applied', 'process', 'rejected', 'accepted', 'pending', 'approved', 'offering') NOT NULL DEFAULT 'applied'");
    }
}
