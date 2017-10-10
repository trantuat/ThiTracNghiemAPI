<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('content',500);
            $table->integer('is_public')->unsigned();
            $table->integer('answer_id')->unsigned();
            $table->integer('topic_id')->unsigned();
            $table->integer('level_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('answer_id')->references('id')->on('answers');
            $table->foreign('topic_id')->references('id')->on('topics');
            $table->foreign('level_id')->references('id')->on('levels');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('questions');
    }
}
