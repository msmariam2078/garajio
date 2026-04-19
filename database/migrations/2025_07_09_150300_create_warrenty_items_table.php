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
        Schema::create('warrenty_items', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('product_id');
            $table->date('repair_date')->nullable();
            $table->date('claim_date')->nullable();
            $table->text('issued_description')->nullable();
            $table->string('issue_proof')->nullable();
            $table->integer('status')->nullable();
            $table->string('service_center')->nullable();
            $table->text('repair_solution')->nullable();
            $table->text('claim_solution')->nullable();
            $table->text('notes')->nullable();
            $table->integer('warranty_registration_id')->nullable();
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
        Schema::dropIfExists('warrenty_items');
    }
};
