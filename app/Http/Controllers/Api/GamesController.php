<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Models\RoomRound;
use Illuminate\Http\Request;

class GamesController extends Controller
{
    public function show(Game $game)
    {
        return GameResource::make($game);
    }

    public function start(Request $request)
    {

    }

    public function move(Request $request)
    {
        $round = RoomRound::query()->where('id', $request->input('room_round_id'))->first();
    }
}
