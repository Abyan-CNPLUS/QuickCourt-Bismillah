<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueImage extends Model
{
    use HasFactory;
    protected $table = 'venue_images';
    protected $primaryKey = 'id';
    protected $fillable = ['venue_id', 'image_url', 'is_primary'];

    public function venue()
    {
        return $this->belongsTo(Venues::class, 'venue_id');
    }
}
