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
    Schema::table('pengajuan', function ($table) {

        $table->string('id_pengajuan')
              ->nullable()
              ->after('id');

    });
}

public function down(): void
{
    Schema::table('pengajuan', function ($table) {

        $table->dropColumn('id_pengajuan');

    });
}
};
