<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. (Tombol Membangun)
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Menambah kolom lantai setelah kolom location
            $table->string('floor')->nullable()->after('location');
        });
    }

    /**
     * Reverse the migrations. (Tombol Membongkar)
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Perintah untuk MENCABUT KEMBALI kolom lantai jika fitur dibatalkan
            $table->dropColumn('floor');
        });
    }
};
