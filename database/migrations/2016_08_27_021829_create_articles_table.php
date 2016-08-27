<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
            $table->string('description', 500);
            $table->text('content');
            $table->string('url');
            $table->integer('user_id')->default(0);
            $table->integer('read_number')->default(0);
            $table->integer('up_number')->default(0);
            $table->integer('down_number')->default(0);
            $table->boolean('is_enable')->default(true);
            $table->timestamp('fetched_at')->nullable();
            $table->string('source', 20);
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
        Schema::drop('articles');
    }
}
