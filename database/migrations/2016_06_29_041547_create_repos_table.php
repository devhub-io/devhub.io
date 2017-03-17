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
            $table->integer('stargazers_count');
            $table->integer('watchers_count');
            $table->integer('open_issues_count');
            $table->integer('forks_count');
            $table->integer('subscribers_count');
            $table->integer('issue_response');
            $table->tinyInteger('status')->index()->default(0);
            $table->timestamp('repos_created_at')->default('1970-01-01 08:00:00');
            $table->timestamp('repos_updated_at')->default('1970-01-01 08:00:00');
            $table->timestamp('fetched_at')->default('1970-01-01 08:00:00');
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
