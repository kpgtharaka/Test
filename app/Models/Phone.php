<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Repair;
use App\Models\PhoneOwnerHistory;

class Phone extends Model
{
    use HasFactory;

    protected $fillable = [
        'imei',
        'serial_number',
        'brand',
        'model',
    ];

    /**
     * The owners that have owned the phone.
     */
    public function owners()
    {
        return $this->belongsToMany(Customer::class, 'phone_owner_histories')
                    ->withTimestamps()
                    ->withPivot(['purchase_date', 'sale_date']);
    }

    /**
     * Get the current owner of the phone.
     */
    public function currentOwner()
    {
        return $this->belongsToMany(Customer::class, 'phone_owner_histories')
                    ->wherePivotNull('sale_date')
                    ->withTimestamps();
    }

    /**
     * Get the repair history for the phone.
     */
    public function repairHistory()
    {
        return $this->hasMany(Repair::class);
    }

    /**
     * Get the ownership history for the phone.
     */
    public function ownershipHistory()
    {
        return $this->hasMany(PhoneOwnerHistory::class);
    }
}
