<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMessagesTableIncludeRecieversId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candybrush_messages',function(Blueprint $table){
            $table->string('candybrush_messages_receivers_user_id',500)->default('draft');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candybrush_messages',function(Blueprint $table){
            $table->dropColumn(['candybrush_messages_receivers_user_id']);
        });
    }
}
