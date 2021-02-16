<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplexesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complexes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('phone')->nullable();

            $table->biginteger('budjet0')->nullable();
            $table->biginteger('budjet1')->nullable();
            $table->biginteger('budjet2')->nullable();
            $table->biginteger('budjet3')->nullable();
            $table->biginteger('budjet4')->nullable();
            $table->biginteger('budjet0_max')->nullable();
            $table->biginteger('budjet1_max')->nullable();
            $table->biginteger('budjet2_max')->nullable();
            $table->biginteger('budjet3_max')->nullable();
            $table->biginteger('budjet4_max')->nullable();

            $table->string('region')->nullable();

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
        Schema::dropIfExists('complexes');
    }
}
