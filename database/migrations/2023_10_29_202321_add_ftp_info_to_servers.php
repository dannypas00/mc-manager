<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servers', static function (Blueprint $table) {
            $table->boolean('enable_ftp')->after('rcon_port')->default(false);
            $table->boolean('is_sftp')->after('enable_ftp')->default(false);
            $table->boolean('use_ssh_auth')->after('is_sftp')->default(false);
            $table->integer('ftp_port')->after('use_ssh_auth')->nullable();
            $table->string('ftp_host')->after('ftp_port')->nullable();
            $table->string('ftp_username')->after('ftp_host')->comment('Contains private key when using ssh key auth')->nullable();
            $table->string('ftp_password')->after('ftp_username')->comment('Contains pass phrase when using ssh key auth')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('servers', static function (Blueprint $table) {
            $table->dropColumn([
                'enable_ftp',
                'is_sftp',
                'use_ssh_auth',
                'ftp_port',
                'ftp_host',
                'ftp_username',
                'ftp_password',
            ]);
        });
    }
};
