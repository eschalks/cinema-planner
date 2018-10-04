<?php


namespace App\Console\Commands;


use App\Enums\MovieDimensions;
use App\Models\Movie;
use App\Models\MovieShowing;
use App\MovieInfo;
use App\MovieInfoCollection;
use App\MovieSources\MovieSourceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;

class FetchMovieShowings extends Command
{
    protected $signature = 'cp:fetch-movies';

    public function handle()
    {
        DB::transaction(function () {
            /** @var MovieSourceInterface[] $sources */
            $sources = app()->tagged('movie_source');

            $progress = new ProgressBar($this->output, count($sources));

            foreach ($sources as $source) {
                $movies = $source->fetchMovies();
                $this->synchronizeDatabase($source->getId(), $movies);
                $progress->advance();
            }

            $progress->finish();
        });
    }

    private function synchronizeDatabase(string $sourceId, MovieInfoCollection $movies)
    {
        /** @var MovieInfo $movieInfo */
        foreach ($movies as $movieInfo) {
            $movie = Movie::firstOrCreate(['title' => $movieInfo->getTitle()]);

            foreach ($movieInfo->getShowings() as $showingInfo) {
                $showingData = [
                    'starts_at' => $showingInfo->getStartTime(),
                    'movie_id' => $movie->id,
                    'source' => $sourceId,
                ];

                if (!MovieShowing::where($showingData)->exists()) {
                    $movieShowing = new MovieShowing($showingData);
                    $movieShowing->ends_at = $showingInfo->getEndTime();
                    $movieShowing->is_3d = $showingInfo->is3D();
                    $movieShowing->quality = $showingInfo->getQuality();
                    $movieShowing->save();
                }
            }
        }
    }
}
