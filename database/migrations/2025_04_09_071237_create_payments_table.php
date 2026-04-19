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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->date('payment_date')->nullable();
            $table->integer('client')->default(0);
            $table->text('invoice');
            $table->string('workorder')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('paid_amount')->nullable();
            $table->string('status', 191)->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('isModified')->nullable();
            $table->boolean('isDeleted')->nullable();
            $table->boolean('IsBCToPortalIntegrated')->nullable();
            $table->dateTime('BCToPortalIntegratedTime')->default('1900-01-01 00:00:00');
            $table->boolean('IsPortalToBCIntegrated')->nullable();
            $table->dateTime('PortalToBCIntegratedTime')->default('1900-01-01 00:00:00');
            $table->string('created_by')->nullable();
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
        Schema::dropIfExists('payments');
    }
};
