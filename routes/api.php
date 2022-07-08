<?php

use App\Distributors\allDistributors;
use App\Http\Controllers\ArrayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/products/{xmlKey}', [ArrayController::class, 'index']);

Route::get('/products/{xmlKey}/search', [ArrayController::class, 'store']);
