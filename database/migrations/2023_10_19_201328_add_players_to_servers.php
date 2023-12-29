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
        Schema::table('servers', static function (Blueprint $table) {
            $table->integer('current_players')->default(0)->after('rcon_port');
            $table->integer('maximum_players')->default(0)->after('current_players');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servers', static function (Blueprint $table) {
            $table->dropColumn(['current_players', 'maximum_players']);
        });
    }
};
