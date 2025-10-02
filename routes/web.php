<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BookingController;

Route::get('/', [EventController::class,'publicEvents'])->name('home');

Route::get('/dashboard',[DashboardController::class,'show'] )->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::redirect('*/', '/login')->name('home');


Route::get('/events-view',[EventController::class,'publicEvents'])->name('events.public');
Route::get('/events/filter',[EventController::class,'filter'])->name('events.filter');

Route::resource('events',EventController::class)->except(['index','show'])->middleware('auth');
//Route::get('/events-view',[EventController::class,'publicEvents'])->name('events.id');

Route::get('/events/{event}',[EventController::class,'show'])->whereNumber('event')->name('events.show');

Route::get('/calendar',[EventController::class,'calendar'])->name('calendar');


Route::middleware('auth')->group(function(){
    Route::resource('events',EventController::class)->except(['index','show']);

    Route::post('/events/{event}/book',[BookingController::class,'store'])->name('bookings.store');
    Route::get('/my-bookings',[BookingController::class,'index'])->name('bookings.index');
    Route::post('/events/{event}/waitlist',[WaitlistController::class,'store'])->name('waitlist.store');
});
//Routes for policy pages
Route::view('/privacy-policy', 'legal.privacy')->name('policy.privacy');
Route::view('/terms-of-use', 'legal.terms')->name('policy.terms');
require __DIR__.'/auth.php';