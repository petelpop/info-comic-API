<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ComicController;
use App\Http\Controllers\CommentController;
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

Route::middleware(['auth:sanctum'])->group(function (){
  Route::get('/comics', [ComicController::class, 'index']);
  Route::get('/comics/{id}', [ComicController::class, 'show']);
  Route::post('/comics', [ComicController::class, 'store']);
  Route::patch('/comics/{id}', [ComicController::class, 'update'])->middleware(['comic.owner']);
  Route::delete('/comics/{id}', [ComicController::class, 'delete'])->middleware(['comic.owner']);
  Route::post('/comment', [CommentController::class, 'store']);
  Route::get('/logout', [AuthenticationController::class, 'logout']);
  Route::get('me', [AuthenticationController::class, 'me']);
});

Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/register', [AuthenticationController::class, 'register']);
