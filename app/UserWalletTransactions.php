<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserWalletTransactions extends Model
{
    //
    protected $table='candybrush_users_wallet_transactions';
    protected $primaryKey='candybrush_users_wallet_transactions_id';
    protected $fillable=['candybrush_users_wallet_transactions_description','candybrush_users_wallet_transactions_type','candybrush_users_wallet_transactions_amount'];
    public $timestamps=true;

    public function wallet(){
        return $this->belongsTo('App\UserWallet','candybrush_users_wallet_transactions_wallet_id');
    }

}
