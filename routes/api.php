<?php

use App\Http\Controllers\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/products/{systemType}', [SearchController::class, 'allDistributorsSortedArray'])->name('array');
Route::get('/products/{systemType}/search', [SearchController::class, 'searchByCity']);
