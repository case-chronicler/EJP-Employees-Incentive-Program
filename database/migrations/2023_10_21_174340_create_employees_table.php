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
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->string('employee_public_ref')->constrained('invites', 'invite_link_ref');
            $table->decimal('balance')->default(0.00);
            $table->enum('status', ['on_probation', 'fully_active'])->default('fully_active');
            $table->timestamps();
            $table->foreignId('user_id')->unique()->constrained(
                table: 'users', column: 'user_id'
            );
            $table->boolean('has_elevated_permission')->comment('Only employees whose role includes the position of an attorney has an elevated permission')->default(false);

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
        Schema::dropIfExists('employees');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
