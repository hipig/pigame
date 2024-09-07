<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameCategoryResource;
use App\Models\Game;
use App\Models\GameCategory;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class GameCategoriesController extends Controller
{
    public function index()
    {
        $categories = GameCategory::query()->with(['games' => function(Builder $q) {
            $q->where('status', Game::STATUS_ENABLE)->orderBy('sort')->latest();
        }])->where('status', Game::STATUS_ENABLE)->orderBy('sort')->latest()->get();

        return GameCategoryResource::collection($categories);
    }
}
