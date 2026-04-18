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
        Schema::create('booking_quotations', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('booking_id')->nullable();
            $table->integer('workorder_id')->nullable();
            $table->string('send_date')->nullable();
            $table->string('due_date')->nullable();
            $table->string('status')->nullable();
            $table->text('notes')->nullable();
            $table->string('cancelMessage')->nullable();
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
        Schema::dropIfExists('booking_quotations');
    }
};
