<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

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

Route::group(['middleware' => ['auth:sanctum']], function () {
    // account
    Route::get('/me', function () {
        $user = auth()->user();

        return response()->json($user);
    });

    // posts
    Route::apiResource('/posts', PostController::class);
    Route::get('/posts/{post}/comments', [PostController::class, 'commentsByPost']);

    // comments
    Route::apiResource('/comments', CommentController::class)->only(['store', 'update', 'destroy']);
});
