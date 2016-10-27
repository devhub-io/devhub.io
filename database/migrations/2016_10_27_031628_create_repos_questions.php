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
            $table->timestamp('creation_date')->nullable();
            $table->timestamp('last_edit_date')->nullable();
            $table->timestamp('last_activity_date')->nullable();
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
