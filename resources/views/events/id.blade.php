<x-app-layout>
<h1 class"text-2x1 mb-4">Upcoming Events</h1>
    <div class="mb-3">
        @auth
            @if(auth()->user()->role ==='organiser')
                <a href="{{route('events.create')}}">"Add new Event"</a>
            @endif
        @endauth
</div>
<ul>
    @foreach($events as $et)
    <li>
        <a href="{{route('events.show',$et)}}">
        {{$et->title}}</a> : {{$et->starts_at->format('d M Y H:i')}} at {{$et->location}}
    </li>
    @endforeach
</ul>
{{--{{$events->links()}}--}}
</x-app-layout>

