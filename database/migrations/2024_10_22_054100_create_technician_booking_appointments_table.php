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
        Schema::create('technician_booking_appointments', function (Blueprint $table) {
            $table->id();
            $table->integer("workorder_id");
            $table->integer("technician_id");
            $table->date("from_date");
            $table->time("from_time");
            $table->date("to_date")->nullable();
            $table->time("to_time")->nullable();
            $table->integer("status")->default(0)->nullable();
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
        Schema::dropIfExists('technician_booking_appointments');
    }
};
