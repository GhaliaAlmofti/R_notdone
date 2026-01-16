<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Add moderation + verification tracking
        Schema::table('reviews', function (Blueprint $table) {
            $table->timestamp('moderated_at')->nullable()->after('comment');
            $table->foreignId('moderated_by')->nullable()->after('moderated_at')
                ->constrained('users')->nullOnDelete();

            $table->timestamp('verified_at')->nullable()->after('moderated_by');
            $table->foreignId('verified_by')->nullable()->after('verified_at')
                ->constrained('users')->nullOnDelete();

            $table->text('rejection_reason')->nullable()->after('report_reason');

            // 2) Standard Laravel way to change the column
            // This works on SQLite and MySQL
            $table->string('status')
                ->default('pending_moderation')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropConstrainedForeignId('moderated_by');
            $table->dropColumn('moderated_at');
            $table->dropConstrainedForeignId('verified_by');
            $table->dropColumn('verified_at');
            $table->dropColumn('rejection_reason');

            // Revert status to a simple string or your old enum
            $table->string('status')->default('pending')->change();
        });
    }
};
