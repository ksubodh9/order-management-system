<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryBoy extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'capacity'];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'delivery_boy_order');
    }
}
