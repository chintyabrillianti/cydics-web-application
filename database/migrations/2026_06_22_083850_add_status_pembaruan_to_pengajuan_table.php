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
        Schema::table('pengajuan', function (Blueprint $table) {

            $table->enum(
                'status_pembaruan',
                [
                    'Belum',
                    'Menunggu',
                    'Perbaikan',
                    'Diterima',
                    'Ditolak'
                ]
            )->nullable();

            $table->date('tanggal_pengajuan_pembaruan')
                ->nullable();

            $table->date('batas_perbaikan')
                ->nullable();

            $table->integer('jumlah_perbaikan_pembaruan')
                ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            //
        });
    }
};
