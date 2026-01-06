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
    Schema::create('schools', function (Blueprint $table) {
        $table->id();

        $table->string('name');
        $table->string('slug')->unique();

        $table->string('logo_path')->nullable();

        $table->string('email')->nullable();
        $table->string('phone', 20)->nullable();
        $table->string('address')->nullable();

        $table->string('area')->index();                // Benghazi areas
        $table->string('category')->index();            // private/public/international...
        $table->string('level')->index();               // kindergarten/primary/secondary...

        $table->string('president_name')->nullable();
        $table->string('fees_range')->nullable();       // ex: "1000–3000 LYD"
        $table->enum('gender_type', ['boys','girls','mixed'])->default('mixed');
        $table->string('curriculum')->nullable();       // national / international etc

        $table->foreignId('admin_user_id')->nullable()->constrained('users')->nullOnDelete();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
