<?php

use App\libraries\Constants;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestFeaturesTagsTable extends Migration
{
    const TABLE='request_features_tags';
    const TABLE_PREFIX='request_features_tags_';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            $table->increments(Constants::PREFIX.self::TABLE_PREFIX.'id');
            $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'request_feature_id')->unsigned();
            $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'tag_id')->unsigned();
            $table->timestamps();
        });
        Schema::table(Constants::PREFIX.self::TABLE,function(Blueprint $table){
//            $table->foreign(Constants::PREFIX.self::TABLE_PREFIX.'r_f_id',Constants::PREFIX.self::TABLE_PREFIX.'r_f_id_fk')->references('candybrush_request_features_id')->on('candybrush_request_features')->onDelete('cascade')->onUpdate('cascade');
            DB::statement('ALTER TABLE candybrush_request_features_tags ADD CONSTRAINT fk_r_f FOREIGN KEY (candybrush_request_features_tags_request_feature_id) REFERENCES candybrush_request_features(candybrush_request_features_id)');
            $table->foreign(Constants::PREFIX.self::TABLE_PREFIX.'tag_id',Constants::PREFIX.self::TABLE_PREFIX.'tag_id_fk')->references('candybrush_tags_id')->on('candybrush_tags')->onDelete('cascade')->onUpdate('cascade');
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
            $table->dropForeign('fk_r_f');
            $table->dropForeign(Constants::PREFIX.self::TABLE_PREFIX.'tag_id_fk');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        });
        Schema::drop(Constants::PREFIX.self::TABLE);
    }
}
