<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Owner extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    // Relasi ke venues
    public function venues()
    {
        return $this->hasMany(Venues::class, 'owner_id');
    }

    // Relasi ke bank accounts
    public function bankAccounts()
    {
        return $this->hasMany(OwnerBankAccount::class, 'owner_id');
    }
}
