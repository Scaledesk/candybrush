<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToUsersPortfolio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // added field to users_portfolio table
        Schema::table('users_portfolio', function ($table) {
            $table->string('candybrush_users_portfolio_file_type', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // rollback method
        Schema::table('users_portfolio', function ($table) {
            $table->dropColumn('candybrush_users_portfolio_file_type');
        });
    }
}
