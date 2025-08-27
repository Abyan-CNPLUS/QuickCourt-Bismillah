<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FoodMenuController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MemesanController;
use App\Http\Controllers\RegisController;
use App\Http\Controllers\LapController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\VenueController as AdminVenueController;
use App\Http\Controllers\Admin\FNBController as AdminMenusController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/menu', [FoodMenuController::class, 'index']);
Route::get('/menu/category/{id}', [FoodMenuController::class, 'getMenusByCategory']);
Route::get('/venues/filter', [\App\Http\Controllers\LapController::class, 'filter'])->name('venues.filter');
Route::get('/menu/all', [FoodMenuController::class, 'all']);
Route::get('/venue', [LapController::class, 'index']);
Route::get('/wow/{venue}', [LapController::class, 'show'])->name('lapangan.show');


Route::get('/register', [RegisController::class, 'create'])->name('register');
Route::post('/register', [RegisController::class, 'adduser'])->name('regis.store');

Route::get('/bookings/create/{id}', [MemesanController::class, 'create'])->name('bookings.create.withVenue');


    route::get('/struck',[ AdminVenueController::class,"struck"]);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/profile', fn() => view('admin.profile'))->name('profile');
    Route::prefix('admin')->group(function () {
    Route::resource('/venues', AdminVenueController::class)->names('admin.venues');
});
    Route::resource('menus',AdminMenusController::class);
    Route::get('/admin/profile', fn() => view('admin.profile'))->name('profile');

    Route::get('/test-form', function () {
    return view('tesform');
})->name('test.form');

Route::post('/test-form-store', function (\Illuminate\Http\Request $request) {
    dd('DATA MASUK', $request->all());
})->name('test.form.store');

// Simpan booking
Route::post('/bookings/store', [MemesanController::class, 'store'])
    ->name('bookings.store');

// Halaman pembayaran
Route::get('/payment/{bookingId}', [PaymentController::class,'show'])->name('bookings.payment');
Route::post('/payment/callback', [PaymentController::class,'callback']);
