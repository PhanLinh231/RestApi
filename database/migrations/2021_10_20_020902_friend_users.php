<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FriendUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friend_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('friend_id')->unsigned();

            $table->bigInteger('user_id')->unsigned();

            $table->tinyInteger('status')->comment('0 is waiting | 1 is decline | 2 is accept | -1 is blocked')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('friend_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friend_users');
    }
}
