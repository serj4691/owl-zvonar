<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScenarioAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scenario_answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('text');
            $table->boolean('need_comment')->default('0');
            $table->bigInteger('question_id')->unsigned();
            $table
                ->foreign('question_id')
                ->references('id')->on('scenario_questions')
                ->onDelete('cascade');
            $table->bigInteger('next_question_id')->unsigned()->nullable();
            $table
                ->foreign('next_question_id')
                ->references('id')->on('scenario_questions')
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
        Schema::dropIfExists('scenario_answers');
    }
}
