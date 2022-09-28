<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->timestamp('date_from')->nullable();
            $table->timestamp('date_to')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('gender')->default(0);
            $table->tinyInteger('type')->default(0);
            $table->integer('age_from');
            $table->integer('age_to');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('surveys', function (Blueprint $blueprint) {
            $blueprint->dropTimestamps();
            $blueprint->dropForeign(['user_id']);
            $blueprint->dropColumn('user_id');
            $blueprint->dropColumn('id');
            $blueprint->dropColumn('title');
            $blueprint->dropColumn('description');
            $blueprint->dropColumn('date_from');
            $blueprint->dropColumn('date_to');
            $blueprint->dropColumn('status');
            $blueprint->dropColumn('gender');
            $blueprint->dropColumn('type');
            $blueprint->dropColumn('age_from');
            $blueprint->dropColumn('age_to');
        });
        Schema::dropIfExists('surveys');
    }
}
