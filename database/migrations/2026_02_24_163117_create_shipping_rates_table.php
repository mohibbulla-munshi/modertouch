<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained('shipping_zones')->onDelete('cascade');
            $table->string('name');
            $table->integer('rate')->default(0)->comment('Shipping rate in cents');
            $table->integer('free_over')->nullable()->comment('Free shipping over amount in cents'); // free shipping if order total > this
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
    }
};
