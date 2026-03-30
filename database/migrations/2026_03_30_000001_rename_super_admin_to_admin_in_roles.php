<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RenameSuperAdminToAdminInRoles extends Migration
{
    /**
     * Rename role "Super Admin" menjadi "Admin" di tabel roles.
     */
    public function up()
    {
        DB::table('roles')
            ->where('name', 'Super Admin')
            ->update(['name' => 'Admin']);
    }

    /**
     * Rollback: kembalikan "Admin" menjadi "Super Admin".
     */
    public function down()
    {
        DB::table('roles')
            ->where('name', 'Admin')
            ->update(['name' => 'Super Admin']);
    }
}
