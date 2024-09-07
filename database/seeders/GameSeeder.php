<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GameCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryData = [
            [
                'name' => '聚会',
                'games' => [
                    [
                        'name' => '爆炸猫咪',
                        'key' => 'bzmm',
                        'icon' => '🐱',
                        'min_people' => 2,
                        'max_people' => 17
                    ]
                ]
            ]
        ];

        foreach ($categoryData as $categoryDatum) {
            $category = GameCategory::query()->firstOrCreate(['name' => $categoryDatum['name']]);
            foreach ($categoryDatum['games'] as $gameDatum) {
                $game = new Game($gameDatum);
                $game->category()->associate($category);
                $game->save();
            }
        }
    }
}
