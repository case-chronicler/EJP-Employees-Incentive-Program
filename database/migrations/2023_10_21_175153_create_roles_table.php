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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->timestamp('end_date')->nullable();
            $table->timestamp('start_date');
            $table->boolean('is_active');

            $table->foreignId('employee_id')->constrained(
                table: 'employees',
                column: 'employee_id'
            );
            $table->foreignId('position_id')->constrained(
                table: 'positions',
                column: 'position_id'
            );
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
        Schema::dropIfExists('roles');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
