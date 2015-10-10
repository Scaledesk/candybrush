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
    const PORTFOLIO_FILE_TYPE = 'candybrush_users_portfolio_file_type';

    protected $table = self::TABLE;

    protected $fillable = [self::USER_ID, self::PORTFOLIO_DESCRIPTION, self::PORTFOLIO_FILE, self::PORTFOLIO_FILE_TYPE];

    public function user()
    {
        return $this->belongsTo('App\User', 'id');
    }
}
