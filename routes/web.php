<?php

use App\Http\Controllers\RouterController;
use Illuminate\Support\Facades\Route;

Route::any('{view?}', [RouterController::class, 'app'])->where('view', '.*');
