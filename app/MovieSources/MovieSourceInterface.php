<?php


namespace App\MovieSources;


use App\MovieInfoCollection;

interface MovieSourceInterface
{
    public function getId(): string;
    public function fetchMovies(): MovieInfoCollection;
}
