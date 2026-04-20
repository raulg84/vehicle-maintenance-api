<?php

namespace App\Services;

use App\Models\Maintenance;
use App\Models\MaintenanceRule;
use App\Models\Vehicle;
use Carbon\Carbon;

class MaintenanceStatusService
{
    /**
     * Construir el estado de mantenimiento de un vehículo.
     */
    public function buildVehicleStatus(Vehicle $vehicle): array
    {
        $rules = MaintenanceRule::query()
            ->where('is_active', true)
            ->where(function ($query) use ($vehicle) {
                $query->where('applies_to_powertrain', 'all')
                    ->orWhere('applies_to_powertrain', $vehicle->powertrain_type);
            })
            ->orderBy('sort_order')
            ->get();

        $ruleStatuses = [];

        foreach ($rules as $rule) {
            $ruleStatuses[] = $this->evaluateRule($vehicle, $rule);
        }

        $globalStatus = $this->calculateGlobalStatus($ruleStatuses);
        $summary = $this->buildSummary($globalStatus);
        $nextAction = $this->buildNextAction($ruleStatuses, $globalStatus);

        return [
            'vehicle_id' => $vehicle->id,
            'vehicle_status' => $globalStatus,
            'summary' => $summary,
            'next_action' => $nextAction,
            'rules' => $ruleStatuses,
        ];
    }

    protected function evaluateRule(Vehicle $vehicle, MaintenanceRule $rule): array
    {
        $lastMaintenance = Maintenance::query()
            ->where('vehicle_id', $vehicle->id)
            ->where('maintenance_rule_id', $rule->id)
            ->orderByDesc('performed_at')
            ->orderByDesc('id')
            ->first();

        if (!$lastMaintenance) {
            return [
                'rule_id' => $rule->id,
                'name' => $rule->name,
                'maintenance_key' => $rule->maintenance_key,
                'status' => 'upcoming',
                'status_label' => 'Próximo',
                'last_maintenance_date' => null,
                'last_maintenance_km' => null,
                'current_vehicle_km' => $vehicle->current_mileage,
                'remaining_km' => null,
                'remaining_days' => null,
            ];
        }

        $daysSinceLast = Carbon::parse($lastMaintenance->performed_at)->diffInDays(now());
        $kmSinceLast = max(0, $vehicle->current_mileage - $lastMaintenance->mileage_at_service);

        $remainingKm = $rule->interval_km !== null
            ? $rule->interval_km - $kmSinceLast
            : null;

        $remainingDays = $rule->interval_days !== null
            ? $rule->interval_days - $daysSinceLast
            : null;

        $isOverdueByKm = $rule->interval_km !== null && $kmSinceLast >= $rule->interval_km;
        $isOverdueByDays = $rule->interval_days !== null && $daysSinceLast >= $rule->interval_days;

        $isUpcomingByKm = false;
        $isUpcomingByDays = false;

        if ($rule->warning_km !== null && $rule->interval_km !== null) {
            $isUpcomingByKm = $kmSinceLast >= ($rule->interval_km - $rule->warning_km);
        }

        if ($rule->warning_days !== null && $rule->interval_days !== null) {
            $isUpcomingByDays = $daysSinceLast >= ($rule->interval_days - $rule->warning_days);
        }

        if ($isOverdueByKm || $isOverdueByDays) {
            $status = 'overdue';
            $statusLabel = 'Vencido';
        } elseif ($isUpcomingByKm || $isUpcomingByDays) {
            $status = 'upcoming';
            $statusLabel = 'Próximo';
        } else {
            $status = 'ok';
            $statusLabel = 'OK';
        }

        return [
            'rule_id' => $rule->id,
            'name' => $rule->name,
            'maintenance_key' => $rule->maintenance_key,
            'status' => $status,
            'status_label' => $statusLabel,
            'last_maintenance_date' => $lastMaintenance->performed_at?->toDateString(),
            'last_maintenance_km' => $lastMaintenance->mileage_at_service,
            'current_vehicle_km' => $vehicle->current_mileage,
            'remaining_km' => $remainingKm,
            'remaining_days' => $remainingDays,
        ];
    }

    /**
     * Calcular el estado global del vehículo basado en los estados individuales de las reglas.
     */
    protected function calculateGlobalStatus(array $ruleStatuses): string
    {
        if (collect($ruleStatuses)->contains(fn ($rule) => $rule['status'] === 'overdue')) {
            return 'overdue';
        }

        if (collect($ruleStatuses)->contains(fn ($rule) => $rule['status'] === 'upcoming')) {
            return 'upcoming';
        }

        return 'ok';
    }

    /**
     * Construir un resumen amigable del estado global.
     */
    protected function buildSummary(string $globalStatus): array
    {
        return match ($globalStatus) {
            'overdue' => [
                'label' => 'Vencido',
                'message' => 'El vehículo presenta mantenimientos que requieren atención.',
            ],
            'upcoming' => [
                'label' => 'Próximo',
                'message' => 'Se aproxima una revisión recomendada.',
            ],
            default => [
                'label' => 'OK',
                'message' => 'No se detectan mantenimientos urgentes.',
            ],
        };
    }

    /**
     * Construir la próxima acción recomendada basada en los estados de las reglas.
     */
    protected function buildNextAction(array $ruleStatuses, string $globalStatus): array
    {
        $priorityOrder = [
            'overdue' => 1,
            'upcoming' => 2,
            'ok' => 3,
        ];

        $candidate = collect($ruleStatuses)
            ->sortBy(fn ($rule) => $priorityOrder[$rule['status']] ?? 99)
            ->first();

        if (!$candidate) {
            return [
                'rule_id' => null,
                'maintenance_key' => null,
                'title' => 'Sin acciones urgentes',
                'message' => 'No hay acciones recomendadas en este momento.',
            ];
        }

        $title = match ($candidate['status']) {
            'overdue' => 'Revisar ' . $candidate['name'],
            'upcoming' => 'Próxima revisión de ' . $candidate['name'],
            default => 'Sin acciones urgentes',
        };

        $message = match ($candidate['status']) {
            'overdue' => 'Se han superado los intervalos recomendados para esta operación.',
            'upcoming' => 'Se aproxima el intervalo recomendado para esta operación.',
            default => 'No se detectan acciones inmediatas recomendadas.',
        };

        return [
            'rule_id' => $candidate['rule_id'],
            'maintenance_key' => $candidate['maintenance_key'],
            'title' => $title,
            'message' => $message,
        ];
    }
}