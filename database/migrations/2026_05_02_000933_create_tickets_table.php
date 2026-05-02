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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
        $table->string('ticket_number')->unique();
        $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('technician_id')->nullable()->constrained('users')->onDelete('set null');
        $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
        $table->string('location');
        $table->string('title');
        $table->text('description');
        $table->string('image_before')->nullable(); // Foto bukti kerusakan
        $table->string('image_after')->nullable();  // Foto bukti selesai
        $table->enum('priority', ['low', 'medium', 'high'])->default('low');
        $table->enum('status', ['open', 'assigned', 'on_progress', 'resolved', 'closed'])->default('open');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
