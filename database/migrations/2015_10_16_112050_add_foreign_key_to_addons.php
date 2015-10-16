<?php

use App\libraries\Constants;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToAddons extends Migration
{
    const TABLE_PREFIX='addons_';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(Constants::PREFIX.'addons',function(Blueprint $table){
            $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'package_id')->unsigned()->change();
        });
        Schema::table(Constants::PREFIX.'addons',function(Blueprint $table){
            $table->foreign('candybrush_addons_package_id','candybrush_addons_package_id_fk')->references('id')->on('candybrush_packages')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::table(Constants::PREFIX.'addons',function(Blueprint $table){
            $table->dropForeign(Constants::PREFIX.self::TABLE_PREFIX.'package_id_fk');
        });
    }
}
