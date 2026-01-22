<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $fillable = [
        'code', 'type', 'value', 'min_purchase', 'max_discount',
        'usage_limit', 'usage_count', 'start_date', 'end_date',
        'is_active', 'description'
    ];
}