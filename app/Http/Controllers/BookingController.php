<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Booking;

class BookingController extends Controller
{
    public function store(Event $event){
        abort_unless(auth()->user()->role === 'attendee', 403);
        $isBooked = Booking::where('user_id',auth()->id())->where('event_id',$event->id)->exists();
            if ($isBooked) return back()->withErrors(['booking'=>'Already booked']);
    
        $curBooking = Booking::where('event_id',$event->id)->count();
        if($curBooking >= $event->capacity){
             return back()->withErrors(['booking'=>'Event is full']);
            }
        
        Booking::create(['user_id'=>auth()->id(),'event_id'=>$event->id]);
        return redirect()->route('bookings.index')->with('ok','Booked!');

    }

    public function index(){
        $bookings = Booking::with('event')->where('user_id',auth()->id())->latest()->get();
        return view('bookings.index',compact('bookings'));
    }
}
