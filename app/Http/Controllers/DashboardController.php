<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
/**
 * Display a listing of the authenticated user's bookings.
 *
 * This method retrieves all bookings made by the currently user.
 * @return \Illuminate\View\View The view displaying the user's bookings.
 */
    public function show(){
        $role = auth()->user()->role;
        $user = auth()->user();

        //Dashboard for Attendee
        if($role === 'attendee')
            return redirect()->route('events.public');


        //Dashboard for Organiser
        if($role === 'organiser'){
            $events = DB::select("SELECT events.id, events.title,events.starts_at,events.location,events.capacity, 
            COUNT(bookings.id) AS total_bookings, (events.capacity - COUNT(bookings.id)) AS available_seats
             FROM events LEFT JOIN bookings ON bookings.event_id = events.id
             WHERE events.creator_id = ?
             GROUP BY events.id,events.title,events.starts_at,events.location,events.capacity
             ORDER BY events.starts_at ASC",[$user->id]);

            return view('dashboard.organiser',compact('events'));
        abort(403); // Show 403 if role is not Attendee or Organiser
    }

    return view('dashboard.default');
    }
}
