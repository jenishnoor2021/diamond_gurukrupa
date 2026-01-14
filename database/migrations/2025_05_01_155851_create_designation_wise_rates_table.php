<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesignationWiseRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designation_wise_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('designation_id');
            $table->string('range_key');
            $table->decimal('value', 10, 2); // Adjust precision/scale if needed
            $table->timestamps();

            $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('designation_wise_rates');
    }
}
