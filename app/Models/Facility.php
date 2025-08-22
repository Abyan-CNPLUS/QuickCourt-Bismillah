<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $table = 'facilities';
    protected $primaryKey = 'id';
    protected $fillable = ['name'];

    public function venues()
    {
        // facility_id -> venue_id
        return $this->belongsToMany(Venue::class, 'facility_venue', 'facility_id', 'venue_id');
    }
}
