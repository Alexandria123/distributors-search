<?php

use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/products/{systemType}/search', [SearchController::class, 'searchByCity'])->where('systemType', ('techexpert|kodeks'));
Route::get('/products/{systemType}', [SearchController::class, 'xmlHandle']);
