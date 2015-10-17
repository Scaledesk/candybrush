<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddPackegesUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // add user package table

        Schema::create('candybrush_users_packages', function ($table) {
            $table->increments('id');
            $table->integer('candybrush_users_packages_user_id');
            $table->integer('candybrush_users_packages_package_id');
            $table->integer('candybrush_users_packages_status');
        });

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::drop('candybrush_users_packages');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
