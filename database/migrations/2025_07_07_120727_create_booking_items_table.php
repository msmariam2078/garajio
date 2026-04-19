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
        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();
            $table->integer('quotation_id');
            $table->string('item_no')->nullable();
            $table->string('item_type')->nullable();
            $table->string('product_id');
            $table->string('product_name')->nullable();
            $table->string('qty')->nullable();
            $table->string('gstprice')->nullable();
            $table->double('linetotal')->nullable();
            $table->string('unit_price')->nullable();
            $table->string('warrenty')->nullable();
            $table->string('location')->nullable();
            $table->string('taxpercentage')->nullable();
            $table->decimal('taxamount')->nullable();
            $table->decimal('totalamount')->nullable();
            $table->string('uom')->nullable();
            $table->string('uom_name')->nullable();
            $table->string('description')->nullable();
            $table->integer('isWarrenty')->nullable();
            $table->string('isModified')->nullable();
            $table->string('isDeleted')->nullable();
            $table->boolean('IsBCToPortalIntegrated')->nullable();
            $table->boolean('IsPortalToBCIntegrated')->nullable();
            $table->datetime('BCToPortalIntegratedTime')->nullable();
            $table->datetime('PortalToBCIntegratedTime')->nullable();
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
        Schema::dropIfExists('booking_items');
    }
};
