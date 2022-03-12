<?php

use App\Http\Controllers\PostbackController;
use App\Http\Controllers\Statistics\LinkHitsController;
use App\Http\Controllers\Statistics\LinkTypeHitsController;
use App\Http\Controllers\User\GetUserJourneyController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
 * -------------------------------------------------
 * GET
 * -------------------------------------------------
 */
Route::get('/statistics/link-hits', [LinkHitsController::class, "__invoke"]);
Route::get('/statistics/link-type-hits', [LinkTypeHitsController::class, "__invoke"]);
Route::get('/user/journey/{id}', [GetUserJourneyController::class, "__invoke"]);
/*
 * -------------------------------------------------
 * POST
 * -------------------------------------------------
 */
Route::post("/postback", [PostbackController::class, "__invoke"]);
