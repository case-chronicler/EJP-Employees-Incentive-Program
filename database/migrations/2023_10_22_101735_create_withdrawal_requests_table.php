<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id('withdrawal_request_id');
            $table->timestamps();
            $table->string('withdrawal_request_link_id')->unique();
            $table->decimal('amount');
            $table->enum('status', ['success', 'pending', 'failed', 'rejected']);
            $table->foreignId('employee_id')->constrained('employees', 'employee_id');
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
        Schema::dropIfExists('withdrawal_requests');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
