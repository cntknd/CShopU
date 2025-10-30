<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'caption', 'image', 'price', 'supplier_price', 'stock', 'department_id', 'category_id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

     public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    public function getStockForSize($sizeName)
    {
        return $this->sizes()->where('size_name', $sizeName)->value('stock') ?? 0;
    }

    public function category()
{
    return $this->belongsTo(Category::class);
}

}
