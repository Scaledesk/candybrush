<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    //
    protected $table=self::TABLE;
    protected $primaryKey='candybrush_users_wallet_user_id';
    protected $fillable=['candybrush_users_wallet_amount'];
    public $timestamps=true;

    // define constants
    const TABLE = 'candybrush_users_wallet';
    const ID = 'candybrush_users_wallet_user_id';
    const AMOUNT = 'candybrush_users_wallet_amount';
    const TRANSTYPE = 'candybrush_users_wallet_transactions_type';
    const DESCRIPTON = 'candybrush_users_wallet_transactions_description';

    /*
     * relation with user
     */
    public function user(){
        return $this->belongsTo('App\User','candybrush_users_wallet_user_id');
    }
    /*
     * relation with WalletTransactions
     */
    public function transactions(){
        return $this->hasMany('App\UserWalletTransactions','candybrush_users_wallet_transactions_wallet_id');
    }

}
