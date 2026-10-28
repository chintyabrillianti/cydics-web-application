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
    Schema::create('laporan_tahunans', function (Blueprint $table) {

        $table->increments('id');

        // contoh : 2026
        $table->year('periode');

        // dokumen laporan tahunan
        $table->string('dokumen');

        // keterangan
        $table->text('keterangan')->nullable();

        // status publish
        $table->enum('status', [
            'Draft',
            'Published'
        ])->default('Draft');

        // waktu publish
        $table->timestamp('published_at')->nullable();

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_tahunans');
    }
};
