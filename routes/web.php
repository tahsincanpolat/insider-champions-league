<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\FixturesController;



Route::get('/teams', [TeamsController::class, 'getTeams']);
Route::post('/generate-fixtures', [FixturesController::class, 'generateFixtures']);
Route::get('/get-fixtures', [FixturesController::class, 'getFixtures']);


Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');
