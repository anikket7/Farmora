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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->decimal('quantity', 10, 2);
            $table->enum('unit', ['kg', 'gram', 'dozen', 'piece', 'liter']);
            $table->decimal('price', 10, 2)->nullable();
            $table->enum('listing_type', ['buy', 'bid', 'both'])->default('buy');
            $table->enum('status', ['active', 'sold', 'inactive', 'pending'])->default('active');
            $table->date('harvest_date')->nullable();
            $table->string('origin_location')->nullable();
            $table->boolean('is_available')->default(true);
            $table->unsignedInteger('views_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
