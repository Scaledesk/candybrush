<?php

use App\libraries\Constants;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdatePackageTableAddUserIdPackage extends Migration
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
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::table(Constants::PREFIX.self::TABLE,function(Blueprint $table){
        $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'user_id')->unsigned();
            $table->enum(Constants::PREFIX.self::TABLE_PREFIX.'status',['0','1'])->default('0');
        });
        Schema::table(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            $table->foreign(Constants::PREFIX.self::TABLE_PREFIX.'user_id',Constants::PREFIX.self::TABLE_PREFIX.'user_id_fk')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
        $user_packages=new AddPackegesUsersTable();
        $user_packages->down();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::table(Constants::PREFIX.self::TABLE,function(Blueprint $table){
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $table->dropForeign(Constants::PREFIX.self::TABLE_PREFIX.'user_id_fk');
            $table->dropColumn(Constants::PREFIX.self::TABLE_PREFIX.'user_id',Constants::PREFIX.self::TABLE_PREFIX.'status');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        });
        $user_packages=new AddPackegesUsersTable();
        $user_packages->up();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
