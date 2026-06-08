<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qc_reports', function (Blueprint $table) {
            $table->decimal('rejected_weight_kg', 10, 2)->default(0)->after('actual_weight_kg');
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('qc_reports', function (Blueprint $table) {
            $table->dropColumn('rejected_weight_kg');
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
