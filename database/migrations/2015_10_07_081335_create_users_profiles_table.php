<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //  create user profile table
        Schema::create('users_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('candybrush_users_profiles_users_id');
            $table->string('candybrush_users_profiles_first_name',50);
            $table->string('candybrush_users_profiles_last_name',50);
            $table->mediumInteger('candybrush_users_profiles_mobile');
            $table->string('candybrush_users_profiles_address');
            $table->string('candybrush_users_profiles_state', 50);
            $table->string('candybrush_users_profiles_city',50);
            $table->string('candybrush_users_profiles_pin',10);
            $table->timestamps();
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
    }
}
