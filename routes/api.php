<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RelayRequestController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::any('{path?}', RelayRequestController::class)
    ->where('path', '.*')
    ->name('relay');

//Route::any('{path?}', function (Request $request, string $path = null) {
//
//    $response = \Illuminate\Support\Facades\Http::withOptions(['verify' => false])
//        ->get('https://host.docker.internal:8005/'.$path);
//
//    dd($response);
//    return $response;
//})->where('path', '.*');
