<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('laporans', function (Blueprint $table) {

        $table->bigInteger('dana_yatim')->default(0);
        $table->bigInteger('bingkisan_yatim')->default(0);

        $table->bigInteger('dana_piatu')->default(0);
        $table->bigInteger('bingkisan_piatu')->default(0);

        $table->bigInteger('dana_yatim_piatu')->default(0);
        $table->bigInteger('bingkisan_yatim_piatu')->default(0);

        $table->bigInteger('dana_duafa_anak')->default(0);
        $table->bigInteger('bingkisan_duafa_anak')->default(0);

        $table->bigInteger('dana_duafa_lansia')->default(0);
        $table->bigInteger('bingkisan_duafa_lansia')->default(0);

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            //
        });
    }
};
