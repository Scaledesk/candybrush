<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateWalletTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('candybrush_users_wallet_transactions',function(Blueprint $table){
            $table->Integer('candybrush_users_wallet_transactions_id',true);
            $table->integer('candybrush_users_wallet_transactions_wallet_id',false,true);
            $table->string('candybrush_users_wallet_transactions_description',100);
            $table->enum('candybrush_users_wallet_transactions_type',['credit','debit']);
            $table->integer('candybrush_users_wallet_transactions_amount',false,true);
            $table->timestamps();
        });
      //  DB::statement('ALTER TABLE candybrush_users_wallet_transactions ADD PRIMARY KEY (candybrush_users_wallet_transactions_id)');
        Schema::table('candybrush_users_wallet_transactions',function(Blueprint $table){
            $table->foreign('candybrush_users_wallet_transactions_wallet_id','candybrush_users_wallet_transactions_wallet_id_fk')->references('candybrush_users_wallet_user_id')->on('candybrush_users_wallet');
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
        Schema::drop('candybrush_users_wallet_transactions');
    }
}
