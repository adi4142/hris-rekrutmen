<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->string('nip')->primary();
            $table->string('name');
            $table->foreignId('user_id')->references('user_id')->on('users');
            $table->string('email')->unique();
            $table->string('phone');
            $table->foreignId('departement_id')->references('departement_id')->on('departements');
            $table->foreignId('position_id')->references('position_id')->on('positions');
            $table->foreignId('division_id')->references('division_id')->on('divisions');
            $table->text('address');
            $table->date('date_of_birth');
            $table->enum('gender', ['Male', 'Female']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
