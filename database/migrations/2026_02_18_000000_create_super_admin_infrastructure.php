<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSuperAdminInfrastructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Tambahkan role Admin
        DB::table('roles')->insertOrIgnore([
            ['name' => 'Admin', 'description' => 'Mengatur sistem dan pengguna'],
        ]);

        // 2. Tambahkan kolom status aktif pada user
        if (!Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('email');
            });
        }

        // 3. Tabel System Settings
        if (!Schema::hasTable('system_settings')) {
            Schema::create('system_settings', function (Blueprint $table) {
                $table->id('setting_id');
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('group')->default('general');
                $table->timestamps();
            });

            // Default settings
            DB::table('system_settings')->insert([
                ['key' => 'system_status', 'value' => 'active', 'group' => 'system'],
                ['key' => 'company_logo', 'value' => null, 'group' => 'appearance'],
                ['key' => 'system_email', 'value' => 'admin@hris.com', 'group' => 'system'],
                ['key' => 'default_application_status', 'value' => 'unprocess', 'group' => 'recruitment'],
            ]);
        }

        // 4. Tabel Activity Logs
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id('log_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('activity');
                $table->string('module');
                $table->text('details')->nullable();
                $table->string('ip_address')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('system_settings');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        DB::table('roles')->where('name', 'Admin')->delete();
    }
}
