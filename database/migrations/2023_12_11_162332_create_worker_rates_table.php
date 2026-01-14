<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worker_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workers_id')->unsigned()->index();
            $table->string('key')->nullable();
            $table->string('value')->nullable();
            $table->timestamps();

            $table->foreign('workers_id')->references('id')->on('workers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('worker_rates');
    }
}
