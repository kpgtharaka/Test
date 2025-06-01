<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Phone; // Assuming Phone model is in the same namespace
use App\Models\Repair;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'address',
    ];

    /**
     * Get the phones owned by the customer.
     */
    public function phones()
    {
        return $this->belongsToMany(Phone::class, 'phone_owner_histories')
                    ->withTimestamps()
                    ->withPivot(['purchase_date', 'sale_date']);
    }

    /**
     * Get the repairs associated with the customer.
     */
    public function repairs()
    {
        return $this->hasMany(Repair::class);
    }
}
