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
     public function index(Request $request)
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
}
