<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update petani_products status to support full supply chain flow
        // First drop the old enum constraint and add new expanded statuses
        Schema::table('petani_products', function (Blueprint $table) {
            $table->text('description')->nullable()->after('image');
        });

        // First expand enum to include both old and new values
        DB::statement("ALTER TABLE petani_products MODIFY COLUMN status ENUM('pending','picked','accepted','approved','procurement','shipping','received','qc_passed','cataloged','rejected') DEFAULT 'pending'");

        // Migrate existing data to new statuses
        DB::table('petani_products')->where('status', 'accepted')->update(['status' => 'cataloged']);
        DB::table('petani_products')->where('status', 'picked')->update(['status' => 'shipping']);

        // Now remove old enum values
        DB::statement("ALTER TABLE petani_products MODIFY COLUMN status ENUM('pending','approved','procurement','shipping','received','qc_passed','cataloged','rejected') DEFAULT 'pending'");

        // 2. Create procurement_transactions table
        Schema::create('procurement_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petani_product_id')->constrained('petani_products')->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
            $table->string('procurement_number')->unique();
            $table->decimal('agreed_price_per_kg', 12, 2);
            $table->decimal('agreed_weight_kg', 10, 2);
            $table->decimal('total_cost', 14, 2);
            $table->date('procurement_date');
            $table->enum('pickup_method', ['pickup', 'delivery'])->default('pickup');
            $table->enum('status', ['pending_pickup', 'in_transit', 'received'])->default('pending_pickup');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Add procurement_id to qc_reports for traceability
        Schema::table('qc_reports', function (Blueprint $table) {
            $table->foreignId('procurement_id')->nullable()->after('petani_product_id')
                  ->constrained('procurement_transactions')->nullOnDelete();
        });

        // 4. Add traceability fields to inventories
        Schema::table('inventories', function (Blueprint $table) {
            $table->foreignId('qc_report_id')->nullable()->after('id')
                  ->constrained('qc_reports')->nullOnDelete();
            $table->foreignId('procurement_id')->nullable()->after('qc_report_id')
                  ->constrained('procurement_transactions')->nullOnDelete();
            $table->string('batch_number')->nullable()->after('procurement_id');
        });
    }

    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropForeign(['qc_report_id']);
            $table->dropForeign(['procurement_id']);
            $table->dropColumn(['qc_report_id', 'procurement_id', 'batch_number']);
        });

        Schema::table('qc_reports', function (Blueprint $table) {
            $table->dropForeign(['procurement_id']);
            $table->dropColumn('procurement_id');
        });

        Schema::dropIfExists('procurement_transactions');

        Schema::table('petani_products', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        DB::statement("ALTER TABLE petani_products MODIFY COLUMN status ENUM('pending','picked','accepted') DEFAULT 'pending'");
    }
};
