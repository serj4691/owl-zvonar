<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProxyEventsTable10 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('leads', function (Blueprint $table) {
            $table->integer('rooms')->nullable();
            $table->string('price_from')->nullable();
            $table->string('price_to')->nullable();
            $table->string('location')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('rooms');
            $table->dropColumn('price_from');
            $table->dropColumn('price_to');
            $table->dropColumn('location');
        });
    }
}
