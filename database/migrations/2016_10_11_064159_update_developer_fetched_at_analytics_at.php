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
            $table->timestamp('fetched_at')->default('1970-01-01 08:00:00');
            $table->timestamp('analytics_at')->default('1970-01-01 08:00:00');
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
