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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->constrained('periodes')->onDelete('cascade');
            $table->foreignId('termin_id')->constrained('termins')->onDelete('cascade');
            $table->string('shipment_number');
            $table->foreignId('vessel_id')->constrained('vessels')->onDelete('cascade');
            $table->foreignId('spk_id')->constrained('spks')->onDelete('cascade');
            $table->string('location');
            $table->foreignId('fuel_id')->constrained('fuels')->onDelete('cascade');
            $table->integer('volume');
            $table->integer('p')->nullable();
            $table->integer('a')->nullable();
            $table->integer('b')->nullable();
            $table->date('completion_date');
            $table->string('lo')->nullable();
            $table->enum('status_shipment', ['in_progress', 'cancelled', 'completed', 'filling_completed'])->default('in_progress');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
