<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->comment('房间');
            $table->id();
            $table->string('game_key', 64)->comment('游戏标识');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedBigInteger('owner_id')->nullable()->comment('房主ID');
            $table->string('code', 64)->comment('房间号');
            $table->unsignedInteger('max_player')->default(1)->comment('最大房间人数');
            $table->unsignedInteger('player_count')->default(0)->comment('当前人数');
            $table->string('status', 64)->default(\App\Models\Room::STATUS_WAITING)->comment('状态');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
