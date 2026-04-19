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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id(); // Creates an auto-incrementing primary key named 'id'
            $table->string('reference')->nullable();
            $table->unsignedBigInteger('client'); // Field for customer ID
            $table->unsignedBigInteger('vehicle'); // Field for vehicle ID
            $table->time('due_date')->nullable();
            $table->date('booking_date')->nullable(); // Field for the booking date
            $table->time('booking_time')->nullable(); // Field for the requested time
            $table->date('requested_date')->nullable(); // Field for the booking date
            $table->time('requested_time')->nullable(); // Field for the requested time
            $table->string('service_group')->nullable(); // Field for the service group
            $table->string('status')->nullable(); // Field for the booking status
            $table->date('scheduled_date')->nullable(); // Field for the scheduled date
            $table->time('scheduled_time')->nullable(); // Field for the scheduled time
            $table->text('description')->nullable(); // Field for a description of the service, nullable
            $table->string('city')->nullable(); // Field for the city
            $table->string('country')->nullable(); // Field for the country
            $table->string('service_location')->nullable(); // Field for the service location
            $table->integer('serviceId')->nullable(); // Field for the booking date
            $table->integer('workorderId')->nullable(); // Field for the requested time
            $table->string('landmark')->nullable();
            $table->string('reasons')->nullable();
            $table->string('skill_group')->nullable();
            $table->string('source')->nullable();
            $table->string('warrantyregisterations')->nullable();
            $table->string('created_by')->nullable();
            $table->boolean('isModified')->nullable();
            $table->boolean('isDeleted')->nullable();
            $table->boolean('IsBCToPortalIntegrated')->nullable();
            $table->datetime('BCToPortalIntegratedTime')->default('1900-01-01 00:00:00');
            $table->boolean('IsPortalToBCIntegrated')->nullable();
            $table->datetime('PortalToBCIntegratedTime')->default('1900-01-01 00:00:00');
            $table->timestamps(); // Adds created_at and updated_at fields
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};