<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_id',
        'customer_id',
        'description_of_problem',
        'status',
        'estimated_cost',
        'final_cost',
        'date_received',
        'date_completed',
        'notes',
    ];

    /**
     * Get the phone that is being repaired.
     */
    public function phone()
    {
        return $this->belongsTo(Phone::class);
    }

    /**
     * Get the customer who brought the phone for repair.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * The parts used in this repair.
     */
    public function partsUsed()
    {
        return $this->belongsToMany(RepairPart::class, 'repair_job_parts')
                    ->withPivot(['quantity_used', 'price_at_time_of_repair'])
                    ->withTimestamps();
    }

    /**
     * Get the detailed breakdown of parts used in this repair job.
     */
    public function jobParts()
    {
        return $this->hasMany(RepairJobPart::class);
    }
}
