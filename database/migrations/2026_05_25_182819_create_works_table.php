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
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('series_id')->nullable();
            $table->unsignedInteger('series_number')->nullable();
            $table->string('title_internal');
            $table->string('title_public');
            $table->string('subtitle')->nullable();
            $table->string('author_name');
            $table->string('pen_name')->nullable();
            $table->string('genre')->nullable();
            $table->string('subgenre')->nullable();
            $table->string('work_type')->nullable();
            $table->string('original_language', 10)->nullable();
            $table->string('status')->default('idea');
            $table->string('target_audience')->nullable();
            $table->string('age_recommendation')->nullable();
            $table->text('description_internal')->nullable();
            $table->text('description_marketing')->nullable();
            $table->date('start_date')->nullable();
            $table->date('planned_publish_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('works');
    }
};
