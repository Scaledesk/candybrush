<?php
use App\libraries\Constants;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateAddonTable extends Migration
{
    const TABLE_PREFIX='addons_';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Constants::PREFIX.'addons',function(Blueprint $table){
           $table->increments(Constants::PREFIX.self::TABLE_PREFIX.'id');
            $table->string(Constants::PREFIX.self::TABLE_PREFIX.'name',80);
            $table->string(Constants::PREFIX.self::TABLE_PREFIX.'description');
            $table->string( Constants::PREFIX.self::TABLE_PREFIX.'package_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop(Constants::PREFIX.'addons');
    }
}
