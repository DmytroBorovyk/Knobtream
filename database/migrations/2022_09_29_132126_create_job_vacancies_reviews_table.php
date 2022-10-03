<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->integer('job_id')->unsigned();
            $table->text('review_text');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('job_reviews', function($table) {
            $table->foreign( 'user_id')->references('id')->on('users');
            $table->foreign('job_id')->references('id')->on('job_vacancies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_reviews');
    }
};
