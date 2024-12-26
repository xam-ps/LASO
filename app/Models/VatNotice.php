<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatNotice extends Model
{
    protected $fillable = ['
        notice_date,
        vat_received,
        vat_paid
    '];

    protected $hidden = ['created_at', 'updated_at'];

    use HasFactory;
}
