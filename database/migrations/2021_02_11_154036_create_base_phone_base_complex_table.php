<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasePhoneBaseComplexTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_phone_base_complex', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('base_phone_id');
            $table->unsignedBigInteger('base_complex_id');

            $table->foreign('base_phone_id')->references('id')->on('base_phones');
            $table->foreign('base_complex_id')->references('id')->on('base_complexes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('base_phone_base_complex');
    }
}
