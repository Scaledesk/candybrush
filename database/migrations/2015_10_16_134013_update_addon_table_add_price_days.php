<?php

use App\libraries\Constants;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAddonTableAddPriceDays extends Migration
{
    const TABLE_PREFIX = 'addons_';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table(Constants::PREFIX.'addons',function(Blueprint $table){
            $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'price');
            $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'days');
        });
        Schema::table('candybrush_packages',function(Blueprint $table){
            $table->dropColumn(['candybrush_packages_offer']);
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
            $table->dropColumn([
                Constants::PREFIX.self::TABLE_PREFIX.'price',
                Constants::PREFIX.self::TABLE_PREFIX.'days']);
        });
        Schema::table('candybrush_packages',function(Blueprint $table){
            $table->string('candybrush_packages_offer');
        });
    }
}
