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
        Schema::create('technician_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->text('log');
            $table->text('created_dateandtime');
            $table->text('sessionid');
            $table->integer('checkin')->nullable();
            $table->integer('checkout')->nullable();
            $table->string('requested_time')->nullable();
            $table->string('accepted_time')->nullable();
            $table->string('en_rout_time')->nullable();
            $table->string('start_time')->nullable();
            $table->string('finish_time')->nullable();
            $table->date('date')->nullable();
            $table->string('workorderid')->nullable();
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
        Schema::dropIfExists('technician_logs');
    }
};
