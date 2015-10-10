<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $create=function(Blueprint $table){
            $table->Integer('candybrush_categories_id')->unsigned()->autoIncrement();
            $table->string('candybrush_categories_name',80);
            $table->integer('candybrush_categories_parent_id')->unsigned();
        };
        $foreign_key=function(Blueprint $table){
            $table->foreign('candybrush_categories_parent_id','candybrush_categories_fk')->references('candybrush_categories_id')->on('candybrush_categories')->onDelete('cascade')->onUpdate('cascade');
        };
        Schema::create('candybrush_categories',$create);
        Schema::table('candybrush_categories',$foreign_key);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('candybrush_categories');
    }
}
