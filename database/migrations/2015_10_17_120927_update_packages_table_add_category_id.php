<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdatePackagesTableAddCategoryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candybrush_packages',function(Blueprint $table){
            $table->integer('candybrush_packages_category_id')->unsigned();
        });
        Schema::table('candybrush_packages',function(Blueprint $table){
            $table->foreign('candybrush_packages_category_id','candybrush_packages_category_id_fk')->references('candybrush_categories_id')->on('candybrush_categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candybrush_packages',function(Blueprint $table){
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $table->dropForeign('candybrush_packages_category_id_fk');
            $table->dropColumn(['candybrush_packages_category_id']);
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        });
    }
}
