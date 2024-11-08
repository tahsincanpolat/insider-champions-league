<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\FixturesController;
use App\Http\Controllers\SimulationController;


// Teams
Route::get('/teams', [TeamsController::class, 'getTeams']);

// Fixtures
Route::post('/generate-fixtures', [FixturesController::class, 'generateFixtures']);
Route::get('/get-fixtures', [FixturesController::class, 'getFixtures']);
Route::get('/get-matches', [FixturesController::class, 'getMatches']);

// Simulation
Route::post('/play-next-week', [SimulationController::class, 'playNextWeek']);
Route::post('/play-all-weeks', [SimulationController::class, 'playAllWeeks']);
Route::post('/reset-data', [SimulationController::class, 'resetData']);
Route::get('/get-standings', [SimulationController::class, 'getStandings']);
Route::get('/get-championleague-predictions', [SimulationController::class, 'getChampionLeaguePredictions']);

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');
