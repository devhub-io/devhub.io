<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateReposSomeIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('repos', function (Blueprint $table) {
            $table->index('repos_created_at');
            $table->index('repos_updated_at');
            $table->index('stargazers_count');
            $table->index('fetched_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('repos', function (Blueprint $table) {
            $table->dropIndex('repos_created_at');
            $table->dropIndex('repos_updated_at');
            $table->dropIndex('stargazers_count');
            $table->dropIndex('fetched_at');
        });
    }
}
