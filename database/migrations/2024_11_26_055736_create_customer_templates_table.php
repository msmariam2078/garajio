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
		Schema::create('customer_templates', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('code', 191)->collation('utf8mb4_unicode_ci');
			$table->text('description')->collation('utf8mb4_unicode_ci');
			$table->text('contact_type')->nullable()->collation('utf8mb4_unicode_ci');
			$table->timestamp('created_at')->nullable();
			$table->timestamp('updated_at')->nullable();
			$table->boolean('isModified')->default(0);
			$table->boolean('IsBCToPortalIntegrated')->default(0);
			$table->dateTime('BCToPortalIntegratedTime')->default('1900-01-01 00:00:00');
			$table->boolean('IsPortalToBCIntegrated')->default(0);
			$table->dateTime('PortalToBCIntegratedTime')->default('1900-01-01 00:00:00');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('customer_templates');
	}
};
