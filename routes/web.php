<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CreateMovie;
use App\Http\Controllers\RetrieveMovie;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/api/v1/movies', [CreateMovie::class, 'create']);

Route::get('/api/v1/movies/{id}', [RetrieveMovie::class, 'get']);

Route::get('/api/v1/movies', [RetrieveMovie::class, 'getAll']);
