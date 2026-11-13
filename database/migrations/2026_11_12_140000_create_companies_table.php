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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employer_id')->nullable()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('logo_company')->nullable();
            $table->string('title')->nullable();
            $table->string('total_employee')->nullable();
            $table->string('location')->nullable();
            $table->string('contact')->nullable();

            // Allowed values: pending, approved, rejected.
            $table->string('status')->default('pending');

            $table->string('website')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
