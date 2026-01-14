<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->text('address')->nullable();
            $table->string('designation')->nullable();
            $table->string('mobile')->nullable();
            $table->string('aadhar_no')->nullable();
            $table->string('is_active')->default(1)->nullable();
            $table->string('bank_name')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('account_no')->nullable();
            $table->text('remark')->nullable();
            $table->string('account_holder_name')->nullable();
            // $table->string('round_1')->default(0);
            // $table->string('round_2')->default(0);
            // $table->string('round_3')->default(0);
            // $table->string('fancy_0')->default(0);
            // $table->string('fancy_1')->default(0);
            // $table->string('fancy_2')->default(0);
            // $table->string('fancy_3')->default(0);
            // $table->string('fancy_4')->default(0);
            // $table->string('fancy_5')->default(0);
            // $table->string('fancy_6')->default(0);
            // $table->string('fancy_7')->default(0);
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
        Schema::dropIfExists('workers');
    }
}
