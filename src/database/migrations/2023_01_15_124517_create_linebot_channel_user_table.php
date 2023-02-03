<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('linebot_channel_user', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('linebot_channel_id')->constrained('linebot_channels');
            $table->string('line_user_id', 100)->comment('LineユーザID');
            // 友達の場合:true 友達じゃない場合:false
            $table->boolean('friend_flag')->default(false)->comment('友達状態');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_linebot_channel');
    }
};
