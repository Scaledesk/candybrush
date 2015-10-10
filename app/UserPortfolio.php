<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class UserPortfolio extends Model
{
    // defining constant
    const TABLE = 'users_portfolio';
    const USER_ID = 'candybrush_users_portfolio_user_id';
    const PORTFOLIO_DESCRIPTION = 'candybrush_users_portfolio_description';
    const PORTFOLIO_FILE = 'candybrush_users_portfolio_file';

    protected $table = self::TABLE;

    protected $fillable = [self::USER_ID, self::PORTFOLIO_DESCRIPTION, self::PORTFOLIO_FILE];

    public function user()
    {
        return $this->belongsTo('App\User', 'id');
    }
}
