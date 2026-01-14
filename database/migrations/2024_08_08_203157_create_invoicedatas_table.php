<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicedatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoicedatas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id')->unsigned()->index();
            $table->string('item')->nullable();
            $table->string('hsn_no')->nullable();
            $table->string('tax')->nullable();
            $table->string('quntity')->nullable();
            $table->string('rate')->nullable();
            $table->string('per')->nullable();
            $table->string('total_amount')->nullable();
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoicedatas');
    }
}
