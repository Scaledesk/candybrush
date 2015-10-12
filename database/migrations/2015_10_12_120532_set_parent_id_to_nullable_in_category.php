<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetParentIdToNullableInCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('candybrush_categories',function(Blueprint $table){
            $table->integer('candybrush_categories_parent_id')->unsigned()->nullable()->change();
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
        Schema::table('candybrush_categories',function(Blueprint $table){
            DB::table('candybrush_categories')->where('candybrush_categories_parent_id','=',NULL)->update(['candybrush_categories_parent_id'=>0]);
            DB::table('ALTER TABLE candybrush_categories MODIFY candybrush_categories_parent_id INTEGER NOT NULL');
        });
    }
}
