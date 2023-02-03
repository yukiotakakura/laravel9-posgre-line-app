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
        Schema::create('linelogin_channels', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->foreignId('linedevelopers_provider_id')->constrained('linedevelopers_providers');
            // BIGINTEGER型、符号無し、ユニーク制約
            $table->bigInteger('channel_id')->unsigned()->unique()->comment('チャンネルID');
            $table->string('name', 20)->comment('チャンネル名');
            $table->string('channel_secret', 100)->comment('チェンネルシークレット');
            $table->foreignId('linebot_channel_id')->nullable()->comment('ボットリンクID')->constrained('linebot_channels');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('linelogin_channels');
    }
};
