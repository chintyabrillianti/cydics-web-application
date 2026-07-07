<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pengajuan', function (Blueprint $table) {

            $table->string('file_kk_perbaikan')->nullable();

            $table->string('file_domisili_perbaikan')->nullable();

            $table->string('file_sktm_perbaikan')->nullable();

            $table->string('file_bukti_rumah_perbaikan')->nullable();

            $table->string('file_kematian_ayah_perbaikan')->nullable();

            $table->string('file_kematian_ibu_perbaikan')->nullable();

            $table->string('file_kematian_orangtua_perbaikan')->nullable();

            $table->string('file_kematian_pasangan_perbaikan')->nullable();

            $table->string('file_bukti_cerai_perbaikan')->nullable();

            $table->string('file_tidak_menikah_perbaikan')->nullable();

            $table->string('file_tidak_bekerja_perbaikan')->nullable();

            $table->string('file_akta_kelahiran_perbaikan')->nullable();

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
