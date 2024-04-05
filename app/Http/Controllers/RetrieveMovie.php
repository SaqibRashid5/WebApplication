<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Cast;
use App\Models\Rating;
use App\Models\Director;
use Psr\Log\LoggerInterface;

class RetrieveMovie extends Controller
{
    /**
     * The logger implementation.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function get($id)
    {
        $movie = Movie::with(['cast', 'director', 'rating'])->find($id);
        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        $response = $this->createResponse($movie->toArray());
        return response()->json($response);
    }

    public function getAll()
    {
        $movies = Movie::with(['cast', 'director', 'rating'])->get();
        if(!$movies) {
            return response()->json(['message' => 'Movies not found'], 404);
        }

        $response = [];
        foreach ($movies->toArray() as $key => $movie) {
            $response[$key] = $this->createResponse($movie);
        }
        return response()->json($response);
    }

    private function createResponse($movie)
    {
        $response = [];
        $response['name'] = $movie['name'];
        $response['release_date'] = date('d-m-Y', strtotime($movie['release_date']));
        $response['casts'] = array_values(array_column($movie['cast'], 'name'));
        $response['director'] = $movie['director']['name'];
        $response['ratings'] = ['imdb' => (float) $movie['rating']['imdb'], 'rotten_tomatto' => (float) $movie['rating']['rotten_tomatto']];
        return $response;
    }
}
