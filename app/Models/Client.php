<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'company_name',
        'company_code',
        'address',
        'vat_code',
        'phone'
    ];
}