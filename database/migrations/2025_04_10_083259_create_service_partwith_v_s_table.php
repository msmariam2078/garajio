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
        Schema::create('service_partwith_v_s', function (Blueprint $table) {
			$table->id();
            $table->integer('service_part_id');
            $table->integer('v_make');
            $table->integer('v_model');
            $table->text('yom');
            $table->string('engine_spec', 191);
            $table->integer('status')->nullable();
            $table->integer('isModified')->nullable();
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
        Schema::dropIfExists('service_partwith_v_s');
    }
};
