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
    Schema::create('reviews', function (Blueprint $table) {
        $table->id();

        $table->foreignId('school_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // parent

        $table->string('student_number'); // parent enters it

        // ratings 0-5
        $table->unsignedTinyInteger('hygiene');
        $table->unsignedTinyInteger('management');
        $table->unsignedTinyInteger('education_quality');
        $table->unsignedTinyInteger('parent_communication');

        $table->text('comment')->nullable();

        // workflow
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->timestamp('approved_at')->nullable();
        $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

        // reporting
        $table->boolean('is_reported')->default(false);
        $table->text('report_reason')->nullable();

        $table->timestamps();

        // prevent spam: same user reviewing same school multiple times (you can change later)
        $table->unique(['school_id', 'user_id']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
