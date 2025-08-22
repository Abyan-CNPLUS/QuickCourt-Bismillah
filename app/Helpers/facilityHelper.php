<?php

namespace App\Helpers;

class FacilityHelper
{
    public static function icon(string $facilityName): string
    {
        $icons = [
            'Parkir Luas'      => '🚗',
            'Toilet'           => '🚽',
            'Ruang Ganti'      => '🛁',
            'Mushola'          => '🕌',
            'Warung Makan'     => '🍴',
            'WiFi Gratis'      => '📶',
            'Penerangan Malam' => '💡',
            'Tribun Penonton'  => '🎟️',
            'Loker Penyimpanan'=> '🔒',
            'Air Minum Gratis' => '💧',
        ];

        return $icons[$facilityName] ?? '❓';
    }
}
