<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovieShowingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_showings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('movie_id');
            $table->string('source');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->boolean('3d');
            $table->unsignedTinyInteger('quality');

            $table->foreign('movie_id')
                ->references('id')
                ->on('movies');

            $table->unique(['movie_id', 'source', 'starts_at']);
            $table->index('starts_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movie_showings');
    }
}
