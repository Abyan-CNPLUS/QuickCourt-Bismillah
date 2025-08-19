<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fnb_order extends Model
{
    use HasFactory;
    protected $table = 'fnb_orders';
    protected $primaryKey= 'id';
    protected $fillable = [
        'booking_id',
        'user_id',
        'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function booking() {
        return $this->belongsTo(Booking::class);
    }

    public function items()
    {
        return $this->hasMany(FnbOrderItem::class, 'fnb_order_id');
    }

}
