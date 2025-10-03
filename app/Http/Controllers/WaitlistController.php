<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WaitlistController extends Controller
{
/**
 * Add the authenticated attendee to the waitlist for a full event.
 *
 * @param \App\Models\Event $event The event instance resolved via route model binding.
 * @return \Illuminate\Http\RedirectResponse Redirects back with status or error message.
 *
 */
    public function store(Event $event)
    {
        abort_unless(auth()->user->role === 'attendee',403);

        if(!$event->isfull){
            return back()->withError(['waitlist'=>'Please Book!']);
        }

        $exists = Waitlist::where('user_id',auth()->id())->where('event_id',$event->id)->exists();

        if($exists) return back()->withErrors(['waitlist'=>'Already on waiting list']);

        Waitlist::create(['user_id'=>auth()->id(),'event_id'=>$event->id]);
        return back()->with('ok','Added to Waitlist... :)');
    }
}
