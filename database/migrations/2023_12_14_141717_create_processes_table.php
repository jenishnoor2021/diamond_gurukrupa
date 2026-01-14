<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dimonds_id')->unsigned()->index();
            $table->string('dimonds_barcode')->nullable();
            $table->string('designation')->nullable();
            $table->string('worker_name')->nullable();
            $table->string('issue_date')->nullable();
            $table->string('issue_weight')->nullable();
            $table->string('return_date')->nullable();
            $table->string('return_weight')->nullable();
            $table->string('price')->nullable();
            $table->string('r_shape')->nullable();
            $table->string('r_weight')->nullable();
            $table->string('r_clarity')->nullable();
            $table->string('r_color')->nullable();
            $table->string('r_cut')->nullable();
            $table->string('r_polish')->nullable();
            $table->string('r_symmetry')->nullable();
            $table->string('ratecut')->nullable();
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
        Schema::dropIfExists('processes');
    }
}
