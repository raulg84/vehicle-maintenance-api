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
        Schema::create('vehicles', function (Blueprint $table) {
        $table->id();

        // Relación con usuario
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();

        // Identificación del vehículo
        $table->string('alias')->nullable(); // Nombre personalizado (ej: "BMW diario")
        $table->string('make');              // Marca
        $table->string('model');             // Modelo
        $table->year('year');                // Año de fabricación

        // Tipo de motorización (clave para reglas)
        $table->enum('powertrain_type', ['combustion', 'hybrid', 'electric']);

        // Estado del vehículo
        $table->integer('current_mileage');  // Km actuales
        $table->date('in_service_date')->nullable(); // Fecha de puesta en servicio

        // Control lógico
        $table->boolean('active')->default(true);

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
