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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('service_id')->constrained('services');
            $table->foreignId('admin_id')->constrained('users');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('expected_completed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->unsignedInteger('committed_budget'); // in INR (paise)
            $table->unsignedInteger('initial_payment'); // in INR (paise)
            $table->unsignedTinyInteger('priority_range')->default(1); // 1 - 10
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
