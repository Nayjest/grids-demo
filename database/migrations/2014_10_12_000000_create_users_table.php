<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->rememberToken();

            $table->date('birthday')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('country');
            $table->string('company');
            $table->integer('posts_count', false, true)->default(0);
            $table->integer('comments_count', false, true)->default(0);

            $table->timestamps();

            $table->index('birthday');
            $table->index('phone_number');
            $table->index('country');
            $table->index('company');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }

}
