<?php

use App\Distributors\allDistributors;
use App\Http\Controllers\ArrayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/products/{systemType}/search', [ArrayController::class, 'store']);
