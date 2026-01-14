<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartyRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('party_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parties_id')->unsigned()->index();
            $table->string('key')->nullable();
            $table->string('value')->nullable();
            $table->timestamps();

            $table->foreign('parties_id')->references('id')->on('parties')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('party_rates');
    }
}
