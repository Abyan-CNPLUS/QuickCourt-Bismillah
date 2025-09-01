<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerBankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'bank_name',
        'account_number',
        'account_name',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
