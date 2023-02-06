<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LinksController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;

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


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/getName', [UserController::class, 'getName']);
    Route::get('/getId', [UserController::class, 'getId']);
    Route::resource('/links', LinksController::class);
    Route::post('/isAuthenticated', [UserController::class, 'isAuthenticated']);
   // Route::get('/links/shortCode/{shortCode}', [LinksController::class, 'getByShortCode']);
    Route::get('/links/user/{userId}', [LinksController::class, 'getAllByUser']);
    Route::get('/originalUrl/{shortCode}', [LinksController::class, 'getOriginalLink']);
    Route::get('/getEmail', [UserController::class, 'getEmail']);
});
