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
            $table->timestamp('start_at')->nullable();
            $table->timestamp('completion_at')->nullable();
            $table->timestamp('deliver_at')->nullable();
            $table->unsignedInteger('committed_budget'); // in INR (paise)
            $table->unsignedInteger('initial_payment'); // in INR (paise)
            $table->unsignedTinyInteger('priority_level')->default(1); // 1 - 10
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
