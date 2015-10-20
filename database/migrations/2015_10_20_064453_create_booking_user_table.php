<?php

use App\libraries\Constants;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingUserTable extends Migration
{
    const TABLE="user_booking";
    const TABLE_PREFIX="user_booking_";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {/*
        Schema::create(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            $table->increments(Constants::PREFIX.self::TABLE_PREFIX.'id');
            $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'user_id')->unsigned();
            $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'booking_id')->unsigned();
            $table->timestamps();
        });
        Schema::table(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            $table->foreign(Constants::PREFIX.self::TABLE_PREFIX.'user_id',Constants::PREFIX.self::TABLE_PREFIX.'user_id_fk')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign(Constants::PREFIX.self::TABLE_PREFIX.'booking_id',Constants::PREFIX.self::TABLE_PREFIX.'booking_id_fk')->references('candybrush_bookings_id')->on('candybrush_bookings')->onDelete('cascade')->onUpdate('cascade');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
       /* Schema::table(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $table->dropForeign(Constants::PREFIX.self::TABLE_PREFIX.'booking_id_fk');
            $table->dropForeign(Constants::PREFIX.self::TABLE_PREFIX.'user_id_fk');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        });
        Schema::drop(Constants::PREFIX.self::TABLE);*/
    }
}
