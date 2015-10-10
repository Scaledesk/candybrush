<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateWalletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('candybrush_users_wallet',function(Blueprint $table){
           $table->integer('candybrush_users_wallet_user_id',false,true);
            $table->integer('candybrush_users_wallet_amount',false);
            $table->primary('candybrush_users_wallet_user_id','users_wallet_user_id_pk');
        });
        Schema::table('candybrush_users_wallet',function(Blueprint $table){
            $table->foreign('candybrush_users_wallet_user_id','users_wallet_user_id_fk')->references('id')->on('users');
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
        Schema::drop('candybrush_users_wallet');
    }
}
