<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkClick extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_click', function (Blueprint $table) {
            $table->increments('id');
            $table->string('target')->index();
            $table->string('referer')->index();
            $table->string('ip', 64);
            $table->string('user_agent');
            $table->timestamp('clicked_at')->default('1970-01-01 08:00:00')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('link_click');
    }
}
