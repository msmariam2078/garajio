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
       Schema::create('work_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('created_date');
            $table->string('subject', 255)->nullable();
            $table->integer('customer_id');
            $table->string('service_group', 255);
            $table->string('service_location', 255)->nullable();
            $table->enum('inspection', ['yes', 'no']);
            $table->decimal('effort_hours', 10, 2)->nullable();
            $table->string('status', 21)->nullable();
            $table->text('vehicle')->nullable();
            $table->text('booking')->nullable();
            $table->text('inspections')->nullable();
            $table->text('technician')->nullable();
            $table->text('invoice')->nullable();
            $table->text('payment')->nullable();
            $table->integer('parent_id')->nullable();
            $table->text('service_lat');
            $table->text('service_lng');
            $table->integer('allocation_status')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->string('notes', 500)->nullable();
            $table->string('reasons', 255)->nullable();
            $table->string('allocation_date', 255)->nullable();
            $table->tinyInteger('isModified')->nullable();
            $table->tinyInteger('isDeleted')->nullable();
            $table->tinyInteger('IsBCToPortalIntegrated')->nullable();
            $table->dateTime('BCToPortalIntegratedTime')->default('1900-01-01 00:00:00');
            $table->tinyInteger('IsPortalToBCIntegrated')->nullable();
            $table->dateTime('PortalToBCIntegratedTime')->default('1900-01-01 00:00:00');
            $table->string('created_by', 191)->nullable();
            $table->string('warranty', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_orders');
    }
};
