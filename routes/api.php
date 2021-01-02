<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/unauthenticated', function() {
    return response()
        ->json(['error' => 'User is not logged in'])
        ->setStatusCode(401);
})->name('login');

Route::post('/user', [AuthController::class, 'signUp']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post(
    '/auth/logout', [AuthController::class, 'logout']
);
Route::middleware('auth:api')->get(
    '/auth/me', [AuthController::class, 'me']
);

Route::middleware('auth:api')->prefix('/todos')->group(function() {

    Route::get('/', [ApiController::class, 'index']);

    Route::get('/', [ApiController::class, 'search']);

    Route::post('/', [ApiController::class, 'store']);

    Route::get('/{id}', [ApiController::class, 'show']);

    Route::put('/{id}', [ApiController::class, 'update']);

    Route::delete('/{id}', [ApiController::class, 'delete']);

});
