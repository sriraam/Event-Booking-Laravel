<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;

class EventController extends Controller
{
public function publicEvents(Request $request){
    $evts = Event::with('categories','creator')
    ->UpcomingEvents()
    ->orderBy('starts_at');
    
    //Filter based on selected category
    if($request->filled('category_id')){
        $evts->whereHas('categories',function($evt)
        {return $$evt->where('cateegories.id',$request->category_id);
        });
    }
    
    $events=$evts->paginate(8)->withQueryString();

    $categories = Category::orderBy('name')->get();

    return view('events.id',compact('events','categories'));
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
        $categories = Category::orderby('name')->get();
        return view('events.createEvent',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_unless(auth()->user()->role === 'organiser',403);
        $data = $request->validate([
            'title' => 'required|max:100',
            'description' => 'nullable|string|max:1000',
            'starts_at'=>'required|date|after:now',
            'location'=>'required|max:255',
            'capacity'=>'required|integer|min:1|max:1000',
            'category_ids'=>'required|array|min:1',
            'category_ids.*' => 'integer|exists:categories,id',
        ]);

        $data['creator_id']=auth()->id();
        
        $event = Event::create(collect($data)->except('category_ids')->all());
        //Add categories
        $event->categories()->sync($data['category_ids']);

        return redirect()->route('events.show',$event)->with('ok','Event created');
    
    }

    /**
     * Display the  event.
     */
    public function show(Event $event)
    {
        $event->load('categories');
        return view('events.showEvent',compact('event'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        abort_unless(auth()->id() === $event->creator_id, 403);
        
        $categories = Category::orderBy('name')->get();
        $event->load('categories');
        return view('events.editEvent', compact('event','categories'));
    }

    /**
     * Update the Event.
     */
    public function update(Request $request, Event $event)
    {
        abort_unless(auth()->id() === $event->creator_id, 403);
        //return view('events.showEvent',compact('event'));
        $data = $request->validate([
            'title'=>'required|max:100',
            'description'=>'nullable',
            'starts_at'=>'required|date|after:now',
            'location'=>'required|max:255',
            'capacity'=>'required|integer|min:1|max:1000',
            'category_ids'=> 'required|array|min:1',
            'category_ids.*' => 'integer|exists:categories,id',
        ]);
        $event->update(collect($data)->except('category_ids')->all());
        $event->categories()->sync($data['category_ids']);

        return redirect()->route('events.show', $event)->with('ok','Event updated');
    }

    /**
     * Remove the Event.
     */
    public function destroy(Event $event)
    {
        abort_unless(auth()->id() === $event->creator_id,403);
        if($event->bookings()->count() > 0)
        {
            return redirect()->route('events.show',$event)->with('error','This Event cannot be deleted because it is already booked');
        }
        $event->delete();
        return redirect()->route('events.public')->with('ok','Event deleted Successfully');
    }

    public function publicIndex(Request $req){
        $q = Event::with('categories','creator','bookings')->upcoming()->orderBy('starts_at');
        if($req->filled('category')){
            $q->where('category_id',$req->category);
        }
        $events = $q->pagination(5)->withQueryString();
        
        $categories = Category::orderBy('name')->get();
        
        return view('events.index',compact('events','categories'));
    }

    public function calendar(Request $req){
        $year = (int)$req->input('year',now()->year);
        $month = (int)$req -> input('month',now()->month);
        $start = \Carbon\Carbon::create($year,$month,1)->startOfMonth();
        $end = (clone $start)->endOfMonth();

        $events = Event::with('category','bookings')
      ->whereBetween('starts_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
      ->orderBy('starts_at')->get();
    $eventsByDay = $events->groupBy(function($evt){return $evt->starts_at->format('Y-m-d');});

    $prev = (clone $start)->subMonth();
    $next = (clone $start)->addMonth();
    $categories = Category::orderBy('name')->get();
    
    return view('events.calendar',compact('year','month','start','prev','next','eventsByDay','categories'));
}

//Filter function for AJAX 
public function filter(Request $req){
    $evts = Event::with('categories','creator','bookings')->upcomingEvents()
    ->orderBy('starts_at');
    
    if($req->filled('category_id')){
        $evts->whereHas('categories', function($evt)use ($req){return $evt->where('categories.id',$req->category_id);});   
    }        
        $events = $evts->paginate(8)->withQueryString();

        //returning the list for AJAX
        return view('events.list',compact('events'));
    }
}
