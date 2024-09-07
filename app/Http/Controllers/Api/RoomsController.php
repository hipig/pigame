<?php

namespace App\Http\Controllers\Api;

use App\Events\RoomListUpdated;
use App\Events\RoomPlayerJoined;
use App\Events\RoomPlayerLeaved;
use App\Events\RoomUpdated;
use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoomPlayerResource;
use App\Http\Resources\RoomResource;
use App\Models\Game;
use App\Models\Room;
use App\Models\RoomPlayer;
use App\Models\RoomRound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomsController extends Controller
{
    public function index(Request $request)
    {
        $rooms = Room::query()->where('game_key', $request->game_key)->whereIn('status', [Room::STATUS_WAITING, Room::STATUS_PLAYING])->latest()->paginate($request->page_size ?? 15);

        return RoomResource::collection($rooms);
    }

    public function store(Request $request)
    {
        $gameKey = $request->game_key;
        $game = Game::query()->where('key', $gameKey)->where('status', Game::STATUS_ENABLE)->firstOrFail();

        $room = new Room();
        $room->game()->associate($game);
        $room->user()->associate(Auth::user());
        $room->max_player = $game->min_player;
        $room->save();

        broadcast(new RoomListUpdated($room));

        return RoomResource::make($room);
    }

    public function update(Request $request, Room $room)
    {
        if ($room->owner_id !== Auth::id()) {
            throw new InvalidRequestException('只有房主可修改人数');
        }

        $maxLimit = $request->max_player;

        if ($maxLimit > $room->game->max_player ||
            $maxLimit < $room->game->min_player ||
            $maxLimit < $room->player_count
        ) {
            throw new InvalidRequestException('人数不能低于或超过游戏限定人数');
        }

        $room->max_player = $maxLimit;
        $room->save();

        broadcast(new RoomListUpdated($room));
        broadcast(new RoomUpdated($room));

        return RoomResource::make($room);
    }

    public function show(Room $room)
    {
        $room->load(['game', 'players']);
        return RoomResource::make($room);
    }

    public function join(Request $request, Room $room)
    {
        $sort = $request->sort;

        $player = RoomPlayer::query()->where('room_id', $room->id)->where('sort', $sort)->first();
        if ($player) {
            throw new InvalidRequestException('该位置已有玩家');
        }

        $user = Auth::user();
        $newPlayer = RoomPlayer::query()->where('room_id', $room->id)->where('user_id', $user->id)->first();
        if (!$newPlayer) {
            $newPlayer = new RoomPlayer();
            $newPlayer->room()->associate($room);
            $newPlayer->user()->associate($user);
            $room->increment('player_count');
        }
        $newPlayer->sort = $sort;
        $newPlayer->save();

        if ($room->player_count === 1) {
            $room->owner()->associate($user);
            $room->save();
        }

        broadcast(new RoomPlayerJoined($room, $newPlayer));

        return RoomPlayerResource::make($newPlayer);
    }

    public function leave(Request $request, Room $room)
    {
        $user = Auth::user();
        $player = RoomPlayer::query()->where('room_id', $room->id)->where('user_id', $user->id)->first();
        if (!$player) {
            throw new InvalidRequestException('该玩家不在位置');
        }

        $player->delete();

        if ($room->owner_id === $user->id) {
            $firstPlayer = RoomPlayer::query()->where('room_id', $room->id)->orderBy('sort')->first();
            $room->owner_id = $firstPlayer->user_id ?? null;
            $room->save();
        }

        $room->decrement('player_count');

        broadcast(new RoomPlayerLeaved($room, $room->players));

        return RoomResource::make($room);
    }

    public function start(Room $room)
    {
        $round = new RoomRound();
        $round->room()->associate($room);
        $round->save();
    }
}
