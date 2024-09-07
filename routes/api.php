<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api;


Route::post('authorizations', [Api\AuthorizationsController::class, 'store'])->name('authorizations.store');

Route::get('game-categories', [Api\GameCategoriesController::class, 'index'])->name('game-categories.index');
Route::get('games/{game:key}', [Api\GamesController::class, 'show'])->name('games.show');

Route::middleware('auth:api')->group(function () {

    // 获取当前用户信息
    Route::get('me', [Api\AuthorizationsController::class, 'me'])->name('authorizations.me');
    // 退出登录
    Route::delete('authorizations', [Api\AuthorizationsController::class, 'destroy'])->name('authorizations.destroy');

    Route::post('rooms/{room}/join', [Api\RoomsController::class, 'join'])->name('rooms.join');
    Route::post('rooms/{room}/leave', [Api\RoomsController::class, 'leave'])->name('rooms.leave');
    Route::apiResource('rooms', Api\RoomsController::class)->only(['index', 'store', 'update', 'show'])->names('rooms');

});