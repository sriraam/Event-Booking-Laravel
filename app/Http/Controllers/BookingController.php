<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Booking;

class BookingController extends Controller
{
    /**
     * Store a new booking for the selcted event.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Event        $event  
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Event $event){
        
        //Check for the attendee
        abort_unless(auth()->user()->role === 'attendee', 403);
        
        $userId= auth()->id();

        $request->merge(['event_id' => $event->id]);

        //Validating unique bookings to avoid duplication 
         $booked = Booking::where('user_id', $userId)
          ->where('event_id', $event->id)->exists();
        
          if ($booked) {
            return back()->withErrors(['booking' => 'You have already booked this event.'])->withInput();
            }

            $current = $event->bookings()->count();
            if($current >= $event->capacity){
                return back()->withErrors(['booking' => 'This event is full. You cannot book it.']);                
            }
        
        Booking::create(['user_id'=>auth()->id(),'event_id'=>$event->id]);
        return redirect()->route('bookings.index')->with('ok','Booking has been confirmed!');

    }

    /**
    * Display a listing of bookings made from the authenticated user
    *
    * @return \Illuminate\View\View  view displaying the user's bookings.
    */
    public function index(){
        $bookings = Booking::with('event')->where('user_id',auth()->id())->latest()->get();
        return view('bookings.index',compact('bookings'));
    }
}
