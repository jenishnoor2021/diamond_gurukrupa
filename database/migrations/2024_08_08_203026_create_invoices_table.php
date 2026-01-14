<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parties_id')->unsigned()->index();
            $table->unsignedBigInteger('companies_id')->unsigned()->index();
            $table->string('invoice_no')->nullable();
            $table->string('file')->nullable();
            $table->string('invoice_date')->nullable();
            $table->string('place_to_supply')->nullable();
            $table->string('due_date')->nullable();
            $table->string('invoice_total')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
