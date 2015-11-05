<?php

use App\libraries\Constants;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePackagePhotosTable extends Migration
{
    const TABLE='packages_photos';
    const TABLE_PREFIX='packages_photos_';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            $table->increments(Constants::PREFIX.self::TABLE_PREFIX.'id');
            $table->string(Constants::PREFIX.self::TABLE_PREFIX.'url');
            $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'packages_id')->unsigned();
        });
        Schema::table(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            $table->foreign(Constants::PREFIX.self::TABLE_PREFIX.'packages_id',Constants::PREFIX.self::TABLE_PREFIX.'packages_id_fk')->references('id')->on('candybrush_packages')->onDelete('cascade')->onUpdate('cascade');
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
                $table->dropForeign(Constants::PREFIX.self::TABLE_PREFIX.'packages_id_fk');
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            });
            Schema::drop(Constants::PREFIX.self::TABLE);
        });
    }
}
