<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateReposLicenses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repos_licenses', function (Blueprint $table) {
            $table->integer('repos_id')->index();
            $table->string('key', 20);
            $table->string('name', 50);
            $table->string('spdx_id', 20);
            $table->boolean('featured');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('repos_licenses');
    }
}
