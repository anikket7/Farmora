<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'confirmed', 'packed', 'shipped', 'out_for_delivery', 'delivered', 'cancelled') DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'confirmed', 'packed', 'out_for_delivery', 'delivered', 'cancelled') DEFAULT 'pending'");
        }
    }
};
