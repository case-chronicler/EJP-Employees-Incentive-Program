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
        Schema::create('incentives_gift', function (Blueprint $table) {
            $table->id("incentives_gift_id");
            $table->string('incentives_gift_type_id')->unique();
            $table->enum('incentives_gift_type', ['group', 'individual']);
            $table->decimal('amount')->default(0.00);
            $table->string('gift_quantity');
            $table->string('note');
            $table->timestamps();
            $table->foreignId('incentive_id')->constrained('incentives', 'incentive_id');
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
        Schema::dropIfExists('incentives_gift');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
