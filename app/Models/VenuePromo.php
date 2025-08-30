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


}
