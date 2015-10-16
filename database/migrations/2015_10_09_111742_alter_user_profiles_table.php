<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_profiles', function (Blueprint $table) {
            // add fields
            $table->renameColumn('candybrush_users_profiles_first_name', 'candybrush_users_profiles_name');
            $table->dropColumn('candybrush_users_profiles_last_name');
            $table->string('candybrush_users_profiles_id_proof');
            $table->string('candybrush_users_profiles_social_account_integration');
            $table->string('candybrush_users_profiles_custom_message');
            $table->date('candybrush_users_profiles_birth_date');
            $table->string('candybrush_users_profiles_sex',50);


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
            //
        });
    }
}
