<?php

use App\Distributors\allDistributers;
use App\Http\Controllers\ArrayController;
use App\Http\Controllers\DistributorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::apiResource('/products/{xmlKey}', DistributorController::class);

