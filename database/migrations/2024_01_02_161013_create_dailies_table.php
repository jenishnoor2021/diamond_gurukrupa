<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dailies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dimonds_id')->unsigned()->index();
            $table->string('barcode')->nullable();
            $table->string('stage')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();

            $table->foreign('dimonds_id')->references('id')->on('dimonds')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dailies');
    }
}
