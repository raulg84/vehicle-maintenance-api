<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceRule;

class MaintenanceRuleController extends Controller
{
    /**
     * Listar reglas de mantenimiento activas, opcionalmente filtrando por tipo de tren motriz.
     */
    // public function index(Request $request)
    // {
    //     $powertrainType = $request->query('powertrain_type');

    //     $query = MaintenanceRule::query()
    //         ->where('is_active', true)
    //         ->orderBy('sort_order');

    //     if ($powertrainType) {
    //         $query->where(function ($q) use ($powertrainType) {
    //             $q->where('applies_to_powertrain', 'all')
    //                 ->orWhere('applies_to_powertrain', $powertrainType);
    //         });
    //     }

    //     return response()->json($query->get());
    // }

    public function index()
    {
        return response()->json(
            MaintenanceRule::orderBy('sort_order')->get()
        );
    }

    /**
     * Listar reglas de mantenimiento activas, opcionalmente filtrando por tipo de tren motriz.
     */
    public function active(Request $request)
    {
        $powertrainType = $request->query('powertrain_type');
        $query = MaintenanceRule::query()
            ->where('is_active', true)
            ->orderBy('sort_order');

        if ($powertrainType) {
            $query->where(function ($q) use ($powertrainType) {
                $q->where('applies_to_powertrain', 'all')
                    ->orWhere('applies_to_powertrain', $powertrainType);
            });
        }

        return response()->json($query->get());
    }

    /**
     * Mostrar una regla de mantenimiento concreta.
     */
    public function show($id)
    {
        $rule = MaintenanceRule::findOrFail($id);

        return response()->json($rule);
    }

    /**
     * Crear una nueva regla de mantenimiento.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'maintenance_key' => 'required|string|max:100|unique:maintenance_rules',
            'description' => 'nullable|string',

            'applies_to_powertrain' => 'required|in:all,combustion,hybrid,electric',

            'interval_km' => 'nullable|integer|min:0',
            'interval_days' => 'nullable|integer|min:0',

            'warning_km' => 'nullable|integer|min:0',
            'warning_days' => 'nullable|integer|min:0',

            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $rule = MaintenanceRule::create($validated);

        return response()->json($rule, 201);
    }

    /**
     * Editar una regla de mantenimiento concreta.
     */
    public function update(Request $request, $id)
    {
        $rule = MaintenanceRule::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'maintenance_key' => "required|string|max:100|unique:maintenance_rules,maintenance_key,$id",
            'description' => 'nullable|string',

            'applies_to_powertrain' => 'required|in:all,combustion,hybrid,electric',

            'interval_km' => 'nullable|integer|min:0',
            'interval_days' => 'nullable|integer|min:0',

            'warning_km' => 'nullable|integer|min:0',
            'warning_days' => 'nullable|integer|min:0',

            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $rule->update($validated);

        return response()->json($rule);
    }

    /**
     * Activar/desactivar una regla de mantenimiento.
     */
    public function toggle($id)
    {
        $rule = MaintenanceRule::findOrFail($id);

        $rule->is_active = !$rule->is_active;
        $rule->save();

        return response()->json($rule);
    }
}
