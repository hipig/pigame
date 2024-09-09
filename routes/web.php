<?php

use App\Http\Controllers\RouterController;
use Illuminate\Support\Facades\Route;

Route::get('game', function () {
    (new \App\Games\Bzmm\Game())->create();
});

Route::any('{view?}', [RouterController::class, 'app'])->where('view', '.*');
