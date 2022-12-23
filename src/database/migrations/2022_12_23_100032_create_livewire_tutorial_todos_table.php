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
        Schema::create('livewire_tutorial_todos', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->comment('タイトル');
            $table->string('content', 255)->comment('コンテンツ');
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
        Schema::dropIfExists('livewire_tutorial_todos');
    }
};
