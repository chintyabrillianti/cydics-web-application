<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {

            $table->string('file_kk_pembaruan')->nullable();

            $table->string('file_sktm_pembaruan')->nullable();

            $table->string('file_domisili_pembaruan')->nullable();

            $table->string('file_bukti_rumah_pembaruan')->nullable();

            $table->string('file_bukti_cerai_pembaruan')->nullable();

            $table->string('file_kematian_pasangan_pembaruan')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {

            $table->dropColumn([

                'file_kk_pembaruan',

                'file_sktm_pembaruan',

                'file_domisili_pembaruan',

                'file_bukti_rumah_pembaruan',

                'file_bukti_cerai_pembaruan',

                'file_kematian_pasangan_pembaruan',

            ]);
        });
    }
};
