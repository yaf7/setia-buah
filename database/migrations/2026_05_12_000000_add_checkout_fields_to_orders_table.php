<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->string('shipping_province')->nullable()->after('shipping_address');
            $table->string('shipping_city')->nullable()->after('shipping_province');
            $table->string('shipping_postal_code', 10)->nullable()->after('shipping_city');
            $table->string('courier_name')->nullable()->after('payment_method');
            $table->string('courier_service')->nullable()->after('courier_name');
            $table->decimal('shipping_cost', 12, 2)->default(0)->after('courier_service');
            $table->decimal('subtotal_amount', 12, 2)->default(0)->after('shipping_cost');
            $table->string('payment_reference')->nullable()->unique()->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['payment_reference']);
            $table->dropColumn([
                'customer_phone',
                'shipping_province',
                'shipping_city',
                'shipping_postal_code',
                'courier_name',
                'courier_service',
                'shipping_cost',
                'subtotal_amount',
                'payment_reference',
            ]);
        });
    }
};
