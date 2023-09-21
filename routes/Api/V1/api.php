<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Api\AuthController;
use App\Http\Controllers\V1\Api\article\ArticleController;
use App\Http\Controllers\V1\Api\article\CommentController;

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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Admin Routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    Route::post('articles',[ArticleController::class,'store']);
    Route::get('articles/review',[ArticleController::class,'review']);
    Route::post('articles/{article}/approve',[ArticleController::class,'approve']);
    Route::get('articles/search', [ArticleController::class, 'search']);
    Route::get('articles/status',[ArticleController::class,'getArticleWithStatus']);
    Route::get('articles/popular',[ArticleController::class,'popularArticle']);
});


Route::middleware('auth:sanctum')->group(function() {

    Route::get('articles/status',[ArticleController::class,'getArticleWithStatus']);
    Route::get('articles/popular',[ArticleController::class,'popularArticle']);
    Route::get('articles/search', [ArticleController::class, 'search']);

    Route::apiResource('articles', ArticleController::class);
    Route::apiResource('comments', CommentController::class);

    Route::post('logout', [AuthController::class, 'logout']);
});
