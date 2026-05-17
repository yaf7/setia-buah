<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->decimal('price_per_kg', 12, 2)->default(35000)->after('expiry_date');
            $table->string('image')->nullable()->after('price_per_kg');
            $table->text('description')->nullable()->after('image');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->after('id');
            $table->text('shipping_address')->nullable()->after('customer_name');
            $table->string('payment_method')->nullable()->after('shipping_address');
            $table->enum('payment_status', ['unpaid', 'paid', 'failed'])->default('unpaid')->after('payment_method');
            $table->string('tracking_number')->nullable()->after('status');
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->foreignId('inventory_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity_kg', 10, 2);
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inventory_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('quantity_kg', 10, 2);
            $table->decimal('price_per_kg', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('carts');
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'shipping_address', 'payment_method', 'payment_status', 'tracking_number']);
        });
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn(['price_per_kg', 'image', 'description']);
        });
    }
};