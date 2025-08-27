<?php

namespace Database\Seeders;

use App\Models\Owner;
use App\Models\OwnerBankAccount;
use App\Models\Venues;

$ownersData = [
    [
        'name' => 'Owner 1',
        'email' => 'owner1@example.com',
        'phone' => '081234567890',
        'bank' => [
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'account_name' => 'Owner 1'
        ]
    ],
    [
        'name' => 'Owner 2',
        'email' => 'owner2@example.com',
        'phone' => '081234567891',
        'bank' => [
            'bank_name' => 'Mandiri',
            'account_number' => '0987654321',
            'account_name' => 'Owner 2'
        ]
    ]
];

foreach ($ownersData as $data) {
    $owner = Owner::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => bcrypt('password123'),
        'phone' => $data['phone']
    ]);

    OwnerBankAccount::create(array_merge($data['bank'], ['owner_id' => $owner->id]));

    // Assign ke venue secara random atau sesuai logic
    $venue = Venues::inRandomOrder()->first();
    if ($venue) $venue->update(['owner_id' => $owner->id]);
}

