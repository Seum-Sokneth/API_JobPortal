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
        Schema::table('employer_profile', function (Blueprint $table) {
            $table->renameColumn('name', 'company_name');
            $table->string('password')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employer_profile', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->string('phone')->nullable(false)->change();
            $table->string('password')->nullable(false)->change();
            $table->renameColumn('company_name', 'name');
        });
    }
};
