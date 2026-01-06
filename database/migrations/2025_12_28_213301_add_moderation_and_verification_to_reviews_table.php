<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
        });

        // 2) Extend enum values (MySQL) to support the new workflow
        // If your status is enum currently:
        DB::statement("ALTER TABLE `reviews` MODIFY `status` ENUM(
            'pending_moderation',
            'pending_verification',
            'approved',
            'rejected'
        ) NOT NULL DEFAULT 'pending_moderation'");
    }

    public function down(): void
    {
        // rollback enum back to old (optional: keep as-is)
        DB::statement("ALTER TABLE `reviews` MODIFY `status` ENUM(
            'pending',
            'approved',
            'rejected'
        ) NOT NULL DEFAULT 'pending'");

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropConstrainedForeignId('moderated_by');
            $table->dropColumn('moderated_at');

            $table->dropConstrainedForeignId('verified_by');
            $table->dropColumn('verified_at');

            $table->dropColumn('rejection_reason');
        });
    }
};
