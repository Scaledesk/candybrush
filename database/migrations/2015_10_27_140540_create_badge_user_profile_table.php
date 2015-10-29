<?php

use App\libraries\Constants;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateBadgeUserProfileTable extends Migration
{
    const TABLE="badges_users";
    const TABLE_PREFIX="badges_users_";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function(){
            Schema::create(Constants::PREFIX.self::TABLE,function(Blueprint $table){
                $table->increments(Constants::PREFIX.self::TABLE_PREFIX.'id');
                $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'badge_id')->unsigned();
                $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'users_id')->unsigned();
            });
            Schema::table(Constants::PREFIX.self::TABLE,function(Blueprint $table){
//                $table->foreign(Constants::PREFIX.self::TABLE_PREFIX.'users_profiles_id',Constants::PREFIX.self::TABLE_PREFIX.'users_profiles_id_fk')->references('id')->on('users_profiles')->onDelete('cascade')->onUpdate('cascade');
                DB::statement('ALTER TABLE candybrush_badges_users ADD CONSTRAINT fk_u FOREIGN KEY (candybrush_badges_users_users_id) REFERENCES users(id) ON UPDATE CASCADE
ON DELETE CASCADE');
//                $table->foreign(Constants::PREFIX.self::TABLE_PREFIX.'badge_id',Constants::PREFIX.self::TABLE_PREFIX.'badge_id_fk')->references('candybrush_badges_id')->on('candybrush_badges')->onDelete('cascade')->onUpdate('cascade');
                DB::statement('ALTER TABLE candybrush_badges_users ADD CONSTRAINT fk_b FOREIGN KEY (candybrush_badges_users_badge_id) REFERENCES candybrush_badges(candybrush_badges_id) ON UPDATE CASCADE
ON DELETE CASCADE');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::transaction(function(){
            Schema::table(Constants::PREFIX.self::TABLE,function(Blueprint $table){
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                $table->dropForeign('fk_u');
                $table->dropForeign('fk_b');
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            });
            Schema::drop(Constants::PREFIX.self::TABLE);
        });
    }
}
