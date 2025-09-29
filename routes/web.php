<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BookingController;

/*Route::get('/', function () {
    return view('welcome');
});
*/
Route::get('/dashboard',[DashboardController::class,'show'] )->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/',function(){ 
    return view('auth.login'); 
});

Route::redirect('*/', '/login')->name('home');
require __DIR__.'/auth.php';

Route::resource('events',EventController::class)->middleware('auth');
Route::get('/events-view',[EventController::class,'publicEvents'])->name('events.id');

Route::post('/events/{event}/book',[BookingController::class,'store'])->middleware('auth')->name('bookings.store');
Route::get('/my-bookings',[BookingController::class,'index'])->middleware('auth')->name('bookings.index');

Route::get('/events/{event}',[EventController::class,'show'])->name('events.show');

Route::get('/calender',[EventController::class,'calendar'])->name('calendar');
Route::post('/events/{event}/waitlist',[WaitlistController::class,'store'])->middleware('auth')->name('waitlist.store');
