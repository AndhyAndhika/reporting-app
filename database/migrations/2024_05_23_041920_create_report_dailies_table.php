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
        Schema::create('report_dailies', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->foreignId('parts_id')->nullable()->constrained('parts');
            $table->foreignId('rejects_id')->nullable()->constrained('rejects');
            $table->integer('qty');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_dailies');
    }
};
