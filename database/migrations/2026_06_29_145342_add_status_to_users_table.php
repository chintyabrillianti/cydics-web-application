<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->enum('status_donatur', [
                'Aktif',
                'Tidak Aktif'
            ])->default('Aktif')->after('role');

            $table->timestamp('last_donation_at')
                ->nullable()
                ->after('status_donatur');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'status_donatur',
                'last_donation_at'
            ]);

        });
    }
};
