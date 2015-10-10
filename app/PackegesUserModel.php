<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackegesUserModel extends Model
{

    //


    public function packagesModel()
    {
        return $this->belongsTo('App\PackagesModel', 'id');
    }
}
