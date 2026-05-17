<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('petani_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('fruit_type');
            $table->enum('grade', ['A', 'B', 'C']);
            $table->decimal('estimated_weight_kg', 10, 2);
            $table->decimal('price_per_kg', 12, 2);
            $table->date('harvest_date');
            $table->string('image')->nullable();
            $table->enum('status', ['pending', 'picked', 'accepted'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('petani_products');
    }
};
