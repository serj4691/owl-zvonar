<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScenarioQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scenario_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('text');
            $table->bigInteger('scenario_id')->unsigned();
            $table
                ->foreign('scenario_id')
                ->references('id')->on('scenarios')
                ->onDelete('cascade');
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
        Schema::dropIfExists('scenario_questions');
    }
}
