<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('lead_id')->unsigned();
            $table->integer('priority');
            $table->datetime('processing_at');
            $table
                ->foreign('lead_id')
                ->references('id')->on('leads')
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
        Schema::dropIfExists('lead_tasks');
    }
}
