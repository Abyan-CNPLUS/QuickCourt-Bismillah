<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venues extends Model
{
    use HasFactory;
    protected $table = 'venues';
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function images()
    {
        return $this->hasMany(VenueImage::class, 'venue_id');
    }

    public function primaryImage()
    {
        return $this->hasOne(VenueImage::class, 'venue_id')->where('is_primary', true);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'facility_venue', 'venue_id', 'facility_id');
    }

    public function fnbMenus()
    {
        return $this->hasMany(Fnb_menu::class);
    }


}
