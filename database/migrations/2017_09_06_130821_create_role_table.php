<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::create('roles', function (Blueprint $table) {
             $table->increments('id');
             $table->string('describe',50);
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
         Schema::dropIfExists('roles');
     }
}
