<?php

use App\libraries\Constants;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBadgeTable extends Migration
{
    const TABLE="badges";
    const TABLE_PREFIX="badges_";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Constants::PREFIX.self::TABLE,function(Blueprint $table){
         $table->increments(Constants::PREFIX.self::TABLE_PREFIX.'id');
            $table->string(Constants::PREFIX.self::TABLE_PREFIX.'name');
            $table->string(Constants::PREFIX.self::TABLE_PREFIX.'image_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(Constants::PREFIX.self::TABLE);
    }
}
