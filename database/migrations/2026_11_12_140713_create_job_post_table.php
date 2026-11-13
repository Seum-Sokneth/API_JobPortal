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
        Schema::create('job_post', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                  ->constrained()->cascadeOnDelete();

            $table->foreignId('department_id')
                  ->constrained('job_departement')->restrictOnDelete();

            $table->timestamp('post_date')->nullable();
            $table->timestamp('deadline')->nullable();
            $table->string('employment_type')->nullable();
            $table->string('experience_level')->nullable();
            $table->decimal('minimum_salary', 12, 2)->nullable();
            $table->decimal('maximum_salary', 12, 2)->nullable();
            $table->text('about_role')->nullable();
            $table->text('responsibilities')->nullable();
            $table->text('required_qualifications')->nullable();
            $table->string('tech_stack')->nullable();
            $table->string('currency', 8)->nullable();
            $table->text('benefits')->nullable();
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_post');
    }
};
