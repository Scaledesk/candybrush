<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateReviewTableAddThreeRatings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candybrush_reviews',function(Blueprint $table) {
            $table->integer('candybrush_reviews_seller_communication_rating');
            $table->integer('candybrush_reviews_seller_as_described');
            $table->integer('candybrush_reviews_would_recommend');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candybrush_reviews',function(Blueprint $table) {
            $table->dropColumn(['candybrush_reviews_seller_communication_rating','candybrush_reviews_seller_as_described','candybrush_reviews_would_recommend']);
        });
    }
}
