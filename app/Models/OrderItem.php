<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderItem;


class OrderItem extends Model
{
    use HasFactory;
    
     protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'supplier_price',
        'size',
    ];

    public function getSupplierTotal() {
        return $this->supplier_price * $this->quantity;
    }

    public function getTotal() {
        return $this->price * $this->quantity;
    }

    public function order() {
        return $this->belongsTo(Order::class);
    }

    // Assuming you have a Product model
    public function product()
     {
        return $this->belongsTo(Product::class);
    }
    
}

