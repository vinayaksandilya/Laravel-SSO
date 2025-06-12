<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SSOAuthController;

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

// SSO Authentication routes
Route::post('/register', [SSOAuthController::class, 'register']);
Route::post('/login', [SSOAuthController::class, 'login']);
Route::post('/verify-token', [SSOAuthController::class, 'verifyToken']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    Route::get('/user', [SSOAuthController::class, 'user']);
    Route::post('/logout', [SSOAuthController::class, 'logout']);
    Route::get('/clients', [SSOAuthController::class, 'getClients']);
    Route::post('/clients', [SSOAuthController::class, 'createClient']);
});

// OAuth routes (these are handled by Passport)
Route::get('/oauth/authorize', '\Laravel\Passport\Http\Controllers\AuthorizationController@authorize');
Route::post('/oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
Route::get('/oauth/clients', '\Laravel\Passport\Http\Controllers\ClientController@forUser');
Route::post('/oauth/clients', '\Laravel\Passport\Http\Controllers\ClientController@store');
