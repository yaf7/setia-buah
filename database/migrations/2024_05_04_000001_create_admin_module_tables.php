<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Audit Logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('action'); // created, updated, deleted
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->json('changes')->nullable();
            $table->timestamps();
        });

        // 2. Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->timestamps();
        });

        // 3. Inventories
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('fruit_type');
            $table->enum('grade', ['A', 'B', 'C']);
            $table->decimal('stock_kg', 10, 2);
            $table->date('expiry_date');
            $table->timestamps();
        });

        // 4. QC Reports
        Schema::create('qc_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petani_product_id')->constrained('petani_products')->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('actual_weight_kg', 10, 2);
            $table->enum('final_grade', ['A', 'B', 'C']);
            $table->enum('status', ['accepted', 'rejected']);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qc_reports');
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('audit_logs');
    }
};