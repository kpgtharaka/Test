<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairJobPart extends Model
{
    use HasFactory;

    protected $fillable = [
        'repair_id',
        'repair_part_id',
        'quantity_used',
        'price_at_time_of_repair',
    ];

    /**
     * Get the repair associated with this job part entry.
     */
    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }

    /**
     * Get the repair part associated with this job part entry.
     */
    public function repairPart()
    {
        return $this->belongsTo(RepairPart::class);
    }
}
