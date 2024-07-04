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
        Schema::table('report_dailies', function (Blueprint $table) {
            $table->integer('total_production')->nullable()->after('report_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_dailies', function (Blueprint $table) {
            $table->dropColumn('total_production');
        });
    }
};
