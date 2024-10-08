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
        Schema::create('server_user', static function (Blueprint $table) {
            $table->foreignId('server_id')->constrained()->references('id')->on('servers')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_user');
    }
};
