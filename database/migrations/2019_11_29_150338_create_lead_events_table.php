<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('lead_id')->unsigned();
            $table->bigInteger('call_id')->nullable();
            $table->bigInteger('operator_id')->unsigned()->nullable();
            $table->string('record_url')->nullable();
            $table->text('comment')->nullable();
            $table->string('status');
            $table->datetime('dt_recall')->nullable();
            $table
                ->foreign('lead_id')
                ->references('id')->on('leads')
                ->onDelete('cascade');
            $table
                ->foreign('operator_id')
                ->references('id')->on('operators')
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
        Schema::dropIfExists('lead_events');
    }
}
