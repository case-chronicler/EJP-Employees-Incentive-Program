<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id('withdrawal_id');
            $table->timestamps();
            $table->decimal('amount');
            $table->foreignId('employee_id')->constrained('employees', 'employee_id');

            $table->string('withdrawal_request_link_id');
 
            $table->foreign('withdrawal_request_link_id')->references('withdrawal_request_link_id')->on('withdrawal_requests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('deposits_withdrawals');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
