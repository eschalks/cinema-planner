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
        $startDate = Chronos::parse(MovieShowing::min('starts_at'));

        $showingQueryFunction = function ($query) use ($startDate) {
            /** @var HasMany|Builder $query */
            $query->where('starts_at', '>=', $startDate);
        };

        $movies = Movie::whereHas('showings', $showingQueryFunction)
                       ->with([
                           'showings' => $showingQueryFunction,
                       ])
                       ->get()
                       ->sortByDesc(function (Movie $movie) {
                           return $movie->showings->count();
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
