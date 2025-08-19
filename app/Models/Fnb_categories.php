<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fnb_categories extends Model
{
    use HasFactory;
    protected $table = 'fnb_categories';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
    ];

    public function menus()
    {
        return $this->hasMany(Fnb_menu::class, 'categories_id');
    }
}
