<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servers', static function (Blueprint $table) {
            $table->string('player_list')->nullable()->comment('Comma-separated list of users')->after('maximum_players');
        });
    }

    public function down(): void
    {
        Schema::table('servers', static function (Blueprint $table) {
            $table->dropColumn('player_list');
        });
    }
};
