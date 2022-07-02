<?php

use App\Distributors\allDistributers;
use App\Http\Controllers\ArrayController;
use App\Http\Controllers\DistributorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get( '/products', function (){
 //   return app(allDistributers::class)->array();
//});
Route::apiResource('/products/{xmlKey}', DistributorController::class);

