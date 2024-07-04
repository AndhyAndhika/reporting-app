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
        Schema::create('reject_on_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parts_id')->nullable()->constrained('parts');
            $table->foreignId('rejects_id')->nullable()->constrained('rejects');
            $table->string('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reject_on_parts');
    }
};
