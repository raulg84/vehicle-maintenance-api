<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaintenanceRule;

class MaintenanceRuleSeeder extends Seeder
{
    /**
     * Reglas de mantenimiento predefinidas para vehículos de combustión y eléctricas.
     */
    public function run(): void
    {
        MaintenanceRule::updateOrCreate(
            ['maintenance_key' => 'oil_change'], // clave única para evitar duplicados
            [
                'name' => 'Cambio de aceite',
                'maintenance_key' => 'oil_change',
                'description' => 'Sustitución de aceite del motor',
                'applies_to_powertrain' => 'combustion',
                'interval_km' => 10000,
                'interval_days' => 365,
                'warning_km' => 2000,
                'warning_days' => 60,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'is_active' => true,
            ]
        );

        MaintenanceRule::updateOrCreate(
            ['maintenance_key' => 'air_filter'], // clave única para evitar duplicados
            [
                'name' => 'Filtro de aire',
                'maintenance_key' => 'air_filter',
                'description' => 'Cambio de filtro de aire',
                'applies_to_powertrain' => 'combustion',
                'interval_km' => 15000,
                'interval_days' => 365,
                'warning_km' => 3000,
                'warning_days' => 90,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
                'is_active' => true,
            ]
        );

        MaintenanceRule::updateOrCreate(
            ['maintenance_key' => 'cabin_filter'], // clave única para evitar duplicados
            [
                'name' => 'Filtro habitáculo',
                'maintenance_key' => 'cabin_filter',
                'description' => 'Cambio de filtro del habitáculo',
                'applies_to_powertrain' => 'all',
                'interval_km' => 15000,
                'interval_days' => 365,
                'warning_km' => 3000,
                'warning_days' => 90,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
                'is_active' => true,
            ]
        );

        MaintenanceRule::updateOrCreate(
            ['maintenance_key' => 'brake_check'], // clave única para evitar duplicados
            [
                'name' => 'Revisión de frenos',
                'maintenance_key' => 'brake_check',
                'description' => 'Inspección de sistema de frenos',
                'applies_to_powertrain' => 'all',
                'interval_km' => 30000,
                'interval_days' => 730,
                'warning_km' => 5000,
                'warning_days' => 120,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
                'is_active' => true,
            ]
        );

        MaintenanceRule::updateOrCreate(
            ['maintenance_key' => 'general_check'], // clave única para evitar duplicados
            [
                'name' => 'Revisión general',
                'maintenance_key' => 'general_check',
                'description' => 'Revisión general del vehículo',
                'applies_to_powertrain' => 'all',
                'interval_km' => null,
                'interval_days' => 365,
                'warning_km' => null,
                'warning_days' => 60,
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
                'is_active' => true,
            ]
        );
    }
}