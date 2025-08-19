<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\time_avalaible;
use App\Models\Dateplay;
use App\Models\Venue;
use Carbon\Carbon;

class TimeController extends Controller
{
    // FORM INPUT
    public function indexxx()
{
   $times = \App\Models\time_avalaible::orderBy('date', 'asc')->get();
    return view('admin.Time_avalaible.index', compact('times'));
}

    public function adData()
{
    $venue = Venues::all(); // Ambil semua data venue
    $dateplays = Dateplay::all();
    return view('admin.Time_avalaible.create', compact('venue','dateplays'));
}


    // SIMPAN JAM UNTUK 7 HARI KE DEPAN
public function cbdata(Request $request)
{
    // Simpan jam ke tabel time_avalaibles
    $time = new Time_avalaible();
$time->venue_id = $request->venue_id;
$time->start_time = $request->start_time;
$time->end_time = $request->end_time;
$time->date = now()->toDateString(); // kasih tanggal hari ini
$time->save();

    // Buat tanggal otomatis 7 hari ke depan
    for ($i = 0; $i < 7; $i++) {
        Dateplay::create([
            'Tanggal' => Carbon::now()->addDays($i)->toDateString(), // YYYY-MM-DD
            'time_avalaible_id' => $time->id, // relasi ke jam yang baru dibuat
        ]);
    }

    return redirect()->back()->with('success', 'Jam & tanggal berhasil dibuat untuk 7 hari ke depan');
}
    // EDIT JAM
    public function edit($id)
    {
        $times = Time_avalaible::findOrFail($id);
        return view('Time_avalaible.edit', compact('times'));
    }

    // UPDATE JAM
    public function update(Request $request, $id)
    {
        $request->validate([
            'start_time' => 'required',
            'end_time'   => 'required',
        ]);

        $times = Time_avalaible::findOrFail($id);
        $times->update([
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
        ]);

        return redirect()->route('time.index')->with('success', 'Jam berhasil diperbarui.');
    }

    // HAPUS JAM
    public function destroy($id)
    {
        $times = Time_avalaible::findOrFail($id);
        $times->delete();

        return redirect()->back()->with('success', 'Jam berhasil dihapus.');
    }
}
