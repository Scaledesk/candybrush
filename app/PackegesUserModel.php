<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackegesUserModel extends Model
{

    //

    protected $fillable=['candybrush_users_packages_user_id', 'candybrush_users_packages_package_id', 'candybrush_users_packages_status'];
    public function packagesModel()
    {
        return $this->belongsTo('App\PackagesModel', 'candybrush_users_packages_package_id');
    }
}
