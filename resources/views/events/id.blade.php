<x-app-layout>
<h1 class"text-2xl mb-4">Upcoming Events</h1>
    <div class="mb-3">
        @auth
            @if(auth()->user()->role ==='organiser')
                <a href="{{route('events.create')}}">Add new Event</a>
            @endif
        @endauth

    <form method="GET">
        <label class="mr-2">Category:</label>
        <select name="category_id" onchange="this.form.submit()">
            <option value="">All</option>
            @foreach($categories as $cat)
            <option value="{{$cat->id}}" @selected(request('category') == $cat->id)>
                    {{$cat->name}}
                </option>
            @endforeach
        </select>
        </form>    
    </div>

<ul>
    @foreach($events as $et)
    <li class="mb-1">
        <a href="{{route('events.show',$et)}}">
            {{$et->title}}
        </a> 
        : {{$et->starts_at->format('d M Y H:i')}} at {{$et->location}}
        
        @if($et->category_id)
        <p>CHECK</p>
        <span class="px-2 py-0.5 text-xs rounded" 
            style="background : {{$et->category->color}}20; border:1px solid {{$et->category->color}}">
            {{$et->category->name}}
        </span>
        @endif
    </li>
@endforeach
</ul>

<div class="mt-4">
    {{--{{$events->links()}}--}}
</div>
</x-app-layout>

