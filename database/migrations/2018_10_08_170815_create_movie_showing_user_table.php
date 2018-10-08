<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovieShowingUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_showing_user', function (Blueprint $table) {
            $table->unsignedInteger('movie_showing_id');
            $table->unsignedInteger('user_id');
            $table->timestamp('created_at')->nullable();

            $table->foreign('movie_showing_id')
                  ->references('id')
                  ->on('movie_showings');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');

            $table->primary(['movie_showing_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movie_showing_user');
    }
}
