<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'city',
        'postcode',
        'cc_number',
        'cc_expiry_month',
        'cc_expiry_year',
        'cc_cvc'
    ];

    public function tickets()
    {
        return $this->hasMany('App\Ticket');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // Calculated Attributes
    public function getTotalCostAttribute()
    {
        $total = 0.0;
        foreach ($this->tickets()->get()->all() as $ticket) {
            $total += $ticket->total_cost;
        }
        return ($total);
    }
}
