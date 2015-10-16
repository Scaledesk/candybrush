<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldInUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_profiles', function (Blueprint $table) {

            // add field in users_profiles table
            $table->string('candybrush_users_profiles_language_known');
            $table->string('candybrush_users_profiles_description');
            $table->string('candybrush_users_profiles_image');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_profiles', function (Blueprint $table) {

            $table->dropColumn(['candybrush_users_profiles_language_known','candybrush_users_profiles_description','candybrush_users_profiles_image']);

        });


    }
}
