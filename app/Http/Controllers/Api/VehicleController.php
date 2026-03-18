<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\User;

class VehicleController extends Controller
{
   /**
     * Listar vehículos del usuario autenticado.
     */
    public function index(Request $request)
    {
        //$user = $request->user();
        $user = \App\Models\User::first();

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
        $validated = $request->validate([
            'alias' => 'nullable|string|max:255',
            'make' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'powertrain_type' => 'required|in:combustion,hybrid,electric',
            'current_mileage' => 'required|integer|min:0',
            'in_service_date' => 'nullable|date',
        ]);

        $vehicle = Vehicle::create([
            ...$validated,
            //'user_id' => $request->user()->id,
            'user_id' => 1,
            'active' => true,
        ]);

        return response()->json($vehicle, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
