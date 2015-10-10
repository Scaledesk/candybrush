
<?php
/**
 * Created by Javed
 * 10/10/15
 * 12:37 pm
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPortfolioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create user_portfolio table
        Schema::create('users_portfolio', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('candybrush_users_portfolio_user_id');
            $table->string('candybrush_users_portfolio_description', 500);
            $table->string('candybrush_users_portfolio_file');
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
        // for rollback

        Schema::drop('users_portfolio');
    }
}
