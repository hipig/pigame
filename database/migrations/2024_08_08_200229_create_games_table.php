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
        Schema::create('games', function (Blueprint $table) {
            $table->comment('游戏');
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable()->comment('分类ID');
            $table->string('name')->comment('名称');
            $table->string('key', 64)->unique()->comment('游戏标识');
            $table->string('icon')->nullable()->comment('图标');
            $table->unsignedSmallInteger('min_player')->default(0)->comment('最小人数');
            $table->unsignedSmallInteger('max_player')->default(0)->comment('最大人数');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->unsignedTinyInteger('status')->default(\App\Models\Model::STATUS_ENABLE)->comment('状态');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
