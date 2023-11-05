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
        Schema::create('incentives_gift_transfer', function (Blueprint $table) {
            $table->id("incentives_gift_transfer_id");
            $table->timestamps();
            $table->decimal('amount');
            $table->foreignId('to_employee_id')->constrained('employees', 'employee_id');
            $table->foreignId('from_employee_id')->constrained('employees', 'employee_id');        
            $table->string('incentives_gift_type_id');
 
            $table->foreign('incentives_gift_type_id')->references('incentives_gift_type_id')->on('incentives_gift');
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
        Schema::dropIfExists('incentives_gift_transfer');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
