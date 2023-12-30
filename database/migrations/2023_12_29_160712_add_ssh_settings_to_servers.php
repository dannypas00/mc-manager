<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servers', static function (Blueprint $table) {
            $table->longText('ssh_key')->after('ftp_password')->nullable();
            $table->string('ssh_port')->after('ftp_password')->nullable();
            $table->string('ssh_username')->after('ftp_password')->nullable();
            $table->boolean('enable_ssh')->after('ftp_password')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('servers', static function (Blueprint $table) {
            $table->dropColumn(['ssh_port', 'ssh_username', 'ssh_key', 'enable_ssh']);
        });
    }
};
