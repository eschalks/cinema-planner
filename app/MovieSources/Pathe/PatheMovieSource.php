<?php


namespace App\MovieSources\Pathe;


use App\Enums\MovieDimensions;
use App\Enums\MovieQuality;
use App\MovieDataCollection;
use App\MovieSources\MovieSourceInterface;
use Cake\Chronos\Chronos;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

class PatheMovieSource implements MovieSourceInterface
{

    public function fetchMovies(): MovieDataCollection
    {
        $date = Chronos::today();
        if ($date->dayOfWeek >= 1 && $date->dayOfWeek < 4) {
            $date = $date->next(4);
        }

        $endDate = $date->next(3);

        $movies = new MovieDataCollection();
        /** @var PromiseInterface[] $promises */
        $promises = [];
        $client = new Client([
            'base_uri' => config('pathe.baseUrl') . '/bioscoop/' . config('pathe.location'),
        ]);

        while ($date <= $endDate) {
            $promises[] = $this->fetchDay($client, $date, $movies);
            $date = $date->addDay();
        }

        foreach ($promises as $promise) {
            $promise->wait();
        }

        return $movies;
    }

    private function fetchDay(Client $client, Chronos $date, MovieDataCollection $movies): PromiseInterface
    {
        return $this->fetchHtml($client, $date)
                    ->then(function (string $html) use ($date, $movies) {
                        $crawler = new Crawler($html);
                        $crawler
                            ->filter('.schedule-simple__item')
                            ->each(function (Crawler $node) use ($date, $movies) {
                                $this->parseMovieElement($node, $date, $movies);
                            });
                    });
    }

    private function fetchHtml(Client $client, Chronos $date): PromiseInterface
    {
        $formattedDate = $date->format('d-m-Y');
        $cachePath = storage_path("app/sources/pathe/$formattedDate.html");
        if (file_exists($cachePath)) {
            return new FulfilledPromise(file_get_contents($cachePath));
        }

        return $client->getAsync('',
            [
                'query' => [
                    'date' => $formattedDate,
                ],
            ])->then(function (ResponseInterface $response) use ($cachePath) {
            $html = $response->getBody()->getContents();
            file_put_contents($cachePath, $html);
            return $html;
        });
    }

    private function parseMovieElement(Crawler $movieElement, Chronos $date, MovieDataCollection $movies)
    {
        $title = $movieElement
            ->filter('.schedule-simple__content a')
            ->text();

        $movie = $movies->addMovieWithTitle($title);

        $movieElement
            ->filter('.schedule-time')
            ->each(function (Crawler $timeElement) use ($date, $movie) {
                $startTime = $this->parseTime(
                    $date,
                    $timeElement->filter('.schedule-time__start')->text()
                );
                $endTime = $this->parseTime(
                    $date,
                    $timeElement->filter('.schedule-time__end')->text()
                );

                if ($endTime < $startTime) {
                    $endTime = $endTime->addDay();
                }

                $labelText = $timeElement->filter('.schedule-time__label')->text();
                $dimensions = strpos($labelText, '3D') !== false ?
                    MovieDimensions::THREE :
                    MovieDimensions::TWO;

                $quality = $this->parseQuality($labelText);

                $movie->addShowing($startTime, $endTime, $dimensions, $quality);
            });
    }

    private function parseTime(Chronos $date, string $text): Chronos
    {
        $time = explode(':', $text);

        $hours = (int)$time[0];
        $minutes = (int)$time[1];

        if ($hours <= 3) {
            $date = $date->addDay();
        }

        return $date->setTime($hours, $minutes);
    }

    private function parseQuality(string $labelText)
    {
        if (stripos($labelText, 'IMAX') !== false) {
            return MovieQuality::IMAX;
        }

        if (stripos($labelText, 'DOLBY') !== false) {
            return MovieQuality::DOLBY_ATMOS;
        }

        return MovieQuality::NORMAL;
    }
}
