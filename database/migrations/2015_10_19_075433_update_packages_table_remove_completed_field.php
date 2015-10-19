<?php

use App\libraries\Constants;
use App\PackagesModel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePackagesTableRemoveCompletedField extends Migration
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
            $table->dropColumn([Constants::PREFIX.self::TABLE_PREFIX.'completed',Constants::PREFIX.self::TABLE_PREFIX.'status']);
        });
        Schema::table(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            $table->enum(Constants::PREFIX.self::TABLE_PREFIX.'status',[PackagesModel::ACTIVE,PackagesModel::PENDING_APPROVAL,PackagesModel::REQUIRES_MODIFICATION,PackagesModel::DENIED,PackagesModel::PAUSED])->default(PackagesModel::PAUSED);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            $table->dropColumn([Constants::PREFIX.self::TABLE_PREFIX.'status']);
        });
        Schema::table(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            $table->enum(Constants::PREFIX.self::TABLE_PREFIX.'completed',['yes','no'])->default('no');
            $table->enum(Constants::PREFIX.self::TABLE_PREFIX.'status',['0','1'])->default('0');
        });
    }
}
