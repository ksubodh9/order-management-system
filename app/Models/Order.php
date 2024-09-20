<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['order_id'];

    public function deliveryBoys()
    {
        return $this->belongsToMany(DeliveryBoy::class, 'delivery_boy_order');
    }
}
