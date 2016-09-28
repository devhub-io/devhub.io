<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReposContributors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repos_contributors', function (Blueprint $table) {
            $table->integer('repos_id')->index();
            $table->string('login', 100);
            $table->string('avatar_url');
            $table->string('html_url');
            $table->string('type', 15);
            $table->boolean('site_admin');
            $table->integer('contributions')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('repos_contributors');
    }
}
