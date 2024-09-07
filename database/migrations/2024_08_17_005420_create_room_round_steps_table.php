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
        Schema::create('room_round_steps', function (Blueprint $table) {
            $table->comment('房间对局步骤');
            $table->id();
            $table->unsignedBigInteger('room_id')->comment('房间ID');
            $table->unsignedBigInteger('round_id')->comment('房间对局ID');
            $table->unsignedBigInteger('user_id')->comment('当前玩家ID');
            $table->unsignedInteger('turn')->default(0)->comment('当前回合');
            $table->unsignedInteger('sort')->default(0)->comment('当前顺序');
            $table->string('phase', 64)->nullable()->comment('当前阶段');
            $table->json('active_users')->nullable()->comment('当前活跃玩家');
            $table->json('sorts')->nullable()->comment('当前玩家顺序列表');
            $table->unsignedInteger('move_count')->default(0)->comment('当前移动次数');
            $table->string('status', 64)->default(\App\Models\RoomRoundStep::STATUS_RUNNING)->comment('状态');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_round_steps');
    }
};
