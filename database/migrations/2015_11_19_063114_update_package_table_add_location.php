<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePackageTableAddLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candybrush_packages',function(Blueprint $table){
            $table->string('candybrush_packages_location',80);
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
            $table->dropColumn(['candybrush_packages_location']);
        });
    }
}
