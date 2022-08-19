<?php

use App\Http\Controllers\RegionsController;
use Illuminate\Support\Facades\Route;

Route::get('/',  [RegionsController::class, 'regionsWithCities']);

Route::prefix('statistic')->controller(RegionsController::class)->group(function(){
    Route::get('/most', 'regionsWhereMostDistributors');
    Route::get('/min', 'regionsWhereLeastDistributors');
    Route::get('/none', 'regionsWhereNoDistributors');
});
