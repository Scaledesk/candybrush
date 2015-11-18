<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdatePackagesTableAddInstructions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candybrush_packages',function(Blueprint $table){
            $table->text('candybrush_packages_instructions')->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candybrush_packages',function(Blueprint $table){
            $table->dropColumn(['candybrush_packages_instructions']);
        });
    }
}
