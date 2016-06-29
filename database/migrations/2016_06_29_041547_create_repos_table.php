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
            $table->smallInteger('type_id')->index();
            $table->string('slug')->unique();
            $table->string('desc');
            $table->text('readme');
            $table->string('website');
            $table->string('github');
            $table->smallInteger('star_number');
            $table->smallInteger('fork_number');
            $table->integer('issue_response');
            $table->tinyInteger('status')->index()->default(0);
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
