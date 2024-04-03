<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
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

            $collection = collect($validatedData);
            $dateFixedCollection = $collection->map(function ($movie) {
                return [
                    'name' => $movie['name'],
                    'release_date' => date('Y-m-d', strtotime($movie['release_date']))
                ];
            });

            $movies = $dateFixedCollection->toArray();

            // Create a new resources using the validated data
            $movieObject = Movie::createBulk($movies);

            // Return a JSON response with the newly created resource
            return response()->json(['message' => 'Movie/s created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
