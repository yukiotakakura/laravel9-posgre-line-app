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
    public function up(): void
    {
        Schema::create('line_providers', function (Blueprint $table) {
            $table->bigIncrements('provider_id')->unsigned()->unique()->comment('LINEプロバイダーID');
            $table->string('name', 255)->comment('LINEプロバイダー名');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('line_providers');
    }
};
