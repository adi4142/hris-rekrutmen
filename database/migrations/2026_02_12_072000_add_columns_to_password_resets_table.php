<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPasswordResetsTable extends Migration
{
    public function up()
    {
        Schema::table('password_resets', function (Blueprint $table) {
            if (!Schema::hasColumn('password_resets', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('token');
            }
            if (!Schema::hasColumn('password_resets', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('is_verified');
            }
        });
    }

    public function down()
    {
        Schema::table('password_resets', function (Blueprint $table) {
            if (Schema::hasColumn('password_resets', 'is_verified')) {
                $table->dropColumn('is_verified');
            }
            if (Schema::hasColumn('password_resets', 'expires_at')) {
                $table->dropColumn('expires_at');
            }
        });
    }
}
