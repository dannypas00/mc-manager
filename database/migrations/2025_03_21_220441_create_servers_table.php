<?php

declare(strict_types=1);

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
        Schema::create('servers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('name');
            $table->boolean('enabled')->default(false);
            $table->string('minecraft_host')->nullable();
            $table->integer('minecraft_port')->nullable();
            $table->integer('rcon_port')->nullable();
            $table->string('rcon_password')->nullable();
            $table->string('ftp_host')->nullable();
            $table->integer('ftp_port')->nullable();
            $table->string('ftp_username')->nullable();
            $table->string('ftp_password')->nullable();
            $table->string('ssh_host')->nullable();
            $table->integer('ssh_port')->nullable();
            $table->text('ssh_key')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
