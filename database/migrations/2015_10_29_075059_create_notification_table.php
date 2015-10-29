<?php

use App\libraries\Constants;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTable extends Migration
{
    const TABLE="notifications";
    const TABLE_PREFIX="notifications_";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            $table->increments(Constants::PREFIX.self::TABLE_PREFIX.'id');
            $table->enum(Constants::PREFIX.self::TABLE_PREFIX.'type',\App\Notification::types());
            $table->string(Constants::PREFIX.self::TABLE_PREFIX.'text');
            $table->boolean(Constants::PREFIX.self::TABLE_PREFIX.'seen')->default(false);
            $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'user_id')->unsigned();
            $table->timestamps();
        });
        Schema::table(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            $table->foreign(Constants::PREFIX.self::TABLE_PREFIX.'user_id',Constants::PREFIX.self::TABLE_PREFIX.'user_id_fk')->references('id')->on('users')->ondelete('cascade')->onupdate('cascade');
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
                $table->dropForeign(Constants::PREFIX.self::TABLE_PREFIX.'user_id_fk');
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            });
            Schema::drop(Constants::PREFIX.self::TABLE);
        });
    }
}
