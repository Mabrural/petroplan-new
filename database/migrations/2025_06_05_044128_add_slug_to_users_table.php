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
        Schema::table('users', function (Blueprint $table) {
            // $table->uuid('slug')->nullable()->after('id');
        });

        // Generate UUID untuk data yang sudah ada
        // \Illuminate\Support\Facades\DB::table('users')->whereNull('slug')->each(function ($user) {
        //     \Illuminate\Support\Facades\DB::table('users')
        //         ->where('id', $user->id)
        //         ->update(['slug' => Str::uuid()]);
        // });

        // Set kolom slug menjadi tidak nullable dan unique
        Schema::table('users', function (Blueprint $table) {
            // $table->uuid('slug')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
