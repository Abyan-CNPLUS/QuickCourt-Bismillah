<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueHoliday extends Model
{
    use HasFactory;
    protected $table = 'venue_holidays';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
