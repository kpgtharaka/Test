<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairPart extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'supplier',
        'cost_price',
        'selling_price',
        'stock_quantity',
        'low_stock_threshold',
    ];

    /**
     * The repairs that have used this part.
     */
    public function repairs()
    {
        return $this->belongsToMany(Repair::class, 'repair_job_parts')
                    ->withPivot(['quantity_used', 'price_at_time_of_repair'])
                    ->withTimestamps();
    }

    /**
     * Get the detailed breakdown of jobs this part was used in.
     */
    public function jobParts()
    {
        return $this->hasMany(RepairJobPart::class);
    }
}
