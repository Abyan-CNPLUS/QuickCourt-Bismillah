<?php

namespace App\Http\Controllers\API\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OwnerDashboardController extends Controller
{
    /**
     * Dashboard gabungan untuk semua venue milik owner yang login.
     * GET /api/owner-dashboard
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $year   = (int)($request->query('year', now()->year));


        $perluDiproses = DB::table('bookings')
            ->join('venues', 'bookings.venue_id', '=', 'venues.id')
            ->where('venues.user_id', $userId)
            ->where('bookings.status', 'pending')
            ->count();

        $kendala = DB::table('bookings')
            ->join('venues', 'bookings.venue_id', '=', 'venues.id')
            ->where('venues.user_id', $userId)
            ->whereIn('bookings.status', ['rejected'])
            ->count();

        $dibatalkan = DB::table('bookings')
            ->join('venues', 'bookings.venue_id', '=', 'venues.id')
            ->where('venues.user_id', $userId)
            ->where('bookings.status', 'cancelled')
            ->count();

        $totalBookings = DB::table('bookings')
            ->join('venues', 'bookings.venue_id', '=', 'venues.id')
            ->where('venues.user_id', $userId)
            ->count();

        $totalTransactions = DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('venues', 'bookings.venue_id', '=', 'venues.id')
            ->where('venues.user_id', $userId)
            ->where('payments.status', 'confirmed')
            ->count();

        $totalBalance = (float) DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('venues', 'bookings.venue_id', '=', 'venues.id')
            ->where('venues.user_id', $userId)
            ->where('payments.status', 'confirmed')
            ->sum('payments.amount');


        $bookingsRaw = DB::table('bookings')
            ->join('venues', 'bookings.venue_id', '=', 'venues.id')
            ->where('venues.user_id', $userId)
            ->whereYear('bookings.created_at', $year)
            ->select(
                DB::raw('MONTH(bookings.created_at) as month'),
                DB::raw('COUNT(bookings.id) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $bookingsPerMonth = $this->fillMonths($bookingsRaw);

        // --- CHART: INCOME PER MONTH (confirmed only) ---
        $incomeRaw = DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('venues', 'bookings.venue_id', '=', 'venues.id')
            ->where('venues.user_id', $userId)
            ->where('payments.status', 'confirmed')
            ->whereYear('payments.created_at', $year)
            ->select(
                DB::raw('MONTH(payments.created_at) as month'),
                DB::raw('SUM(payments.amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $incomePerMonth = $this->fillMonths($incomeRaw, asFloat: true);

        // --- TOP 5 F&B (opsional, jika ada tabelnya) ---
        // fnb_orders (venue_id) -> fnb_order_items (menu_id, quantity) -> fnb_menu (name)
        $topFnb = DB::table('fnb_order_items')
            ->join('fnb_orders', 'fnb_order_items.fnb_order_id', '=', 'fnb_orders.id')
            ->join('venues', 'fnb_orders.venue_id', '=', 'venues.id')
            ->join('fnb_menu', 'fnb_order_items.fnb_menu_id', '=', 'fnb_menu.id')
            ->where('venues.user_id', $userId)
            ->select('fnb_menu.name', DB::raw('SUM(fnb_order_items.quantity) as total'))
            ->groupBy('fnb_menu.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // --- RECENT BOOKINGS (10 terakhir) ---
        $recentBookings = DB::table('bookings')
            ->join('venues', 'bookings.venue_id', '=', 'venues.id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->where('venues.user_id', $userId)
            ->select(
                'bookings.id',
                'bookings.status',
                'bookings.booking_date',
                'bookings.start_time',
                'bookings.end_time',
                'venues.name as venue_name',
                'users.name as customer_name'
            )
            ->orderByDesc('bookings.created_at')
            ->limit(10)
            ->get();

        $venues = DB::table('venues')
        ->leftJoin('venue_images', function ($join) {
            $join->on('venues.id', '=', 'venue_images.venue_id')
                ->where('venue_images.is_primary', 1);
        })
        ->where('venues.user_id', $userId)
        ->select('venues.id', 'venues.name', 'venue_images.image_url')
        ->get();

        // --- BALIKAN JSON ---
        return response()->json([
            'summary' => [
                'perluDiproses'      => $perluDiproses,
                'kendala'            => $kendala,
                'dibatalkan'         => $dibatalkan,
                'totalBookings'      => $totalBookings,
                'totalTransactions'  => $totalTransactions,
                'totalBalance'       => $totalBalance,
                'year'               => $year,
            ],
            'charts' => [
                // Format: array 12 elemen (index 1..12): { "month": 1..12, "total": int/float }
                'bookingsPerMonth' => $this->chartArray($bookingsPerMonth),
                'incomePerMonth'   => $this->chartArray($incomePerMonth, asFloat: true),
            ],
            'topFnb' => $topFnb,          // [{name, total}]
            'recentBookings' => $recentBookings, // list 10 terakhir
            'venues' => $venues,
        ], 200);
    }

    
    public function show(Request $request, $venueId)
    {
        $userId = Auth::id();
        $year   = (int)($request->query('year', now()->year));

        // pastikan venue milik owner yg login
        $owned = DB::table('venues')
            ->where('id', $venueId)
            ->where('user_id', $userId)
            ->exists();

        if (!$owned) {
            return response()->json([
                'message' => 'Unauthorized: venue not owned by current user.'
            ], 403);
        }

        // --- SUMMARY (untuk venue tertentu) ---
        $perluDiproses = DB::table('bookings')
            ->where('venue_id', $venueId)
            ->where('status', 'pending')
            ->count();

        $kendala = DB::table('bookings')
            ->where('venue_id', $venueId)
            ->whereIn('status', ['rejected'])
            ->count();

        $dibatalkan = DB::table('bookings')
            ->where('venue_id', $venueId)
            ->where('status', 'cancelled')
            ->count();

        $totalBookings = DB::table('bookings')
            ->where('venue_id', $venueId)
            ->count();

        $totalTransactions = DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->where('bookings.venue_id', $venueId)
            ->where('payments.status', 'confirmed')
            ->count();

        $totalBalance = (float) DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->where('bookings.venue_id', $venueId)
            ->where('payments.status', 'confirmed')
            ->sum('payments.amount');


        $bookingsRaw = DB::table('bookings')
            ->where('venue_id', $venueId)
            ->whereYear('created_at', $year)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(id) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $bookingsPerMonth = $this->fillMonths($bookingsRaw);


        $incomeRaw = DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->where('bookings.venue_id', $venueId)
            ->where('payments.status', 'confirmed')
            ->whereYear('payments.created_at', $year)
            ->select(
                DB::raw('MONTH(payments.created_at) as month'),
                DB::raw('SUM(payments.amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $incomePerMonth = $this->fillMonths($incomeRaw, asFloat: true);


        $topFnb = DB::table('fnb_order_items')
            ->join('fnb_orders', 'fnb_order_items.fnb_order_id', '=', 'fnb_orders.id')
            ->join('fnb_menu', 'fnb_order_items.fnb_menu_id', '=', 'fnb_menu.id')
            ->where('fnb_orders.venue_id', $venueId)
            ->select('fnb_menu.name', DB::raw('SUM(fnb_order_items.quantity) as total'))
            ->groupBy('fnb_menu.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();


        $recentBookings = DB::table('bookings')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->where('bookings.venue_id', $venueId)
            ->select(
                'bookings.id',
                'bookings.status',
                'bookings.booking_date',
                'bookings.start_time',
                'bookings.end_time',
                'users.name as customer_name'
            )
            ->orderByDesc('bookings.created_at')
            ->limit(10)
            ->get();


        $venue = DB::table('venues')
        ->leftJoin('venue_images', function ($join) {
            $join->on('venues.id', '=', 'venue_images.venue_id')
                ->where('venue_images.is_primary', 1);
        })
        ->where('venues.id', $venueId)
        ->select('venues.*', 'venue_images.image_url')
        ->first();

        return response()->json([
            'venue'   => $venue,
            'summary' => [
                'perluDiproses'      => $perluDiproses,
                'kendala'            => $kendala,
                'dibatalkan'         => $dibatalkan,
                'totalBookings'      => $totalBookings,
                'totalTransactions'  => $totalTransactions,
                'totalBalance'       => $totalBalance,
                'year'               => $year,
            ],
            'charts' => [
                'bookingsPerMonth' => $this->chartArray($bookingsPerMonth),
                'incomePerMonth'   => $this->chartArray($incomePerMonth, asFloat: true),
            ],
            'topFnb'         => $topFnb,
            'recentBookings' => $recentBookings,
        ], 200);
    }


    private function monthSeed(bool $asFloat = false): array
    {
        $seed = [];
        for ($m = 1; $m <= 12; $m++) {
            $seed[$m] = $asFloat ? 0.0 : 0;
        }
        return $seed;
    }


    private function fillMonths(array $raw, bool $asFloat = false): array
    {
        $base = $this->monthSeed($asFloat);
        foreach ($raw as $month => $val) {
            $base[(int)$month] = $asFloat ? (float)$val : (int)$val;
        }
        return $base;
    }

    private function chartArray(array $map, bool $asFloat = false): array
    {
        $arr = [];
        foreach ($map as $month => $val) {
            $arr[] = [
                'month' => (int)$month,
                'total' => $asFloat ? (float)$val : (int)$val,
            ];
        }
        return $arr;
    }
}
