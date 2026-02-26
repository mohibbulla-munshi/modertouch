<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('shipping_rates');
        Schema::dropIfExists('shipping_zones');
    }

    public function down(): void
    {
        // Not necessary for cleanup
    }
};
