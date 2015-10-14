<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMessageUsersTableDeleteMessageStatusFromMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('candybrush_messages_user');
        Schema::create('candybrush_messages_receivers',function(Blueprint $table){
            $table->increments('id');
            $table->integer('candybrush_messages_recievers_user_id')->unsigned();
            $table->integer('candybrush_messages_recievers_message_id')->unsigned();
            $table->enum('candybrush_messages_recievers_message_status',['0','1'])->default('0');
            $table->timestamps();
        });

        Schema::table('candybrush_messages_receivers',function(Blueprint $table){
            $table->foreign('candybrush_messages_recievers_user_id','candybrush_messages_recievers_user_id_fk')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('candybrush_messages_recievers_message_id','candybrush_messages_recievers_message_id_fk')->references('id')->on('candybrush_messages')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('candybrush_messages_user');
        Schema::create('candybrush_messages_user', function ($table) {
            $table->increments('id');
            $table->integer('candybrush_messages_user_id');
            $table->integer('candybrush_messages_message_id');
            $table->string('candybrush_messages_message_type',20);
        });

    }
}
