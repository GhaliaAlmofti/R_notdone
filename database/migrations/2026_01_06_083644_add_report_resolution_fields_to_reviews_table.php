<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->enum('report_status', ['pending', 'resolved_valid', 'resolved_invalid'])
                ->nullable()
                ->after('reported_by');

            $table->timestamp('report_resolved_at')->nullable()->after('report_status');

            $table->foreignId('report_resolved_by')->nullable()
                ->after('report_resolved_at')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropConstrainedForeignId('report_resolved_by');
            $table->dropColumn(['report_resolved_at', 'report_status']);
        });
    }
};
