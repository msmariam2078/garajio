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
        Schema::create('service_partadjust_ments', function (Blueprint $table) {
            $table->id();
            $table->string('warehouse_id')->nullable();
            $table->integer('service_part_id');
            $table->integer('unavailable')->nullable();
            $table->integer('commited')->nullable();
            $table->integer('available')->nullable();
            $table->integer('onhand')->nullable();
            $table->boolean('isModified')->nullable();
            $table->boolean('isDeleted')->nullable();
            $table->boolean('IsBCToPortalIntegrated')->nullable();;
            $table->boolean('IsPortalToBCIntegrated')->nullable();
            $table->datetime('BCToPortalIntegratedTime')->default('1900-01-01 00:00:00');
            $table->datetime('PortalToBCIntegratedTime')->default('1900-01-01 00:00:00');
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
        Schema::dropIfExists('service_partadjust_ments');
    }
};
