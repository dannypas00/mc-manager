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
        Schema::create('servers', static function (Blueprint $table) {
            $table->id();

            $table->boolean('enabled')->default(false);
            $table->unsignedInteger('port')->default(25565);
            $table->unsignedInteger('rcon_port')->default(25575);
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon')->default('dev-images/cavern-icon.png');
            $table->string('local_ip')->nullable();
            $table->string('public_ip')->nullable();
            $table->string('rcon_password', 512);
            $table->timestamps();

            $table->index('enabled');
            $table->unique(['local_ip', 'public_ip']);
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
