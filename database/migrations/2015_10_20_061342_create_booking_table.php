<?php

use App\libraries\Constants;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingTable extends Migration
{
    const TABLE='bookings';
    const TABLE_PREFIX='bookings_';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            $table->increments(Constants::PREFIX.self::TABLE_PREFIX.'id');
            $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'user_id')->unsigned();
            $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'package_id')->unsigned();
            $table->string(Constants::PREFIX.self::TABLE_PREFIX.'payment_type');
            $table->enum(Constants::PREFIX.self::TABLE_PREFIX.'payment_status',['CONFIRMED','NOT_CONFIRMED'])->default('NOT_CONFIRMED');
            $table->timestamps();
        });
        Schema::table(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            $table->foreign(Constants::PREFIX.self::TABLE_PREFIX.'user_id',Constants::PREFIX.self::TABLE_PREFIX.'user_id_fk')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign(Constants::PREFIX.self::TABLE_PREFIX.'package_id',Constants::PREFIX.self::TABLE_PREFIX.'package_id_fk')->references('id')->on('candybrush_packages')->onDelete('cascade')->onUpdate('cascade');
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
            $table->dropForeign(Constants::PREFIX.self::TABLE_PREFIX.'package_id_fk');
            $table->dropForeign(Constants::PREFIX.self::TABLE_PREFIX.'user_id_fk');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        });
        Schema::drop(Constants::PREFIX.self::TABLE);
    }
}
