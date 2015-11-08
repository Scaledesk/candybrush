<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMessagesTableAddUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candybrush_messages',function(Blueprint $table){
           $table->integer("candybrush_messages_user_id")->unsigned()->after('id');
            $table->foreign('candybrush_messages_user_id','candybrush_messages_user_id_fk')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('candybrush_messages',function(Blueprint $table){
           /* $table->foreign('candybrush_messages_user_id','candybrush_messages_user_id_fk')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');*/
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
        DB::transaction(function(){
        Schema::table('candybrush_messages',function(Blueprint $table){
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $table->dropForeign('candybrush_messages_user_id_fk');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $table->dropColumn("candybrush_messages_user_id");
            });
        });
    }
}
