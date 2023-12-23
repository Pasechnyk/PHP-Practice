<?php

use App\Http\Controllers\API\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("categories",[CategoryController::class, 'getAll']);
Route::post("categories/create",[CategoryController::class, 'create']);
Route::put("categories/update/{id}", [CategoryController::class, 'update']);
Route::delete("categories/delete/{id}", [CategoryController::class, 'delete']);