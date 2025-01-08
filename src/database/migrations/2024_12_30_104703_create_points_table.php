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
        Schema::table('users', function (Blueprint $table) {
        $table->unsignedInteger('current_points')->default(0);
        });

        Schema::create('point_generations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('points');
            $table->foreignId('owner_id')->constrained('users');
            $table->string('generation_area'); // 'signup' | 'reference'
            $table->nullableMorphs('generator');
            $table->json('calc_config')->nullable();
            $table->timestamp('credited_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('point_redeems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users');
            $table->unsignedInteger('points');
            $table->timestamp('redeemed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_redeems');
        Schema::dropIfExists('point_generations');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('points');
        });
        
    }
};
