<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {

            $table->unsignedInteger('user_id')
                ->nullable()
                ->after('id');

        });

        // isi data lama
        DB::table('kegiatans')->update([
            'user_id' => 1
        ]);

        Schema::table('kegiatans', function (Blueprint $table) {

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->unsignedInteger('user_id')
                ->nullable(false)
                ->change();

        });
    }

    public function down(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {

            $table->dropForeign(['user_id']);

            $table->dropColumn('user_id');

        });
    }
};