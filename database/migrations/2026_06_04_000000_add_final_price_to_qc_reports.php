<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qc_reports', function (Blueprint $table) {
            $table->decimal('final_price_per_kg', 12, 2)->nullable()->after('final_grade');
        });
    }

    public function down(): void
    {
        Schema::table('qc_reports', function (Blueprint $table) {
            $table->dropColumn('final_price_per_kg');
        });
    }
};
