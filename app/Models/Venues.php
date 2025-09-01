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

    // Scope untuk venue yang sudah di-approve (publik)
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    // Scope untuk venue milik user tertentu (owner dashboard)
    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope untuk publik: hanya yang approved
    public function scopePublic($query)
    {
        return $query->where('approval_status', 'approved');
    }

    // Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // City
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // Owner
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Images
    public function images()
    {
        return $this->hasMany(VenueImage::class, 'venue_id');
    }

    // Primary image
    public function primaryImage()
    {
        return $this->hasOne(VenueImage::class, 'venue_id')->where('is_primary', 1);
    }

    // Facilities (many-to-many)
    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'facility_venue', 'venue_id', 'facility_id');
    }

    // FNB menus
    public function fnbMenus()
    {
        return $this->hasMany(Fnb_menu::class, 'venue_id');
    }

    // Mendapatkan gambar yang ditampilkan di front-end
    public function getDisplayImageAttribute()
    {
        if ($this->primaryImage) {
            return $this->primaryImage->image_url;
        }

        return $this->images->first()->image_url ?? null;
    }

    public function venuePromos()
    {
        return $this->hasMany(VenuePromo::class, 'venue_id');
    }

}
