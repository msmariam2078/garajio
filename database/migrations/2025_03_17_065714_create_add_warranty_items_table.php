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
        Schema::create('add_warranty_items', function (Blueprint $table) {
            $table->id();
            $table->integer('workorderid');
            $table->integer('product_id');
            $table->string('warrantynumber');
            $table->string('from_date');
            $table->string('to_date');
            $table->string('booking_item_id')->nullable();
            $table->string('wreg_id')->nullable();
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
        Schema::dropIfExists('add_warranty_items');
    }
};
