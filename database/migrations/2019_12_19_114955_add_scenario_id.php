<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScenarioId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('callcenters', function (Blueprint $table) {
            $table->bigInteger('scenario_id')->unsigned()->nullable();
            $table
                ->foreign('scenario_id')
                ->references('id')->on('scenarios')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('callcenters', function (Blueprint $table) {
            $table->dropForeign(['scenario_id']);
            $table->dropColumn('scenario_id');
        });
    }
}
