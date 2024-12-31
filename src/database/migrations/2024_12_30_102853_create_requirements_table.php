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
        Schema::create('requirements', function (Blueprint $table) {
            $table->id();
            $table->string('referal_code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('service_id')->constrained('services');
            $table->foreignId('owner_id')->constrained('users');
            $table->foreignId('referer_id')->nullable()->constrained('users');
            $table->foreignId('admin_id')->nullable()->constrained('users');
            $table->timestamp('required_at')->nullable();
            $table->unsignedInteger('expecting_budget'); // in INR (paise)
            $table->string('status')->default('pending');
            $table->foreignId('project_id')->nullable()->constrained('projects');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requirements');
    }
};
