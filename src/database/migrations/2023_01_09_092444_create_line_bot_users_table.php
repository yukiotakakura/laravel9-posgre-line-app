<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_bot_users', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->foreignId('line_provider_id')->comment('LINEプロバイダーID');
            $table->foreignId('line_bot_id')->nullable()->comment('LINEボットID');
            $table->foreignId('user_id')->comment('ユーザID');
            $table->foreignId('user_id')->comment('ユーザID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('line_bot_users');
    }
};
