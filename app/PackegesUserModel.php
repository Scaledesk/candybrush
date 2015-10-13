<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackegesUserModel extends Model
{

    //
    protected $table='candybrush_users_packages';
    protected $fillable=['candybrush_users_packages_user_id', 'candybrush_users_packages_package_id', 'candybrush_users_packages_status'];
    public $timestamps=true;
    public function packagesModel()
    {
        return $this->belongsTo('App\PackagesModel', 'candybrush_users_packages_package_id');
    }
}
