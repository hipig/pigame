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
        Schema::create('room_rounds', function (Blueprint $table) {
            $table->comment('房间对局');
            $table->id();
            $table->unsignedBigInteger('room_id')->comment('房间ID');
            $table->unsignedSmallInteger('current_sort')->default(1)->comment('当前顺序');
            $table->unsignedSmallInteger('current_step_id')->default(0)->comment('当前步骤');
            $table->json('data')->nullable()->comment('对局数据');
            $table->string('status')->default(\App\Models\RoomRound::STATUS_PLAYING)->comment('状态');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_rounds');
    }
};
