<?php
/**
 * Created By Javed
 * 10/10/15
 * 04:33 pm
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPackegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * create table packages
         */
        Schema::create('candybrush_packages', function ($table) {
            $table->increments('id');
            $table->string('candybrush_packages_name');
            $table->string('candybrush_packages_description', 500);
            $table->string('candybrush_packages_category');
            $table->string('candybrush_packages_sub_category');
            $table->integer('candybrush_packages_price');
            $table->string('candybrush_packages_bonus');
            $table->string('candybrush_packages_offer');
            $table->integer('candybrush_packages_deal_price');
            $table->date('candybrush_packages_available_dates');
            $table->string('candybrush_packages_term_condition');
            $table->string('candybrush_packages_payment_type');
            $table->string('candybrush_packages_maximum_delivery_days');

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

        Schema::drop('candybrush_packages');
    }
}
