<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_survey', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_id');
            $table->foreign('survey_id')->references('id')->on('surveys');
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->references('id')->on('questions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('question_survey', function (Blueprint $blueprint) {
            $blueprint->dropForeign(['question_id']);
            $blueprint->dropColumn('question_id');
            $blueprint->dropForeign(['survey_id']);
            $blueprint->dropColumn('survey_id');
            $blueprint->dropColumn('id');
        });
        Schema::dropIfExists('question_survey');
    }
}
