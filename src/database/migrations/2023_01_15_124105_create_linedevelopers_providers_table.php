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
        Schema::create('linedevelopers_providers', function (Blueprint $table) {
            $table->id()->comment('ID');
            // BIGINTEGER型、符号無し、ユニーク制約
            $table->bigInteger('provider_id')->unsigned()->unique()->comment('プロバイダーID');
            $table->string('name', 100)->comment('プロバイダー名');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('linedevelopers_providers');
    }
};
