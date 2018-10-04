<?php


namespace App\Console\Commands;


use App\MovieDataCollection;
use App\MovieSources\MovieSourceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;

class FetchMovieTimes extends Command
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
                $this->synchronizeDatabase($movies);
                $progress->advance();
            }

            $progress->finish();
        });
    }

    private function synchronizeDatabase(MovieDataCollection $movies)
    {
    }
}
