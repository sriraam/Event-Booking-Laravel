<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
public function publicEvents(){
    $events = Event::UpcomingEvents()->orderBy('starts_at')->paginate(5);
    return view('events.id',compact('events'));
}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(auth()->user()->role === 'organiser', 403);
        return view('events.createEvent');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_unless(auth()->user()->role === 'organiser',403);
        $data = $request->validate([
            'title' => 'required|max:50',
            'starts_at'=>'required|date|after:now',
            'location'=>'required|max:200',
            'capacity'=>'required|integer|min:1'
        ]);
        $data['creator_id']=auth()->id();
        $event = Event::create($data);
        return redirect()->route('events.show',$event)->with('ok','Event created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::findOrFail($id);
        return view('events.showEvent',compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        abort_unless(auth()->id() === $event->creator_id, 403);
        return view('events.editEvent', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        abort_unless(auth()->id() === $event->creator_id, 403);
        //return view('events.showEvent',compact('event'));
        $data = $request->validate([
            'title'=>'required|max:50',
            'starts_at'=>'required|date|after:now',
            'location'=>'required|max:200',
            'capacity'=>'required|integer|min:1'
        ]);
        $event->update($data);
            
        return redirect()->route('events.show',$event)->with('ok','Event updated');
       // return view('events.showEvent',compact('event'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        abort_unless(auth()->id() === $event->creator_id,403);
        $event->delete();
        return redirect()->route('events.id')->with('ok','Event deleted');
    }
}
