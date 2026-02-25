<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('customer')->after('password'); // super_admin, admin, manager, customer
            $table->string('phone')->nullable()->after('role');
            $table->string('avatar')->nullable()->after('phone');
            $table->boolean('is_banned')->default(false)->after('avatar');
            $table->text('ban_reason')->nullable()->after('is_banned');
            $table->timestamp('banned_at')->nullable()->after('ban_reason');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'avatar', 'is_banned', 'ban_reason', 'banned_at']);
        });
    }
};
