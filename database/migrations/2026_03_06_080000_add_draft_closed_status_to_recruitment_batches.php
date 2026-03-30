<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddDraftClosedStatusToRecruitmentBatches extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE recruitment_batches MODIFY COLUMN status ENUM('draft', 'active', 'closed') DEFAULT 'active'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE recruitment_batches MODIFY COLUMN status ENUM('active', 'completed') DEFAULT 'active'");
    }
}
