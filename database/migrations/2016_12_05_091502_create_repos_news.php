<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReposNews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repos_news', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url')->index();
            $table->string('title');
            $table->integer('repos_id')->index();
            $table->smallInteger('score');
            $table->integer('time')->index();
            $table->integer('item_id')->index();
            $table->date('post_date');
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
        Schema::drop('repos_news');
    }
}
