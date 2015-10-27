<?php

use App\libraries\Constants;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserProfileAddCommission extends Migration
{
    const TABLE= 'users_profiles';
    const TABLE_PREFIX='users_profiles_';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(self::TABLE,function(Blueprint $table){
            $table->integer(Constants::PREFIX.self::TABLE_PREFIX.'commission')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(self::TABLE,function(Blueprint $table){
            $table->dropColumn([Constants::PREFIX.self::TABLE_PREFIX.'commission']);
        });
    }
}
