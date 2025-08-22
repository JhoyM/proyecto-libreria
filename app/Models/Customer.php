<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'document_type', 'document_number',
        'phone', 'email', 'address', 'birth_date', 'customer_type',
        'discount_percentage', 'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'status' => 'boolean',
        'discount_percentage' => 'decimal:2',
    ];

    public function getFullNameAttribute(): string
    {
        return trim(($this->first_name ?? '').' '.($this->last_name ?? ''));
    }
}
