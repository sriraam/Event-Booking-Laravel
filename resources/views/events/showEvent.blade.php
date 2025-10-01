<x-app-layout>
    <h1 class="text-2x1 mb-2">{{$event->title}}</h1>
    <p>{{ $event->starts_at->toDayDateTimeString() }} | {{$event->location}}</p>
    @if($event->Category)
        <p>
            <span class="px-2 py-1 text-sm rounded"
                  style="background: {{ $event->category->color }}20;
                         border: 1px solid {{ $event->category->color }};
                         color: {{ $event->category->color }}">
                {{ $event->category->name }}
            </span>
        </p>
    @endif

    @if(auth()->id() === $event->creator_id)
        <a class="underline" href="{{ route('events.edit',$event) }}">Edit</a>
        <form action="{{ route('events.destroy',$event) }}" method="POST" 
        onsubmit="return confirm('Are you sure you want to delete this event ?');" >
            @csrf @method('DELETE') <button>Delete</button>
        </form>
    @endif
    @auth
        @if(auth()->user()->role === 'attendee')
            <form method="POST" action="{{ route('bookings.store',$event)}}" class="mt-3">@csrf
                <button>Book Now</button>
            </form>
        @endif
    @endauth
</x-app-layout>