<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('candybrush_messages_user', function ($table) {
            $table->increments('id');
            $table->integer('candybrush_messages_user_id');
            $table->integer('candybrush_messages_message_id');
            $table->string('candybrush_messages_message_type',20);
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
        Schema::Drop('candybrush_messages_user');

    }
}
