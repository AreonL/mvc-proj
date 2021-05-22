<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HelloWorldController,
    YatzyController,
    SessionController,
    GameController,
    BookController,
    HighscoreController
};

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


// Added for mos example code
Route::get('/hello-world', function () {
    echo "Hello World";
});
Route::get('/hello-world-view', function () {
    return view('message', [
        'message' => "Hello World from within a view"
    ]);
});
Route::get('/hello', [HelloWorldController::class, 'hello']);
Route::get('/hello/{message}', [HelloWorldController::class, 'hello']);

// Kmom04 / Import routes for yatzy and game21
// Kmom10 / New routes
Route::get('/yatzy', [YatzyController::class, 'index']);
Route::get('/yatzy/setup', [YatzyController::class, 'setup']);
Route::post('/yatzy/firstRoll', [YatzyController::class, 'firstRoll']);
Route::post('/yatzy/roll', [YatzyController::class, 'roll']);
// Route::post('/yatzy/selection', [YatzyController::class, 'selection']);

Route::get('/session', [SessionController::class, 'session']);
Route::post('/session/destroy', [SessionController::class, 'destroy']);

Route::get('/game21', [GameController::class, 'index']);
Route::post('/game21/roll', [GameController::class, 'roll']);

// Kmom05 / Book club and highscore
Route::get('/book', [BookController::class, 'index']);
Route::post('/book/store', [BookController::class, 'store']);

Route::get('/highscore', [HighscoreController::class, 'index']);
Route::post('/highscore/store', [HighscoreController::class, 'store']);

// Kmom10 / Let's go
Route::get('/highscore/{id}', [HighscoreController::class, 'show']);