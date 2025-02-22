<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'artist_id',
        'purchase_type',
        'tokens',
        'transaction_id',
        'payment_method_id',
        'amount',
        'currency',
        'status',
        'latest_charge_id',
        'receipt_url',
    ];
}
