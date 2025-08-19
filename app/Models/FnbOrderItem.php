<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FnbOrderItem extends Model
{
    use HasFactory;
    protected $table = 'fnb_order_items';
    protected $primaryKey = 'id';
    protected $fillable = [
        'fnb_order_id',
        'fnb_menu_id',
        'quantity',
        'price',
    ];

    public function order() {
        return $this->belongsTo(Fnb_order::class);
    }

    public function menu() {
        return $this->belongsTo(Fnb_menu::class);
    }

}
