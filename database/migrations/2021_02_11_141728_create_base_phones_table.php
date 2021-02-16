<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasePhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_phones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('phone');
            $table->string('complexes')->nullable();
            $table->string('sources')->nullable();
            $table->string('channels')->nullable();
            $table->date('first_contact_date')->nullable();
            $table->text('comments')->nullable();
            $table->integer('success_calls')->unsigned()->default(0);
            $table->integer('got_through_calls')->unsigned()->default(0);
            $table->integer('failed_calls')->unsigned()->default(0);
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
        Schema::dropIfExists('base_phones');
    }
}
