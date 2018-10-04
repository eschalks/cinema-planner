<?php


namespace App\MovieSources;


use App\MovieDataCollection;

interface MovieSourceInterface
{
    public function fetchMovies(): MovieDataCollection;
}
