<?php


namespace App;


use Cake\Chronos\Chronos;

class MovieInfo
{
    /**
     * @var string
     */
    private $title;

    private $showings = [];

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function addShowing(Chronos $startTime, Chronos $endTime, int $dimensions, int $quality)
    {
        $this->showings[] = new MovieShowingInfo($startTime, $endTime, $dimensions, $quality);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return MovieShowingInfo[]
     */
    public function getShowings()
    {
        return $this->showings;
    }
}
