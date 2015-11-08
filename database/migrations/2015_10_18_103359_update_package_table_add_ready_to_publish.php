<?php

use App\libraries\Constants;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePackageTableAddReadyToPublish extends Migration
{
    const TABLE='packages';
    const TABLE_PREFIX='packages_';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            $table->enum(Constants::PREFIX.self::TABLE_PREFIX.'completed',['yes','no'])->default('no');
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
        Schema::table(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            $table->dropColumn([Constants::PREFIX.self::TABLE_PREFIX.'completed']);
        });
    }
}
