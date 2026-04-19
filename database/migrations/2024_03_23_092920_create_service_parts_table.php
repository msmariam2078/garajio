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
        Schema::create('service_parts', function (Blueprint $table) {
            $table->id();
            $table->string('item_no')->nullable();
            $table->string('item_type')->nullable();
            $table->string('product_name')->nullable();
            $table->text('description')->nullable();
            $table->string('item_group')->nullable();
            $table->string('category')->nullable();
            $table->string('type')->nullable();
            $table->integer('qty_on_hand')->default(0);
            $table->string('uom')->nullable();
            $table->decimal('sales_price', 15, 2)->default(0.00);
            $table->decimal('price', 15, 2)->default(0.00);
            $table->decimal('tax', 5, 2)->default(0.00);
            $table->string('image')->nullable();
            $table->string('warrenty')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('pri')->nullable();
            $table->string('parent_id')->nullable();
            $table->string('warehouse_id')->nullable();
            $table->integer('discount_percentage')->nullable();
            $table->integer('v_make')->nullable();
            $table->integer('v_model')->nullable();
            $table->string('brand')->nullable();
            $table->string('origin')->nullable();
            $table->string('reference_type')->nullable();
            $table->string('reference_number')->nullable();
            $table->boolean('isModified')->nullable();
            $table->boolean('isDeleted')->nullable();
            $table->boolean('IsBCToPortalIntegrated')->nullable();
            $table->datetime('BCToPortalIntegratedTime')->nullable();
            $table->boolean('IsPortalToBCIntegrated')->nullable();
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
        Schema::dropIfExists('service_parts');
    }
};
