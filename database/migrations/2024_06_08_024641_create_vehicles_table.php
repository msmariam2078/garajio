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
		Schema::create('vehicles', function (Blueprint $table) {
			$table->id();

			$table->string('rego')->nullable();
			$table->integer('status')->default(1);

			$table->string('name', 51)->nullable();
			$table->string('client', 51)->nullable(); // kept as string because you had varchar(51) with collation utf8mb4_bin, adjust if you want foreign key

			$table->string('state')->nullable();

			$table->string('v_make', 51)->nullable();
			$table->string('vm', 51)->nullable();
			$table->string('vmc', 51)->nullable();

			$table->string('model_series')->nullable();
			$table->string('vin')->nullable();
			$table->string('body_type')->nullable();
			$table->string('drive_type')->nullable();

			$table->date('rego_due_date')->nullable();
			$table->date('last_in_date')->nullable();

			$table->string('service_interval')->nullable();
			$table->string('radio_pin')->nullable();
			$table->string('key_code')->nullable();
			$table->text('note')->nullable();
			$table->string('engine_number')->nullable();
			$table->string('fleet_code')->nullable();

			$table->string('vt', 51)->nullable();
			$table->string('vdt', 51)->nullable();
			$table->string('vft', 51)->nullable();

			$table->boolean('a_c')->nullable()->default(false);

			$table->string('vbt', 20)->nullable();
			$table->string('vcn', 51)->nullable();
			$table->string('vsc', 51)->nullable();

			$table->string('odometer', 25)->nullable();
			$table->decimal('hours', 10, 2)->nullable();

			$table->string('engine_code')->nullable();
			$table->string('chassis_no')->nullable();

			$table->date('build_date')->nullable();
			$table->date('prod_date')->nullable();
			$table->date('last_service')->nullable();
			$table->date('next_service')->nullable();

			$table->string('cylinders')->nullable();
			$table->string('liters')->nullable();
			$table->string('fuel_induction')->nullable();
			$table->string('fuel_type')->nullable();
			$table->string('tare_mass')->nullable();
			$table->string('tire_size')->nullable();
			$table->string('imported_id')->nullable();

			$table->integer('parent_id')->default(0);

			$table->boolean('isModified')->nullable();
			$table->boolean('isDeleted')->nullable();
			$table->boolean('IsBCToPortalIntegrated')->nullable();
			$table->dateTime('BCToPortalIntegratedTime')->default('1900-01-01 00:00:00');
			$table->boolean('IsPortalToBCIntegrated')->nullable();
			$table->dateTime('PortalToBCIntegratedTime')->default('1900-01-01 00:00:00');

			$table->integer('rs_id')->nullable();
			$table->integer('es_id')->nullable();

			$table->string('uom')->nullable();

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
		Schema::dropIfExists('vehicles');
	}
};
