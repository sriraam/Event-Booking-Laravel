<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;

class EventController extends Controller
{

/**
 * Display a paginated list of upcoming public events, with filtering by category.
 *
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\View\View
 */
public function publicEvents(Request $request){
    $evts = Event::with('categories','creator')
    ->UpcomingEvents()
    ->orderBy('starts_at');
    
    //Filter based on selected category
    if($request->filled('category_id')){
        $evts->whereHas('categories',function($evt)
        {return $evt->where('cateegories.id',$request->category_id);
        });
    }
    
    //Displays 8 events in a page
    $events=$evts->paginate(8)->withQueryString();

    $categories = Category::orderBy('name')->get();

    return view('events.id',compact('events','categories'));
}

/**
 * Shows the form for creating a new event.
 * This method ensures that 'organiser' can create the event.

 * @return \Illuminate\View\View
 */
public function create()
{
    abort_unless(auth()->user()->role === 'organiser', 403);
    $categories = Category::orderby('name')->get();
    return view('events.createEvent',compact('categories'));
}

/**
 * Store a newly created event in the database.
 * creates a new event record, and attaches selected categories via a many-to-many relationship.
 *
 * @param \Illuminate\Http\Request $request containing event data.
 * @return \Illuminate\Http\RedirectResponse Redirects to the event view
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
 * Display the details of a specific event.
 *
 *returns the view responsible for rendering the event details.
 *
 * @param \App\Models\Event $event instance contains event data.
 * @return \Illuminate\View\View The view displaying the event details.
 */
public function show(Event $event)
    {
        $event->load('categories');
        return view('events.showEvent',compact('event'));
    }

/**
 * Show the form to edit the selected event.
 *
 * Restricts access to the edit form to the event’s creator
 * 
 * @param \App\Models\Event $event instance containg event data.
 * @return \Illuminate\View\View The view displays the event edit form.
 *
 */
public function edit(Event $event)
{

    abort_unless(auth()->id() === $event->creator_id, 403);
        
    $categories = Category::orderBy('name')->get();
    $event->load('categories');
    return view('events.editEvent', compact('event','categories'));
}

/**
 * Update the selected event in the database.
 *
 * This method validates the incoming request data, ensures the authenticated user
 * and stores the data mentioned in the form to the requested event
 * 
 * @param \Illuminate\Http\Request $request The HTTP request containing updated event data.
 * @param \App\Models\Event $event The event instance to be updated
 * @return \Illuminate\Http\RedirectResponse Redirects to the event view.
 *
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
 * Remove the specified event from the database.
 *
 * This method ensures that only the event's creator can delete it.
 * If the event has existing bookings, deletion action returns error, Otherwise the event is deleted and user is redirected
 * to all events listing
 *
 * @param \App\Models\Event $event The event instance data to be deleted
 * @return \Illuminate\Http\RedirectResponse Redirects to the events.public if success with a alert message.
 *
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

   /* public function calendar(Request $req){
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
}*/

/**
 * Filters using the selected category and returns the paginated list of upcoming events via AJAX.
 * The filtered list is paginated and returned as a partial view for dynamic rendering via AJAX.
 *
 * @param \Illuminate\Http\Request $req The request containing filter parameters.
 * @return \Illuminate\View\View The partial view containing the filtered event list.
 */
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
