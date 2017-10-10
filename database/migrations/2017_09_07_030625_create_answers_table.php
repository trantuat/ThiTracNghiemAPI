<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::create('answers', function (Blueprint $table) {
             $table->increments('id');
             $table->string('option1',500);
             $table->string('option2',500);
             $table->string('option3',500);
             $table->string('option4',500);
             $table->integer('correct_answer');
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
         Schema::dropIfExists('answers');
     }
}
