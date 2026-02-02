<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollsDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payrolls_detail', function (Blueprint $table) {
            $table->bigIncrements('payroll_detail_id');
            $table->string('nip');
            $table->unsignedBigInteger('payroll_id');
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('total_allowance', 15, 2);
            $table->decimal('total_deduction', 15, 2);
            $table->decimal('total_salary', 15, 2);
            $table->timestamps();

            $table->foreign('nip')->references('nip')->on('employees')->onDelete('cascade');
            $table->foreign('payroll_id')->references('payroll_id')->on('payrolls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payrolls_detail');
    }
}
