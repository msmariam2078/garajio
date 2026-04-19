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
        Schema::create('engine_specs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('vehicle_id')->nullable();
            $table->integer('make_id');
            $table->integer('model_id');
            $table->string('enginespecs');
            
            $table->boolean('isModified')->nullable();
            $table->integer('isDeleted')->nullable(); // Matches SQL definition
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
        Schema::dropIfExists('engine_specs');
    }
};
