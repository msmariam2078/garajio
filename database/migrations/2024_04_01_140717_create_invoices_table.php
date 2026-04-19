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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('client')->default(0);
            $table->integer('wo_id')->default(0);
            $table->date('invoice_date')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('invoice_id')->default(0);
            $table->float('total', 8, 2)->default(0);
            $table->float('discount', 8, 2)->default(0);
            $table->float('final_amount', 8, 2)->nullable();
            $table->string('status')->nullable();
            $table->integer('parent_id')->default(0);
            $table->text('notes')->nullable();
            $table->date('post_date')->nullable();
            $table->date('followup_date')->nullable();
            $table->string('invoice_type')->nullable();
            $table->boolean('account_type')->default(0);
            $table->string('odometer')->nullable();
            $table->string('hours')->nullable();
            $table->string('next_service_kms')->nullable();
            $table->string('job_status_comment')->nullable();
            $table->string('customer_source')->nullable();
            $table->string('payment_terms')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
