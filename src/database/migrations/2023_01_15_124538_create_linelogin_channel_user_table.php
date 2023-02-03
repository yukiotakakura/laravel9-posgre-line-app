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
        Schema::create('linelogin_channel_user', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('linelogin_channel_id')->constrained('linelogin_channels');
            $table->string('access_token', 255)->comment('アクセストークン(有効期間は30日)');
            $table->integer('expires_in')->comment('アクセストークンの有効期限が切れるまでの秒数');
            $table->string('id_token', 1000)->comment('ユーザー情報を含むJSONウェブトークン');
            $table->string('refresh_token', 100)->comment('新しいアクセストークンを取得するためのトークン(有効期間は90日)');
            $table->string('scope', 100)->comment('アクセストークンに付与されている権限。');
            $table->string('token_type', 50)->comment('トークンタイプ');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_linelogin_channel');
    }
};
