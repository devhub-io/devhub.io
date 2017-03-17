<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReposQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repos_questions', function (Blueprint $table) {
            $table->integer('repos_id')->index();
            $table->string('title');
            $table->string('link');
            $table->integer('view_count')->index();
            $table->integer('answer_count');
            $table->integer('score');
            $table->integer('question_id')->index();
            $table->timestamp('creation_date')->default('1970-01-01 08:00:00');
            $table->timestamp('last_edit_date')->default('1970-01-01 08:00:00');
            $table->timestamp('last_activity_date')->default('1970-01-01 08:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('repos_questions');
    }
}
