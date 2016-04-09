<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInstagramToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('insta_token');
            $table->string('insta_user');
            $table->string('insta_name');
            $table->string('insta_picture');
            $table->tinyInteger('insta_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['insta_token', 'insta_user', 'insta_name', 'insta_picture', 'insta_active']);
        });
    }
}
