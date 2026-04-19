<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'users',
            function (Blueprint $table) {
                $table->id();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('email')->unique();
                $table->string('type')->nullable();
                $table->string('profile')->nullable();
                $table->string('phone_number')->nullable();
                $table->string('lang')->nullable();
                $table->integer('subscription')->nullable();
                $table->date('subscription_expire_date')->nullable();
                $table->integer('parent_id')->default(0);
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('client_type')->nullable();
                $table->integer('is_active')->default(1);
                $table->string('warehouse_name')->nullable();
                $table->text('fax')->nullable();
                $table->text('po_box')->nullable();
                $table->string('mobile')->nullable();
                $table->string('ccp')->nullable();
                $table->string('ccm')->nullable();
                $table->string('title')->nullable();
                $table->string('country')->nullable();
                $table->string('gst')->nullable();
                $table->string('skills')->nullable();
                $table->string('services')->nullable();
                $table->text('landmark')->nullable();
                $table->string('customer_template')->nullable();
                $table->string('shift')->nullable();
				$table->boolean('isModified')->nullable();
				$table->boolean('isDeleted')->default(0);
                $table->boolean('IsBCToPortalIntegrated')->nullable();
                $table->datetime('BCToPortalIntegratedTime')->default('1900-01-01 00:00:00');
                $table->boolean('IsPortalToBCIntegrated')->nullable();
                $table->datetime('PortalToBCIntegratedTime')->default('1900-01-01 00:00:00');
                $table->rememberToken();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
