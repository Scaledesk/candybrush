<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('candybrush_messages', function ($table) {
            $table->increments('id');
            $table->string('candybrush_messages_subject');
            $table->string('candybrush_messages_body', 2000);
            $table->integer('candybrush_messages_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('candybrush_messages');
    }
}
