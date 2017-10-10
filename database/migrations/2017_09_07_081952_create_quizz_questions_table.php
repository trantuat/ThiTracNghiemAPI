<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizzQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::create('quizz_questions', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('quizz_id')->unsigned();
             $table->integer('question_id')->unsigned();
             $table->integer('option_choose')->nullable();
             $table->foreign('question_id')->references('id')->on('questions');
             $table->foreign('quizz_id')->references('id')->on('quizzes');
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
         Schema::dropIfExists('quizz_questions');
     }
}
