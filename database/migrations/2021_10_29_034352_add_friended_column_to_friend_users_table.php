<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFriendedColumnToFriendUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('friend_users', function (Blueprint $table) {
            $table->tinyInteger('friended')->default(0)->comment('true:đã từng là bạn, false:không là bạn');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('friend_users', function (Blueprint $table) {
            $table->dropColumn('friended');
        });
    }
}
