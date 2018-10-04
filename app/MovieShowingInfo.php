<?php


namespace App;


use App\Enums\MovieDimensions;
use Cake\Chronos\Chronos;

class MovieShowingInfo
{
    /**
     * @var Chronos
     */
    private $startTime;
    /**
     * @var Chronos
     */
    private $endTime;
    /**
     * @var int
     */
    private $dimensions;
    /**
     * @var int
     */
    private $quality;

    public function __construct(Chronos $startTime, Chronos $endTime, int $dimensions, int $quality)
    {
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->dimensions = $dimensions;
        $this->quality = $quality;
    }

    /**
     * @return Chronos
     */
    public function getStartTime(): Chronos
    {
        return $this->startTime;
    }

    /**
     * @return Chronos
     */
    public function getEndTime(): Chronos
    {
        return $this->endTime;
    }

    /**
     * @return int
     */
    public function getDimensions(): int
    {
        return $this->dimensions;
    }

    /**
     * @return int
     */
    public function getQuality(): int
    {
        return $this->quality;
    }

    public function is3D()
    {
        return $this->dimensions === MovieDimensions::THREE;
    }
}
