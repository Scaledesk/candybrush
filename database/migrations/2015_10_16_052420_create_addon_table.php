<?php
use App\libraries\Constants;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateAddonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Constants::PREFIX.'addons',function(Blueprint $table){
           $table->increments(Constants::PREFIX.'addon_id');
            $table->string(Constants::PREFIX.'name',80);
            $table->string(Constants::PREFIX.'description');
            $table->string( Constants::PREFIX.'package_id');
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
        Schema::drop(Constants::PREFIX.'addons');
    }
}
