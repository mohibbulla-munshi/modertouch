<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();
            // Shipping snapshot
            $table->string('shipping_name');
            $table->string('shipping_phone');
            $table->string('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_state')->nullable();
            $table->string('shipping_postal')->nullable();
            $table->string('shipping_country')->default('Bangladesh');
            // Financials
            $table->integer('subtotal')->comment('Subtotal in cents');
            $table->integer('discount')->default(0)->comment('Discount in cents');
            $table->integer('shipping_cost')->default(0)->comment('Shipping cost in cents');
            $table->integer('tax')->default(0)->comment('Tax in cents');
            $table->integer('total')->comment('Total in cents');
            $table->string('coupon_code')->nullable();
            // Payment
            $table->enum('payment_method', ['cod', 'bank_transfer', 'online'])->default('cod');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            // Status
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
