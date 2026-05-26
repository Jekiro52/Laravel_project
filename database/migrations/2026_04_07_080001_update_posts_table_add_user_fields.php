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
        if (!Schema::hasTable('posts')) {
            return;
        }

        $hasUserId = Schema::hasColumn('posts', 'user_id');
        $hasTitle = Schema::hasColumn('posts', 'title');
        $hasContent = Schema::hasColumn('posts', 'content');

        Schema::table('posts', function (Blueprint $table) use ($hasUserId, $hasTitle, $hasContent) {
            if (!$hasUserId) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!$hasTitle) {
                $table->string('title')->nullable()->after('user_id');
            }

            if (!$hasContent) {
                $table->text('content')->nullable()->after('title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('posts')) {
            return;
        }

        $hasUserId = Schema::hasColumn('posts', 'user_id');
        $hasTitle = Schema::hasColumn('posts', 'title');
        $hasContent = Schema::hasColumn('posts', 'content');

        if ($hasUserId) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }

        Schema::table('posts', function (Blueprint $table) use ($hasUserId, $hasTitle, $hasContent) {
            if ($hasContent) {
                $table->dropColumn('content');
            }

            if ($hasTitle) {
                $table->dropColumn('title');
            }

            if ($hasUserId) {
                $table->dropColumn('user_id');
            }
        });
    }
};
