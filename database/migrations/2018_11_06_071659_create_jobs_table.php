<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('jobshub')->create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category', 20);
            $table->string('company_name', 100);
            $table->string('company_logo', 100);
            $table->string('job_title', 100);
            $table->string('job_location', 50);
            $table->string('url', 200);
            $table->text('description');
            $table->string('slug', 10)->unique();
            $table->string('tags', 50);
            $table->string('coupon', 100);
            $table->string('source', 50);
            $table->tinyInteger('status')->default(1);
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
        Schema::connection('jobshub')->dropIfExists('jobs');
    }
}
