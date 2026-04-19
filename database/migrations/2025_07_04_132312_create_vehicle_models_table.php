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
		Schema::create('vehicle_models', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('make_id')->nullable();
            $table->text('model_name');
            $table->boolean('isModified')->nullable();
            $table->integer('isDeleted')->nullable();
            $table->boolean('IsBCToPortalIntegrated')->nullable();
            $table->dateTime('BCToPortalIntegratedTime')->default('1900-01-01 00:00:00');
            $table->boolean('IsPortalToBCIntegrated')->nullable();
            $table->dateTime('PortalToBCIntegratedTime')->default('1900-01-01 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_models');
    }
};
