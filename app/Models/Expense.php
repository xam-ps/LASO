<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['
        billing_date,
        payment_date,
        company_name,
        product_name,
        invoice_number,
        net,
        tax,
        gross,
    '];

    public function costType()
    {
        return $this->belongsTo(CostType::class);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    use HasFactory;
}
