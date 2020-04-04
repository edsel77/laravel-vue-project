<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->autoIncrement();
            $table->string('username', 100)->unique();
            $table->string('firstname', 100);
            $table->string('lastname', 100);
            $table->string('password', 255);
            $table->string('address', 100);
            $table->string('postcode', 100);
            $table->string('contact', 100);
            $table->string('email', 100);
            $table->tinyInteger('role');
            $table->dateTime('created_at', 0);
            $table->dateTime('updated_at', 0);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
