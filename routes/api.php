<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::post('register', [AuthController::class, 'register']);
Route::post('auth', [AuthController::class, 'auth']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('logout', [AuthController::class, 'logout']);

    Route::apiResource('tasks', TaskController::class)->except([
        'create', 'edit'
    ]);

    Route::post('tasks/{task}/asigned', [TaskController::class, 'assigneTo']);
    Route::post('tasks/{task}/comment', [TaskController::class, 'comment']);
});
