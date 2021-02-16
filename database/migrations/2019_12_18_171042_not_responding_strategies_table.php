<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotRespondingStrategiesTable extends Migration
{
    public function up()
    {
        Schema::create('not_responding_strategies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('nr_count');
            $table->integer('nr_all_count');
            $table->text('slots');
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
        Schema::dropIfExists('not_responding_strategies');
    }
}