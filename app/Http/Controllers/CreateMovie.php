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
                '*.release_date' => 'required',
            ]);

            $collection = collect($request->all());
            $dateFixedCollection = $collection->map(function ($movie) {
                return [
                    'name' => $movie['name'],
                    'release_date' => date('Y-m-d', strtotime($movie['release_date']))
                ];
            });

            $movies = $dateFixedCollection->toArray();

            // $validatedData['release_date'] = date('Y-m-d', strtotime($validatedData['release_date']));
            // Create a new resource using the validated data
            $movieObject = Movie::create($movies);

            // Return a JSON response with the newly created resource
            return response()->json(['message' => 'Movie created successfully', 'data' => $movieObject], 201);
        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
