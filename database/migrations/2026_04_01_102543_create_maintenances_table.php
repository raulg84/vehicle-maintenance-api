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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();

            // Relación con vehículo
            $table->foreignId('vehicle_id')
                ->constrained()
                ->cascadeOnDelete();

            // Relación opcional con regla de mantenimiento (para trazabilidad)
            $table->foreignId('maintenance_rule_id')
                ->nullable()
                ->constrained('maintenance_rules')
                ->nullOnDelete();

            // Tipo de mantenimiento realizado (ej: "oil_change", "tire_rotation", etc.)
            $table->string('maintenance_type');

            $table->date('performed_at');
            $table->integer('mileage_at_service');
            $table->decimal('cost', 10, 2)->nullable();
            $table->text('notes')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};