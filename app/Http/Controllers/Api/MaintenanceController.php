<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    /**
     * Listar mantenimientos del usuario autenticado.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Maintenance::whereHas('vehicle', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->orderBy('performed_at', 'desc');

        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->integer('vehicle_id'));
        }

        $maintenances = $query->get();

        return response()->json($maintenances);
    }

    /**
     * Crear un nuevo mantenimiento.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'maintenance_rule_id' => ['nullable', 'integer', 'exists:maintenance_rules,id'],
            'maintenance_type' => ['required', 'string', 'max:255'],
            'performed_at' => ['required', 'date'],
            'mileage_at_service' => ['required', 'integer', 'min:0'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $vehicle = Vehicle::where('id', $validated['vehicle_id'])
            ->where('user_id', $user->id)
            ->first();

        if (!$vehicle) {
            return response()->json([
                'message' => 'Vehículo no encontrado o no pertenece al usuario autenticado.'
            ], 404);
        }

        $maintenance = Maintenance::create($validated);

        return response()->json($maintenance, 201);
    }

    /**
     * Mostrar un mantenimiento concreto.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        $maintenance = Maintenance::where('id', $id)
            ->whereHas('vehicle', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->first();

        if (!$maintenance) {
            return response()->json([
                'message' => 'Mantenimiento no encontrado'
            ], 404);
        }

        return response()->json($maintenance);
    }

    /**
     * Actualizar un mantenimiento.
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();

        $maintenance = Maintenance::where('id', $id)
            ->whereHas('vehicle', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->first();

        if (!$maintenance) {
            return response()->json([
                'message' => 'Mantenimiento no encontrado'
            ], 404);
        }

        $validated = $request->validate([
            'vehicle_id' => ['sometimes', 'integer', 'exists:vehicles,id'],
            'maintenance_rule_id' => ['nullable', 'integer'],
            'maintenance_type' => ['sometimes', 'string', 'max:255'],
            'performed_at' => ['sometimes', 'date'],
            'mileage_at_service' => ['sometimes', 'integer', 'min:0'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        if (isset($validated['vehicle_id'])) {
            $vehicle = Vehicle::where('id', $validated['vehicle_id'])
                ->where('user_id', $user->id)
                ->first();

            if (!$vehicle) {
                return response()->json([
                    'message' => 'El vehículo indicado no pertenece al usuario autenticado.'
                ], 404);
            }
        }

        $maintenance->update($validated);

        return response()->json($maintenance);
    }

    /**
     * Eliminar un mantenimiento.
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $maintenance = Maintenance::where('id', $id)
            ->whereHas('vehicle', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->first();

        if (!$maintenance) {
            return response()->json([
                'message' => 'Mantenimiento no encontrado'
            ], 404);
        }

        $maintenance->delete();

        return response()->json([
            'message' => 'Mantenimiento eliminado correctamente'
        ]);
    }
}
