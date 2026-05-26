<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'client_id',
        'invoice_number',
        'total_without_vat',
        'vat_amount',
        'total_with_vat'
    ];
}
