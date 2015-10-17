<?php

use App\libraries\Constants;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonusTable extends Migration
{
    const TABLE_PREFIX='bonus_';
    const TABLE='bonus';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Constants::PREFIX.self::TABLE,function(Blueprint $table){
           $table->increments(Constants::PREFIX.self::TABLE_PREFIX.'id');
            $table->string(Constants::PREFIX.self::TABLE_PREFIX.'name',80);
            $table->string(Constants::PREFIX.self::TABLE_PREFIX.'description');
            $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'package_id')->unsigned();
        });
        Schema::table(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            $table->foreign(Constants::PREFIX.self::TABLE_PREFIX.'package_id',Constants::PREFIX.self::TABLE_PREFIX.'package_id_fk')->references('id')->on(Constants::PREFIX.'packages')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('candybrush_packages',function(Blueprint $table){
            $table->dropColumn(['candybrush_packages_bonus']);
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
        Schema::drop(Constants::PREFIX.self::TABLE);
        Schema::table('candybrush_packages',function(Blueprint $table){
            $table->string('candybrush_packages_bonus');
        });
    }
}
