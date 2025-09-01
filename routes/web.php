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
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Owner\PemilikVenueController;
use App\Http\Controllers\Owner\FoodiesPemilikController;
use App\Http\Controllers\Owner\DashboardPemilikController;
use App\Http\Controllers\Owner\DashbordPemilikController;
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

    // Route khusus approval dulu
    Route::get('venues/approval', [AdminVenueController::class, 'approvalList'])->name('admin.venues.approval');
    Route::post('venues/{venue}/approve', [AdminVenueController::class, 'approve'])->name('admin.venues.approve');
    Route::post('venues/{venue}/reject', [AdminVenueController::class, 'reject'])->name('admin.venues.reject');

    // Baru resource route
    Route::resource('venues', AdminVenueController::class)->names('admin.venues');
});

    Route::resource('menus',AdminMenusController::class);
    Route::get('/admin/profile', fn() => view('admin.profile'))->name('profile');

Route::post('/test-form-store', function (\Illuminate\Http\Request $request) {
    dd('DATA MASUK', $request->all());
        })->name('test.form.store');

// Simpan booking
Route::post('/bookings/store', [MemesanController::class, 'store'])
    ->name('bookings.store');

// PILIH PAYMENT OPTION
Route::get('/bookings/{booking}/payment-option', [PaymentController::class, 'option'])->name('payments.option');
Route::post('/bookings/{booking}/payment-option', [PaymentController::class, 'optionProcess'])->name('payments.option.process');

Route::get('/payments/{payment}/gateway', [PaymentController::class, 'gateway'])
    ->name('payments.gateway');

// Halaman manual/hybrid
Route::get('/payments/{booking}/manual', [PaymentController::class, 'manual'])->name('payments.manual');
// Upload bukti transfer
Route::post('/payments/{booking}/manual', [PaymentController::class, 'manualUpload'])->name('payments.manual.upload');


Route::get('/admin/venue-approval', [App\Http\Controllers\Admin\AdminController::class, 'venueApproval'])->name('admin.venue-approval');
Route::post('/admin/venue-approval/{venue}', [App\Http\Controllers\Admin\AdminController::class, 'updateVenueStatus'])->name('admin.update-venue-status');

Route::prefix('owner')->middleware(['auth'])->group(function () {
    Route::get('/venues', [PemilikVenueController::class, 'index'])->name('owner.venues.index');
    Route::get('/venues/create', [PemilikVenueController::class, 'create'])->name('owner.venues.create');
    Route::post('/venues', [PemilikVenueController::class, 'store'])->name('owner.venues.store');
    Route::get('/venues/{venue}/edit', [PemilikVenueController::class, 'edit'])->name('owner.venues.edit');
    Route::put('/venues/{venue}', [PemilikVenueController::class, 'update'])->name('owner.venues.update');
    Route::delete('/venues/{venue}', [PemilikVenueController::class, 'destroy'])->name('owner.venues.destroy');
});

Route::resource('Foodies',FoodiesPemilikController::class);

Route::middleware(['auth'])->prefix('owner')->group(function () {
   Route::get('dashboard', [DashbordPemilikController::class, 'index'])->name('owner.dashboard');
});
