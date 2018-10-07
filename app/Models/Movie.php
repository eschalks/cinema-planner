<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;

/**
 * App\Models\Movie
 *
 * @property int $id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static EloquentBuilder|\App\Models\Movie whereCreatedAt($value)
 * @method static EloquentBuilder|\App\Models\Movie whereId($value)
 * @method static EloquentBuilder|\App\Models\Movie whereTitle($value)
 * @method static EloquentBuilder|\App\Models\Movie whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MovieShowing[] $showings
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movie withShowings(\Closure $showingQuery)
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

    /**
     * Queries movies based on whether they have any showings meeting the provided criteria.
     * Also eager loads all matching showings.
     *
     * @param EloquentBuilder $query
     * @param \Closure $showingQuery
     * @return EloquentBuilder
     */
    public function scopeWithShowings(EloquentBuilder $query, \Closure $showingQuery)
    {
        return $query->whereHas('showings', $showingQuery)
                     ->with(['showings' => $showingQuery]);
    }
}
