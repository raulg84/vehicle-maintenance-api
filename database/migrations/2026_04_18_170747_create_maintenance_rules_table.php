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
        Schema::create('maintenance_rules', function (Blueprint $table) {
            $table->id();

            // Nombre visible
            $table->string('name');

            // Clave interna única
            $table->string('maintenance_key')->unique();

            // Descripción opcional
            $table->text('description')->nullable();

            // Tipo de motorización al que aplica
            $table->enum('applies_to_powertrain', [
                'all',
                'combustion',
                'hybrid',
                'electric'
            ])->default('all');

            // Intervalos de mantenimiento
            $table->unsignedInteger('interval_km')->nullable();
            $table->unsignedInteger('interval_days')->nullable();

            // Umbrales de aviso
            $table->unsignedInteger('warning_km')->nullable();
            $table->unsignedInteger('warning_days')->nullable();

            // Estado de la regla
            $table->boolean('is_active')->default(true);

            // Orden en frontend (opcional, para mostrar antes unas reglas que otras)
            $table->unsignedInteger('sort_order')->default(0);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_rules');
    }
};
