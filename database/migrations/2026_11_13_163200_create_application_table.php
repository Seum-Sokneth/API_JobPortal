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
        Schema::create('application', function (Blueprint $table) {
            $table->id();

            $table->foreignId('job_post_id')
                ->constrained('job_post')->cascadeOnDelete();
            $table->foreignId('seeker_id')
                ->constrained('seekers_profiles')->cascadeOnDelete();
            $table->foreignId('resume_id')
                ->constrained('resume')->cascadeOnDelete();

            $table->text('cover_letter')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('applied_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application');
    }
};
