<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Maintenance;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * Campos asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'alias',
        'make',
        'model',
        'year',
        'powertrain_type',
        'current_mileage',
        'in_service_date',
        'active',
    ];

    /**
     * Conversión de tipos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'year' => 'integer',
        'current_mileage' => 'integer',
        'in_service_date' => 'date',
        'active' => 'boolean',
    ];

    /**
     * Relación: un vehículo pertenece a un usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: un vehículo puede tener varios mantenimientos.
     */
    public function maintenanceRecords(): HasMany
    {
        return $this->hasMany(MaintenanceRecord::class);
    }
}