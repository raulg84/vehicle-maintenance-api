<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\MaintenanceRule;
use App\Models\Vehicle;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'maintenance_rule_id',
        'maintenance_type',
        'performed_at',
        'mileage_at_service',
        'cost',
        'notes',
    ];

    protected $casts = [
        'performed_at' => 'date',
        'mileage_at_service' => 'integer',
        'cost' => 'decimal:2',
    ];

    /**
     * Relación: un mantenimiento pertenece a un vehículo.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Relación: un mantenimiento puede estar asociado a una regla de mantenimiento (opcional).
     */
    public function rule(): BelongsTo
    {
        return $this->belongsTo(MaintenanceRule::class, 'maintenance_rule_id');
    }
}