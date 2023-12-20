<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelAllowance extends Model
{
    protected $fillable = ['
        travel_date,
        start,
        end,
        destination,
        reason,
        company,
        distance,
        notes,
        refund
    '];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    use HasFactory;
}
