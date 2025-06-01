<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneOwnerHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_id',
        'customer_id',
        'purchase_date',
        'sale_date',
    ];

    /**
     * Get the phone associated with this history entry.
     */
    public function phone()
    {
        return $this->belongsTo(Phone::class);
    }

    /**
     * Get the customer associated with this history entry.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
