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
        Schema::create('client_details', function (Blueprint $table) {
            $table->id();

            $table->integer('client_id')->default(0);
            $table->integer('user_id')->default(0);

            $table->string('company')->nullable();

            $table->text('service_address')->nullable();
            $table->string('service_city')->nullable();
            $table->string('service_state')->nullable();
            $table->string('service_country')->nullable();
            $table->string('service_zip_code')->nullable();

            $table->text('billing_address')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_zip_code')->nullable();

            $table->text('addresses')->nullable();
            $table->decimal('credit_limit', 15, 2)->nullable();

            $table->string('payment_terms_code')->nullable();
            $table->string('payment_method_code')->nullable();
            $table->string('vat_bus_posting_group')->nullable();
            $table->string('customer_posting_group')->nullable();
            $table->string('gen_bus_posting_group')->nullable();

            $table->integer('parent_id')->nullable();

            $table->timestamps(); // created_at & updated_at

            $table->string('type')->default('fix');
            $table->string('virtual')->nullable();

            $table->integer('isDeleted')->nullable();

            $table->text('trading_name')->nullable();
            $table->text('note_customer')->nullable();
            $table->text('note_contact')->nullable();
            $table->text('business_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_details');
    }
};