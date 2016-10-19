<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReposVoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repos_vote', function (Blueprint $table) {
            $table->integer('repos_id')->index();
            $table->tinyInteger('reliable');
            $table->tinyInteger('recommendation');
            $table->tinyInteger('documentation');
            $table->string('ip', 50)->index();
            $table->string('user_agent')->index();
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
        Schema::drop('repos_vote');
    }
}
