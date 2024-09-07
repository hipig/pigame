<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\Models\Game;
use Illuminate\Http\Request;

class GamesController extends Controller
{
    public function show(Game $game)
    {
        return GameResource::make($game);
    }
}
