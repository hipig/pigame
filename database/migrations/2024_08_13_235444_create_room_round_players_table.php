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
        Schema::create('room_round_players', function (Blueprint $table) {
            $table->comment('对局玩家');
            $table->id();
            $table->unsignedBigInteger('room_id')->comment('房间ID');
            $table->unsignedBigInteger('round_id')->comment('房间对局ID');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedSmallInteger('sort')->default(1)->comment('当前顺序');
            $table->json('data')->nullable()->comment('对局玩家数据');
            $table->string('status', 64)->default(\App\Models\RoomRoundPlayer::STATUS_RUNNING)->comment('状态');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_round_players');
    }
};
