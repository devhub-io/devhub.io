<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeveloperTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('developer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('login', 100)->index();
            $table->string('name', 100);
            $table->integer('github_id')->default(0);
            $table->string('avatar_url');
            $table->string('html_url');
            $table->string('type', 20);
            $table->boolean('site_admin');
            $table->string('company', 100);
            $table->string('blog');
            $table->string('location', 200);
            $table->string('email', 100);
            $table->smallInteger('public_repos');
            $table->smallInteger('public_gists');
            $table->integer('followers');
            $table->integer('following');
            $table->timestamp('site_created_at')->default('0000-00-00 00:00:00');
            $table->timestamp('site_updated_at')->default('0000-00-00 00:00:00');
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
        Schema::drop('developer');
    }
}
