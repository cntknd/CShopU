<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\OrderConfirmed;

class Order extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = [
    'user_id',
    'total_price',
    'status', // if you are using a status like 'pending' or 'completed'
    'confirmed_at',
    'paid_at',
];

protected $casts = [
    'confirmed_at' => 'datetime',
    'paid_at' => 'datetime',
];

public function items()
{
    return $this->hasMany(OrderItem::class);
}

public function getTotalAttribute()
{
    return $this->total_price;
}

public function user()
{
    return $this->belongsTo(User::class);
}

public function orderItems()
{
    return $this->hasMany(OrderItem::class);
}

public function confirmOrder()
{
    $this->status = 'confirmed';
    $this->confirmed_at = now();
    $this->save();
    
    // Send notification to the user
    $this->user->notify(new OrderConfirmed($this));
    
    return $this;
}

public function cancelOrder()
{
    if ($this->status === 'cancelled') {
        return $this;
    }
    
    // Restore stock for all items
    foreach ($this->orderItems as $item) {
        $product = \App\Models\Product::find($item->product_id);
        
        if (!$product) {
            continue;
        }
        
        if ($item->size && $product->has_size) {
            // Restore stock for size-specific products
            $productSize = \App\Models\ProductSize::where('product_id', $item->product_id)
                ->where('size_name', $item->size)
                ->first();
            
            if ($productSize) {
                $productSize->stock += $item->quantity;
                $productSize->save();
            }
        } else if (!$product->has_size) {
            // Restore stock for products without size
            $product->stock += $item->quantity;
            $product->save();
        }
    }
    
    $this->status = 'cancelled';
    $this->save();
    
    return $this;
}

public function isPaymentOverdue()
{
    if ($this->status !== 'confirmed' || !$this->confirmed_at) {
        return false;
    }
    
    // Check if 24 hours have passed since confirmation
    return $this->confirmed_at->addHours(24)->isPast() && !$this->paid_at;
}

public function markAsPaid()
{
    $this->paid_at = now();
    $this->save();
    
    return $this;
}

public function getTimeRemainingToPay()
{
    if (!$this->confirmed_at || $this->paid_at) {
        return null;
    }
    
    $deadline = $this->confirmed_at->copy()->addHours(24);
    
    if ($deadline->isPast()) {
        return 'OVERDUE';
    }
    
    $remaining = now()->diffForHumans($deadline, true);
    return $remaining;
}
}