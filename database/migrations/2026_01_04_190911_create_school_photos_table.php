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
    Schema::create('school_photos', function (\Illuminate\Database\Schema\Blueprint $table) {
        $table->id();

        $table->foreignId('school_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->string('path'); // stored relative path like: schools/1/photos/xxx.jpg
        $table->string('caption')->nullable(); // optional, for later

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_photos');
    }
};
