<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    protected $fillable = ['
        billing_date,
        payment_date,
        company_name,
        invoice_number,
        net,
        tax,
        gross
    '];

    protected $hidden = ['created_at', 'updated_at'];

    use HasFactory;
}
