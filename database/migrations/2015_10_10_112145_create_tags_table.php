<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
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
            $table->Integer('candybrush_tags_id')->unsigned()->autoIncrement();
            $table->string('candybrush_tags_name',80);
            $table->string('candybrush_tags_description',100);
        };
        Schema::create('candybrush_tags',$create);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('candybrush_tags');
    }
}
