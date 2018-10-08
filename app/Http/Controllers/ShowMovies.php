<?php


namespace App\Http\Controllers;


use App\DateIterator;
use App\Models\Movie;
use App\Models\MovieShowing;
use Cake\Chronos\Chronos;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShowMovies extends Controller
{
    public function __invoke()
    {
        $startDate = Chronos::today();

        $movies = Movie::withShowings(function ($query) use ($startDate) {
            /** @var HasMany|Builder $query */
            $query->where('starts_at', '>=', $startDate);
        })->get()
                       ->sortByDesc(function (Movie $movie) {
                           return $movie->getPopularity();
                       });

        $endDate = $movies->max(function (Movie $movie) {
            return $movie->showings->max('starts_at');
        });

        $dates = new DateIterator($startDate, $endDate);

        return view('movies',
            [
                'movies' => $movies,
                'dates'  => $dates,
            ]);
    }
}
