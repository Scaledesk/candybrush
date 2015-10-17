<?php

use App\libraries\Constants;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePackagesTagsTable extends Migration
{
    const TABLE='packages_tags';
    const TABLE_PREFIX='packages_tags_';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            $table->increments(Constants::PREFIX.self::TABLE_PREFIX.'id');
            $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'tag_id')->unsigned();
            $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'package_id')->unsigned();
        });
        Schema::table(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            $table->foreign(Constants::PREFIX.self::TABLE_PREFIX.'tag_id',Constants::PREFIX.self::TABLE_PREFIX.'tag_id_fk')->references('candybrush_tags_id')->on('candybrush_tags')->onDelete('cascade')->onUpdate('cascade');
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
        //
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::drop(Constants::PREFIX.self::TABLE);
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
