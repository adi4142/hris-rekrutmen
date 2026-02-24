<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersAndSelectionApplicantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->nullable()->after('name');
            }
        });

        Schema::table('selection_applicant', function (Blueprint $table) {
            if (!Schema::hasColumn('selection_applicant', 'selection_date')) {
                $table->date('selection_date')->nullable()->after('notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });

        Schema::table('selection_applicant', function (Blueprint $table) {
            $table->dropColumn('selection_date');
        });
    }
}
