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
    Schema::create('pengajuan', function (Blueprint $table) {

        $table->increments('id');

        $table->unsignedInteger('user_id');

$table->foreign('user_id')
      ->references('id')
      ->on('users')
      ->onDelete('cascade');

        $table->string('kategori_bantuan');

        $table->string('nama_lengkap');
        $table->enum('alamat_sesuai_kk', [
        'Ya',
        'Tidak'
        ])->nullable();

        $table->text('alamat_sekarang')->nullable();

        $table->string('no_hp');

        $table->date('tanggal_lahir');

        $table->enum('jenis_kelamin', [
            'Laki-laki',
            'Perempuan'
        ]);

        $table->enum('status_pernikahan', [
            'Belum Menikah',
            'Menikah',
        ])->nullable();

        $table->integer('jumlah_saudara')->nullable();

        $table->decimal('penghasilan_orang_tua', 15, 2)->nullable();

        $table->decimal('penghasilan', 15, 2)->nullable();

        $table->enum('status_rumah', [
            'Milik Sendiri/Menumpang',
            'Sewa/Kontrak/Kredit'
        ])->nullable();

        $table->integer('jumlah_tanggungan')->nullable();

        $table->enum('orangtua_lengkap', [
            'Ya',
            'Tidak'
        ])->nullable();

        $table->string('file_akta_kelahiran')->nullable();

        $table->string('file_kk')->nullable();

        $table->string('file_domisili')->nullable();

        $table->string('file_sktm')->nullable();

        $table->string('file_kematian_ayah')->nullable();

        $table->string('file_kematian_ibu')->nullable();

        $table->string('file_kematian_orangtua')->nullable();
        
        $table->string('file_kematian_pasangan')->nullable();
        
        $table->string('file_tidak_menikah')->nullable();

        $table->string('file_tidak_bekerja')->nullable();

        $table->enum('status', [
            'Menunggu',
            'Diterima',
            'Ditolak'
        ])->default('Menunggu');

        $table->enum('status_kelayakan', [
            'Menunggu',
            'Layak',
            'Tidak Layak'
        ])->default('Menunggu');


        $table->text('catatan_admin')->nullable();

        $table->date('tanggal_pembaruan_dokumen')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
