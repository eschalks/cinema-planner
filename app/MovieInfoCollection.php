<?php


namespace App;

use Traversable;

class MovieInfoCollection implements \IteratorAggregate
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

    /**
     * Retrieve an external iterator
     * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->movies);
    }
}
