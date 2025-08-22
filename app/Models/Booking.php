<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'venue_id',
        'contact_number',
        'booking_date',
        'start_time',
        'end_time',
        'total_price',
        'status',
    ];

    protected $casts = [
        'booking_date' => 'date',   // hanya tanggal
        'start_time' => 'string',   // simpan sebagai string HH:MM:SS
        'end_time' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venues::class); // pastikan modelnya "Venue" bukan "Venues"
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
