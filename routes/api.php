<?php

use App\Distributors\allDistributers;
use App\Http\Controllers\ArrayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get( '/products', function (){
    return app(allDistributers::class)->array();
});

//Route::get( '/store', [ArrayController::class, 'insertDataToDB']);


//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
