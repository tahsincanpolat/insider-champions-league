<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/teams', [ApiController::class, 'getTeams']);
Route::post('/generate-fixtures', [ApiController::class, 'generateFixtures']);
