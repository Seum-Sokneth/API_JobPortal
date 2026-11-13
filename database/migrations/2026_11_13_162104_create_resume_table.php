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
        Schema::create('resume', function (Blueprint $table) {

           $table->foreignId('seeker_id')
               ->constrained('seekers_profiles')->cascadeOnDelete();

            $table->id();
            $table->timestamps();
            $table->string('fullname');
            $table->string('email');
            $table->string('phone');
            $table->string('file_path');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resume');
    }
};
