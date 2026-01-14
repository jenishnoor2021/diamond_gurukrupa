<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDimondsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dimonds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parties_id')->unsigned()->index();
            $table->string('dimond_name')->nullable();
            $table->integer('year')->nullable();
            $table->string('month')->nullable();
            $table->string('series_no')->nullable();
            $table->string('janger_no')->nullable();
            $table->string('shape')->nullable();
            $table->string('weight')->nullable();
            $table->string('required_weight')->nullable();
            $table->string('clarity')->nullable();
            $table->string('color')->nullable();
            $table->string('cut')->nullable();
            $table->string('polish')->nullable();
            $table->string('symmetry')->nullable();
            $table->text('barcode')->nullable();
            $table->string('barcode_number')->nullable();
            $table->string('status')->nullable();
            $table->string('amount')->nullable();
            $table->string('delevery_date')->nullable();
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
        Schema::dropIfExists('dimonds');
    }
}
