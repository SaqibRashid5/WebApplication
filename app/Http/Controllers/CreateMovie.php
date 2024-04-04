<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Cast;
use App\Models\Rating;
use App\Models\Director;
use Psr\Log\LoggerInterface;

class CreateMovie extends Controller
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

    public function create(Request $request)
    {
        try {
            // Validate incoming request data
            $validatedData = $request->validate([
                '*.name' => 'required|string',
                '*.casts' => 'required|array',
                '*.release_date' => 'required',
                '*.director' => 'required|string',
                '*.ratings' => 'required|array'
            ]);

            $this->postMovieData($validatedData);
            
            return response()->json(['message' => 'Movie/s created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    private function postMovieData($validatedData)
    {
        foreach ($validatedData as $movie) {
            $movieData = [
                'name' => $movie['name'],
                'release_date' => date('Y-m-d', strtotime($movie['release_date']))
            ];
            $movieObj = Movie::create($movieData);
            foreach ($movie['casts'] as $cast) {
                $castObj = new Cast(['name' => $cast]);
                $movieObj->cast()->save($castObj);
            }
            $directorObj = new Director(['name' => $movie['director']]);
            $movieObj->director()->save($directorObj);
            $ratingObj = new Rating(['imdb' => $movie['ratings']['imdb'], 
            'rotten_tomatto' => $movie['ratings']['rotten_tomatto']]);
            $movieObj->rating()->save($ratingObj);
        }
    }
}
