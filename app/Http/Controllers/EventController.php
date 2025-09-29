<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;

class EventController extends Controller
{
public function publicEvents(Request $request){
    $events = Event::with('category')
    ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->category_id))
    ->UpcomingEvents()
    ->orderBy('starts_at')
    ->paginate(5)
    ->withQueryString();
    
   // if ($request->filled('category')) {
  //      $evt_cat->where('category_id', $request->category);
   // }

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
            'title' => 'required|max:50',
            'starts_at'=>'required|date|after:now',
            'location'=>'required|max:200',
            'capacity'=>'required|integer|min:1',
            'category_id'=>'required|exists:categories,id',
        ]);

        $data['creator_id']=auth()->id();
        
        $event = Event::create($data);
        
        $event->save();
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
        
        $categories = Category::orderBy('name')->get();

        return view('events.editEvent', compact('event','categories'));
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
            'capacity'=>'required|integer|min:1',
            'category_id'=>'required|exists:categories,id'
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

    public function publicIndex(Request $req){
        $q = Event::with('category')->upcoming()->orderBy('starts_at');
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

public function filter(\Illuminate\Http\Request $req){
    $events = Event::with('category')
        ->when($req->filled('category_id'),function($q) use ($req) {return $q->where('category_id', $req->category_id); })
        ->upcomingEvents()->orderBy('starts_at')->paginate(5)
        ->withQueryString();

        return view('events.list',compact('events'));
    }


}
