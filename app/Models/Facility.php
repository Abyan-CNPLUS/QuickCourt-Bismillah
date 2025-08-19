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
        return $this->belongsToMany(Venues::class, 'facility_venue', 'facility_id', 'venue_id');
    }

}
