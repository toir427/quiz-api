<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCorrectAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('correct_answers', function (Blueprint $blueprint) {
            $blueprint->string('answer', 512)->nullable();
            $blueprint->unsignedBigInteger('answer_id')->nullable()->change();
            $blueprint->foreign('question_id')->references('id')->on('questions');
            $blueprint->foreign('user_id')->references('id')->on('users');
            $blueprint->foreign('answer_id')->references('id')->on('answers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('correct_answers', function (Blueprint $table) {
            $table->dropColumn('answer');
            $table->dropForeign(['answer_id']);
            $table->dropColumn('answer_id');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropForeign(['question_id']);
            $table->dropColumn('question_id');
            $table->dropColumn('id');
        });
        Schema::dropIfExists('correct_answers');
    }
}
