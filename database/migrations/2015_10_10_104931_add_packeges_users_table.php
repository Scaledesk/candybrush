<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
        //
        Schema::drop('candybrush_users_packages');
    }
}
