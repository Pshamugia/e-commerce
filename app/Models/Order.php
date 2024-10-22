<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    // Define the fillable properties
    protected $fillable = ['user_id', 'subtotal', 'shipping', 'total', 'status', 'name', 'phone', 'payment_method'];

    // Relationship with OrderItem model
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
