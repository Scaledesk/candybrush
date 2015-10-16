<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentAndReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('candybrush_reviews', function ($table) {
            $table->increments('id');
            $table->integer('candybrush_reviews_user_id');
            $table->integer('candybrush_reviews_package_id');
            $table->integer('candybrush_reviews_rating');
            $table->string('candybrush_reviews_comment',500);
            $table->integer('candybrush_reviews_admin_verified');
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
        Schema::drop('candybrush_reviews');
    }
}
