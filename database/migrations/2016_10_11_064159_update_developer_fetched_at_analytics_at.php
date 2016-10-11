<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDeveloperFetchedAtAnalyticsAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('developer', function (Blueprint $table) {
            $table->timestamp('fetched_at')->nullable;
            $table->timestamp('analytics_at')->nullable;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('developer', function (Blueprint $table) {
            $table->dropColumn('fetched_at');
            $table->dropColumn('analytics_at');
        });
    }
}
