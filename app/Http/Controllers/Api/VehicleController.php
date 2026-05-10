<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use App\Services\MaintenanceStatusService;

class VehicleController extends Controller
{
    /**
     * Listar vehículos del usuario.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $vehicles = Vehicle::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($vehicles);
    }



    /**
     * Crear un nuevo vehículo.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'alias' => 'nullable|string|max:255',
            'make' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'powertrain_type' => 'required|in:combustion,hybrid,electric',
            'current_mileage' => 'required|integer|min:0|max:2000000',
            'in_service_date' => 'nullable|date',
            'active' => 'sometimes|boolean',
        ]);

        $vehicle = Vehicle::create([
            ...$validated,
            'user_id' => $user->id,
            'active' => $validated['active'] ?? true,
        ]);

        return response()->json($vehicle, 201);
    }

    /**
     * Mostrar un vehículo concreto.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        $vehicle = Vehicle::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$vehicle) {
            return response()->json([
                'message' => 'Vehículo no encontrado'
            ], 404);
        }

        return response()->json($vehicle);
    }


    /**
     * Actualizar un vehículo.
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();

        $vehicle = Vehicle::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$vehicle) {
            return response()->json([
                'message' => 'Vehículo no encontrado'
            ], 404);
        }

        $validated = $request->validate([
            'alias' => 'nullable|string|max:255',
            'make' => 'sometimes|string|max:100',
            'model' => 'sometimes|string|max:100',
            'year' => 'sometimes|integer|min:1900|max:' . date('Y'),
            'powertrain_type' => 'sometimes|in:combustion,hybrid,electric',
            'current_mileage' => 'required|integer|min:0|max:2000000',
            'in_service_date' => 'nullable|date',
            'active' => 'sometimes|boolean',
        ]);

        $maxMaintenanceMileage = Maintenance::where('vehicle_id', $vehicle->id)
            ->max('mileage_at_service');

        if (
            $maxMaintenanceMileage !== null &&
            $validated['current_mileage'] < $maxMaintenanceMileage
        ) {
            return response()->json([
                'message' => 'El kilometraje actual no puede ser inferior al mayor kilometraje registrado en los mantenimientos.',
            ], 422);
        }

        $vehicle->update($validated);

        return response()->json($vehicle);
    }

    /**
     * Eliminar un vehículo.
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $vehicle = Vehicle::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$vehicle) {
            return response()->json([
                'message' => 'Vehículo no encontrado'
            ], 404);
        }

        $vehicle->delete();

        return response()->json([
            'message' => 'Vehículo eliminado correctamente'
        ]);
    }

    /**
     * Obtener el estado de mantenimiento de un vehículo.
     */
    public function maintenanceStatus($id, MaintenanceStatusService $maintenanceStatusService)
    {
        $vehicle = Vehicle::findOrFail($id);

        return response()->json(
            $maintenanceStatusService->buildVehicleStatus($vehicle)
        );
    }
}
