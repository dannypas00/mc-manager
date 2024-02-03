<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servers', static function (Blueprint $table) {
            /** @see \App\Enums\ServerType */
            $table->enum('type', [1, 2, 3])->after('enabled')->default(1);
        });
    }

    public function down(): void
    {
        Schema::table('servers', static function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
