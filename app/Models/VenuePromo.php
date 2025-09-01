<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenuePromo extends Model
{
    use HasFactory;
    protected $table = 'venue_promos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'venue_id',
        'title',
        'description',
        'image_url',
        'start_date',
        'end_date',
    ];

    public function venue()
    {
        return $this->belongsTo(Venues::class);
    }

    public function getImageUrlAttribute($value)
    {
        if (!$value) return null;

        // Jika sudah full URL, return langsung
        if (str_starts_with($value, 'http')) {
            return $value;
        }

        return asset('storage/' . $value); // otomatis convert relative path ke full URL
    }
}
