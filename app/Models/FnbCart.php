<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FnbCart extends Model
{
    use HasFactory;
    protected $table = 'fnb_cart';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'fnb_menu_id',
        'quantity',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menu()
    {
        return $this->belongsTo(Fnb_menu::class, 'fnb_menu_id');
    }
}
