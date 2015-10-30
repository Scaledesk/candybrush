<?php

use App\libraries\Constants;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateReferralTable extends Migration
{
    const TABLE="referrals";
    const TABLE_PREFIX="referrals_";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            $table->increments(Constants::PREFIX.self::TABLE_PREFIX.'id');
            $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'users_id')->unsigned();
            $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'referred_users_id')->unsigned();
            $table->string(Constants::PREFIX.self::TABLE_PREFIX.'referred_users_email');
            $table->string(Constants::PREFIX.self::TABLE_PREFIX.'referral_code');
        });
        Schema::table(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            DB::statement('ALTER TABLE candybrush_referrals ADD CONSTRAINT fk_u1 FOREIGN KEY (candybrush_referrals_users_id) REFERENCES users(id) ON UPDATE CASCADE
ON DELETE CASCADE');
            DB::statement('ALTER TABLE candybrush_referrals ADD CONSTRAINT fk_r_u FOREIGN KEY (candybrush_referrals_referred_users_id) REFERENCES users(id) ON UPDATE CASCADE
ON DELETE CASCADE');
            DB::statement('ALTER TABLE candybrush_referrals ADD CONSTRAINT fk_r_u_e FOREIGN KEY (candybrush_referrals_referred_users_email) REFERENCES users(email) ON UPDATE CASCADE
ON DELETE CASCADE');
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
                $table->dropForeign('fk_u1');
                $table->dropForeign('fk_r_u');
                $table->dropForeign('fk_r_u_e');
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            });
            Schema::drop(Constants::PREFIX.self::TABLE);
        });
    }
}
