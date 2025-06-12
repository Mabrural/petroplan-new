<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->uuid('slug')->unique()->default(Str::uuid()); // UUID slug
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key ke users
            $table->enum('permission', ['admin_officer', 'operasion']); // ENUM permission
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
