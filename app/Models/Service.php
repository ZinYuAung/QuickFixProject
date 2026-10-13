<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //
    protected $table = 'services';

    protected $fillable = [
        'service_category_id',
        'name',
        'description',
        'base_price',
        'estimated_minutes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'estimated_minutes' => 'integer',
            'is_active' => 'boolean',
        ];
    }


    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    // Providers who offer services via pivot table
    public function providers()
    {
        return $this->belongsToMany(Provider::class, 'provider_services', 'service_id', 'provider_id');
    }
     public function serviceAreas()
    {
        return $this->belongsToMany(
            ServiceArea::class,
            'service_area_service',
            'service_id',
            'service_area_id'
        );
    }
}
