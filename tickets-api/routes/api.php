<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/tickets',             [TicketController::class, 'index']);
    Route::post('/tickets',            [TicketController::class, 'store']);
    Route::get('/tickets/{ticket}',    [TicketController::class, 'show']);
    Route::put('/tickets/{ticket}',    [TicketController::class, 'update']);
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy']);

    Route::post('/tickets/{ticket}/comments',             [CommentController::class, 'store']);
    Route::delete('/tickets/{ticket}/comments/{comment}', [CommentController::class, 'destroy']);
});