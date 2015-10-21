<?php

use App\libraries\Constants;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestFeatureTable extends Migration
{
    const TABLE='request_features';
    const TABLE_PREFIX='request_features_';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            $table->increments(Constants::PREFIX.self::TABLE_PREFIX.'id');
            $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'user_id')->unsigned();
            $table->string(Constants::PREFIX.self::TABLE_PREFIX.'title',80);
            $table->string(Constants::PREFIX.self::TABLE_PREFIX.'description');
            $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'budget');
            $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'category_id')->unsigned();
        });
        Schema::table(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            $table->foreign(Constants::PREFIX.self::TABLE_PREFIX.'user_id',Constants::PREFIX.self::TABLE_PREFIX.'user_id_fk')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign(Constants::PREFIX.self::TABLE_PREFIX.'category_id',Constants::PREFIX.self::TABLE_PREFIX.'category_id_fk')->references('candybrush_categories_id')->on('candybrush_categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $table->dropForeign(Constants::PREFIX.self::TABLE_PREFIX.'category_id_fk');
            $table->dropForeign(Constants::PREFIX.self::TABLE_PREFIX.'user_id_fk');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        });
        Schema::drop(Constants::PREFIX.self::TABLE);
    }
}
