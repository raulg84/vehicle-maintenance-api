<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaintenanceRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'maintenance_key',
        'description',
        'applies_to_powertrain',
        'interval_km',
        'interval_days',
        'warning_km',
        'warning_days',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'interval_km' => 'integer',
        'interval_days' => 'integer',
        'warning_km' => 'integer',
        'warning_days' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Relación: una regla de mantenimiento puede tener muchos mantenimientos asociados.
     */
    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }
}
