<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReposTrees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repos_trees', function (Blueprint $table) {
            $table->integer('repos_id')->index();
            $table->string('commit_sha', 50)->index();
            $table->string('sha', 50);
            $table->string('path');
            $table->string('mode', 10);
            $table->string('type', 15);
            $table->string('url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('repos_trees');
    }
}
