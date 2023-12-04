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
        Schema::create('incentives', function (Blueprint $table) {
            $table->id('incentive_id');
            $table->string('name');
            $table->string('icon_name');
            $table->decimal('amount_per_item');
            $table->enum('type', ['group', 'individual']);
            $table->timestamps();
        });

        DB::table('incentives')->insert([
            'name' => 'Coffee',
            'icon_name' => 'coffee',
            'amount_per_item' => 5.00,
            'type' => 'individual',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('incentives')->insert([
            'name' => 'Cupcake',
            'icon_name' => 'cupcake',
            'amount_per_item' => 2.50,
            'type' => 'individual',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('incentives')->insert([
            'name' => 'Flower',
            'icon_name' => 'flower',
            'amount_per_item' => 25.00,
            'type' => 'individual',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('incentives')->insert([
            'name' => 'Silver Pen',
            'icon_name' => 'silver_pen',
            'amount_per_item' => 10.00,
            'type' => 'individual',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('incentives')->insert([
            'name' => 'Pizza Party',
            'icon_name' => 'pizza',
            'amount_per_item' => 0.00,
            'type' => 'group',
            'created_at' => now(),
            'updated_at' => now()
        ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incentives');
    }
};
