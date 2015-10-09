<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    //
    protected $table='candybrush_users_wallet';
    protected $primaryKey='candybrush_users_wallet_user_id';
    protected $fillable=['candybrush_users_wallet_amount'];
    public $timestamps=true;

    /*
     * relation with user
     */
    public function user(){
        return $this->belongsTo('App\User','candybrush_users_wallet_user_id');
    }
    /*
     * relation with WalletTransactions
     */
    public function transaction(){
        return $this->hasMany('App\UserWalletTransactions','candybrush_users_wallet_transactions_wallet_id');
    }

}
