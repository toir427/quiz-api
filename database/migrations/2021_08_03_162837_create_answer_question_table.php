<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswerQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_question', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->references('id')->on('questions');
            $table->unsignedBigInteger('answer_id');
            $table->foreign('answer_id')->references('id')->on('answers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('answer_question', function (Blueprint $blueprint) {
            $blueprint->dropForeign(['answer_id']);
            $blueprint->dropColumn('answer_id');
            $blueprint->dropForeign(['question_id']);
            $blueprint->dropColumn('question_id');
            $blueprint->dropColumn('id');
        });
        Schema::dropIfExists('answer_question');
    }
}
