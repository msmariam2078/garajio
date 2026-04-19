<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('inventory_setups', function (Blueprint $table) {
            $table->id();
			$table->boolean('auto')->defualt(0);
			$table->string('item_prefix')->nullable();
		    $table->string('item_number')->nullable();
			$table->boolean('hand_availability')->defualt(0)->nullable();
		
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
        Schema::dropIfExists('inventory_setups');
    }
};
