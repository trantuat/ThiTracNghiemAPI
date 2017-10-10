<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::create('users', function (Blueprint $table) {
             $table->increments('id');
             $table->string('username',50);
             $table->string('email',50)->unique();
             $table->string('password',200);
             $table->integer('role_user_id')->unsigned();
             $table->foreign('role_user_id')->references('id')->on('roles');
             $table->string('secret_key',200);
             $table->integer('is_active');
             $table->timestamps();
             $table->charset = 'utf8';
             $table->collation = 'utf8_unicode_ci';
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
