<?php

use App\libraries\Constants;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdatePackagesTableAddPhotos extends Migration
{
    const TABLE='packages_photos';
    const TABLE_PREFIX='packages_photos_';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       //Nothing
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Nothing
    }
}
