<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LinksController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::resource('/post', PostController::class);


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
//Route::post('/isAuthenticated', [UserController::class, 'isAuthenticated']);


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/getName', [UserController::class, 'getName']);
    Route::get('/getId', [UserController::class, 'getId']);
    Route::resource('/links', LinksController::class);
});


// controller->AuthController
//Route::post('/register', [\App\Http\Controllers\Controller::class, 'register']);
