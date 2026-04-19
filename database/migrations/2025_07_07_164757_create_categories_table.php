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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->integer('parent')->default(0);
            $table->text('description')->collation('utf8mb4_general_ci');
            $table->integer('children')->default(0);
            $table->boolean('isModified')->nullable();
            $table->boolean('isDeleted')->nullable();
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
        Schema::dropIfExists('categories');
    }
};
