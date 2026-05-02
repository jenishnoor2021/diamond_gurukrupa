<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsKpToDimondsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dimonds', function (Blueprint $table) {
            $table->boolean('is_kp')->default(0)->after('status')->comment('Mark diamond as KP (Keep Private)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dimonds', function (Blueprint $table) {
            $table->dropColumn('is_kp');
        });
    }
}
