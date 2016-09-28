<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReposTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repos_tags', function (Blueprint $table) {
            $table->integer('repos_id')->index();
            $table->string('name', 20);
            $table->string('zipball_url');
            $table->string('tarball_url');
            $table->string('commit_sha', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('repos_tags');
    }
}
