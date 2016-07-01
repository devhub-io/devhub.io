<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->smallInteger('category_id')->index();
            $table->string('slug')->unique();
            $table->integer('image')->default(0);
            $table->string('description');
            $table->string('language');
            $table->text('readme');
            $table->string('homepage');
            $table->string('github');
            $table->smallInteger('stargazers_count');
            $table->smallInteger('watchers_count');
            $table->smallInteger('open_issues_count');
            $table->smallInteger('forks_count');
            $table->smallInteger('subscribers_count');
            $table->integer('issue_response');
            $table->tinyInteger('status')->index()->default(0);
            $table->timestamp('repos_created_at');
            $table->timestamp('repos_updated_at');
            $table->timestamp('fetched_at');
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
        Schema::drop('repos');
    }
}
