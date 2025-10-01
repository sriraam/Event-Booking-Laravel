<x-app-layout>

 <h1 class="font-semibold text-xl text-gray-800 leading-tight">Atendee Dashboard</h1>

 @if($events->isEmpty())
    <p>No Upcoming Events, right now</p>
@else
    <ul class="event-list">
        @foreach($events as $event)
            @php $color = $event->categoty>->color ?? '#3b2525'; @endphp
            <li class="event-card">
                <a href="{{ route('events.show',$event)}}" class="event-title"> {{$event->title}}</a>
                <div class="event-meta">
                    {{ $event->starts_at->format('d M Y H:i')}} | {{$event->location}}
                </div>
                @if($event->category)
                    <span class="badge" style="--badge: {{$coloe }}">{{$event->category->name}}</span>
                @endif
                <form method="POST" action="{{route('bookings.store',$event)}}"> class="mt-2"
                    @csrf
                    <button class="btn btn-primary">Book Now</button>
                </form>
            </li>
        @endforeach
    </ul>
    <div class="mt-4">{{$events->links()}}</div>
@endif
</x-app-layout>