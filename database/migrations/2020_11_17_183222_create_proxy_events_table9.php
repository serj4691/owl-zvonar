<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProxyEventsTable9 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lead_uploads', function (Blueprint $table) {
            $table->string('name')->nullable();
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->bigInteger('base_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lead_uploads', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('base_id');
        });
    }
}
