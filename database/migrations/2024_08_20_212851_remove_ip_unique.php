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
            $table->dropUnique('servers_local_ip_public_ip_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servers', static function (Blueprint $table) {
            $table->unique(['local_ip', 'public_ip']);
        });
    }
};
