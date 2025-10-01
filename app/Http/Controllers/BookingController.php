<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Booking;

class BookingController extends Controller
{
    public function store(Request $request, Event $event){
        abort_unless(auth()->user()->role === 'attendee', 403);
        
        $userId= auth()->id();

        //Validating unique bookings to avoid duplication
        $request->validate([
            'event_id' => [ 'required',
            Rule::unique('bookings')->where(function($q) use ($userId,$event) {
                return $q->where('user_id',$userId)->where('event_id',$event->id);}),],],
                ['event_id.unique' => 'You have already booked this event.',
            ]);

            $current = $event->bookings()->count();
            if($current >= $event->capacity){
                return back()->withErrors(['bookings' => 'This event is full. You cannot book it.']);                
            }
        
        Booking::create(['user_id'=>auth()->id(),'event_id'=>$event->id]);
        return redirect()->route('bookings.index')->with('ok','Booking has been confirmed!');

    }

    public function index(){
        $bookings = Booking::with('event')->where('user_id',auth()->id())->latest()->get();
        return view('bookings.index',compact('bookings'));
    }
}
