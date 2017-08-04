<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReposDependencies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repos_dependencies', function (Blueprint $table) {
            $table->integer('repos_id')->index();
            $table->string('source', 15)->index();
            $table->string('env', 15)->index();
            $table->string('package', 100);
            $table->string('version', 50);
            $table->string('version_condition', 10);
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
        Schema::drop('repos_dependencies');
    }
}
