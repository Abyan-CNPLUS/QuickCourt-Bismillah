<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fnb_menu extends Model
{
    use HasFactory;
    protected $table = 'fnb_menu';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'categories_id',
        'venue_id',
        'price',
        'description',
        'image',
    ];

    protected $appends = ['image_url'];

    public function venue()
    {
        return $this->belongsTo(Venues::class);
    }

    public function category()
    {
        return $this->belongsTo(Fnb_categories::class, 'categories_id');
    }


    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        if (preg_match('/^http/i', $this->image)) {
            return $this->image;
        }
        
        return asset('storage/' . ltrim($this->image, '/'));
    }
}
