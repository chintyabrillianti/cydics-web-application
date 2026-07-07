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
        Schema::create('laporans', function (Blueprint $table) {

            $table->id();

            // contoh : 2026-06
            $table->string('periode');

            // diinput admin
            $table->decimal('dana_keluar', 15, 2);

            // file pdf / jpg
            $table->string('dokumen');

            // opsional
            $table->text('keterangan')->nullable();

            // Draft / Published
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
        Schema::dropIfExists('laporans');
    }
};
