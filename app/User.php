<?php

namespace App;
use Bican\Roles\Traits\HasRoleAndPermission;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword/*, HasRoleAndPermission*/;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password','confirmation_code','confirmed'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /*
     * relation with profile
     */
    public function userProfiles(){
        return $this->hasOne('App\UserProfile','candybrush_users_profiles_users_id');
    }
    /*
     * relation with UserWallet
     */
    public function userWallet(){
        return $this->hasOne('App\UserWallet','candybrush_users_wallet_user_id');
    }
    /*
     * relation with wallettransactions
     */
    public function transactions(){
        return $this->hasManyThrough('UserWalletTransactions','UserWallet','candybrush_users_wallet_user_id','candybrush_users_wallet_transactions_wallet_id');
    }
    public function userPortfolio()
    {
        return $this->hasMany('App\UserPortfolio', 'candybrush_users_portfolio_user_id');
    }
    public function messages()
    {
        return $this->hasone('App\Message', 'candybrush_messages_user_id');
    }
    public function recieversMessage()
    {
        return $this->belongsToMany('App\Message', 'candybrush_messages_receivers','candybrush_messages_recievers_user_id','candybrush_messages_recievers_message_id')->withTimestamps();
    }
    public function packages(){
        return $this->hasMany('App\PackagesModel', 'candybrush_packages_user_id');
    }
    public function bookings(){
        return $this->hasMany('App\Booking','candybrush_bookings_user_id');
    }
    public function requestFeatures(){
        return $this->hasMany('App\RequestFeature','candybrush_request_features_user_id');
    }
}