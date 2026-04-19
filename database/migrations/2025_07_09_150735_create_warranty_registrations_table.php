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
        Schema::create('warranty_registrations', function (Blueprint $table) {
            $table->id();
            $table->integer('work_order_id');
            $table->integer('product_id');
            $table->integer('customer_id')->nullable();
            $table->date('warranty_start_date');
            $table->date('warranty_end_date')->nullable();
            $table->string('vehicle')->nullable();
            $table->string('status');
            $table->integer('warranty_period');
            $table->string('claim_count')->nullable();
            $table->string('jump_start')->nullable();
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
        Schema::dropIfExists('warranty_registrations');
    }
};
