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
    private $popularity;

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

    /**
     * Used to determine how high up a movie should appear on the index page.
     * Based on the amount of showings and number of users that are interested.
     */
    public function getPopularity()
    {
        if ($this->popularity === null) {
            $this->popularity = $this->getPopularityShowingCount() + $this->getPopularityUserCount() * 1000;
        }
        return $this->popularity;
    }

    private function getPopularityShowingCount()
    {
        if ($this->relationLoaded('showings')) {
            return $this->showings->count();
        }

        return $this->showings()->count();
    }

    private function getPopularityUserCount()
    {
        return $this->createUserCountQuery()->count('user_id');
    }

    private function createUserCountQuery()
    {
        $query = \DB::table('movie_showing_user')
                    ->distinct();

        if ($this->relationLoaded('showings')) {
            $showingIds = $this->showings->pluck('id');
            return $query->whereIn('movie_showing_id', $showingIds);
        }

        return $query->join('movie_showings', 'movie_showing_id', '=', 'movie_showings.id')
                     ->where('movie_id', $this->id);
    }
}
