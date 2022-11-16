<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserProfileAPIController;
use App\Http\Middleware\UserProfileAPIMiddleware;

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

//
// NOTA:
//
// nel file app/Http/Kernel.php viene dichiarato per tutte le rotte 'api' un middleware con rate limiter: 'throttle:api'.
// Il rate limiter: 'throttle:api' è poi definito in app/Providers/RouteServiceProvider.php
//
// https://github.com/laravel/laravel/blob/9.x/app/Providers/RouteServiceProvider.php#L49
// return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
//
// Se non rimuovo il rate limiter 'throttle:api' per lasciare soltanto il rate limiter 'throttle:user_profile_rate'
// verranno applicati entrambi e tra i due prevarrà quello più restrittivo.
//
// Il nuovo rate limiter 'throttle:user_profile_rate' è definito in app/Providers/UserProfileAPIServiceProvider.php
//
// Un rate limiter può essere definito in linea, ad esempio: 'throttle:100,1'
//
// Laravel Routing
// https://laravel.com/docs/9.x/routing#rate-limiting
//

//
// CON RATE LIMITER DI DEFAULT: 'throttle:api'
// Route::middleware([UserProfileAPIMiddleware::class])->group(function ()
//
// SENZA RATE LIMITER: 'throttle:api'
// Route::middleware([UserProfileAPIMiddleware::class])->withoutMiddleware(['throttle:api'])->group(function ()
//
// CON RATE LIMITER: 'throttle:user_profile_rate' E SENZA RATE LIMITER: 'throttle:api'
Route::middleware([UserProfileAPIMiddleware::class, 'throttle:user_profile_rate'])->withoutMiddleware(['throttle:api'])->group(function ()
{
    Route::post('/user_profile', [UserProfileAPIController::class, 'ProfileProvisioning']);
});
