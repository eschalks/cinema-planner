<?php


namespace App;

class MovieDataCollection
{
    /**
     * @var MovieInfo[]
     */
    private $movies = [];

    public function addMovieWithTitle(string $title): MovieInfo
    {
        $movie = $this->movies[$title] ?? null;
        if (!$movie) {
            $movie = $this->movies[$title] = new MovieInfo($title);
        }
        return $movie;
    }
}
