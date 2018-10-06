<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Collection;

/**
 * App\Models\Movie
 *
 * @property int $id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MovieShowing[] $showings
 */
class Movie extends BaseModel
{
    public function showings()
    {
        return $this->hasMany(MovieShowing::class);
    }

    /**
     * @param \DateTimeInterface $date
     * @return Collection|MovieShowing[]
     */
    public function getShowingsForDate(\DateTimeInterface $date): Collection
    {
        $dateString = $date->format('Y-m-d');
        return $this->showings->filter(function (MovieShowing $showing) use ($dateString) {
            return $showing->starts_at->format('Y-m-d') === $dateString;
        });
    }
}
