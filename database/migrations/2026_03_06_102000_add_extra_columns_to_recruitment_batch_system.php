<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraColumnsToRecruitmentBatchSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruitment_batches', function (Blueprint $table) {
            $table->integer('quota')->nullable()->after('name')->comment('Maksimal pelamar dalam batch');
            $table->text('description')->nullable()->after('quota');
        });

        Schema::table('recruitment_batch_stages', function (Blueprint $table) {
            $table->string('pic_name')->nullable()->after('location')->comment('Nama Pewawancara/Penilai');
            $table->string('room_url')->nullable()->after('pic_name')->comment('URL Meeting (jika online)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recruitment_batches', function (Blueprint $table) {
            $table->dropColumn(['quota', 'description']);
        });

        Schema::table('recruitment_batch_stages', function (Blueprint $table) {
            $table->dropColumn(['pic_name', 'room_url']);
        });
    }
}
