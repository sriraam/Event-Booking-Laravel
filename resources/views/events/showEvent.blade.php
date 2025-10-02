<x-app-layout>
    <h1 class="text-2x1 mb-2">{{$event->title}}</h1>
<!-- Displaying Category tags -->
  @if($event->categories && $event->categories->isNotEmpty())
    <div class="mt-1 flex flex-wrap gap-2">
      @foreach($event->categories as $cat)
        <span class="inline-block text-xs px-2 py-0.5 rounded"
              style="background: {{ $cat->color }}20; border: 1px solid {{ $cat->color }}; color: {{ $cat->color }}">
          {{ $cat->name }}
        </span>
      @endforeach
    </div>
  @endif
    <p class="mb-4 text-gray-700">{{ $event->description }}</p>
    <p>{{ $event->starts_at->toDayDateTimeString() }} | {{$event->location}}</p>
    <p class="text-gray-700"> Organised by {{ $event->creator->name ?? 'Unknown' }} </p>
    
    <p class="text-sm text-gray-700">
     Capacity : {{ $event->capacity }}<br>
    </p>
    <p class="text-sm text-gray-600">
     Remaining Seats: {{ $event->capacity - $event->bookings()->count() }}
    </p>
    @if(auth()->id() === $event->creator_id)
        <a class="underline" href="{{ route('events.edit',$event) }}">Edit</a>
        <form action="{{ route('events.destroy',$event) }}" method="POST" 
        onsubmit="return confirm('Are you sure you want to delete this event ?');" >
            @csrf @method('DELETE') <button>Delete</button>
        </form>
    @endif
    @auth
        @if(auth()->user()->role === 'attendee')
           
            @php 
                $alreadyBooked = $event->bookings()->where('user_id', auth()->id())->exists();
                $isFull = $event->bookings()->count() >= $event->capacity;
            
            @endphp
            @if($alreadyBooked)
            <div class="alert-success mb-3">
                You have booked this event.
            </div>
            @else
                 @if($errors->has('booking'))
                     <div class="alert-error">
                       {{ $errors->first('booking') }}
                     </div>
                @endif
            <form method="POST" action="{{ route('bookings.store',$event)}}" class="mt-3">@csrf
            <input type="hidden" name="event_id" value="{{$event->id}}">    
           
            <button class="btn {{$isFull ? 'btn-soldout' : 'btn-primary'}}" 
            {{$isFull ? 'disabled' : ''}}>
            {{$isFull ? 'Sold Out' : 'Book Now'}}
            </button>
            </form>
            @if($isFull)
            <div class="mb-3 p-2 bg-red-100 text-red-800 border border-red-300 rounded mt-2">
               {{ ('The event is full. You cannot book it. Check other events ->') }}
               <dic class="mt-4"><a href="{{ route('events.public') }}" class="btn btn-secondary">
                View All Events
                </a>
            </div>
            @endif

        @endif
        @endif
    @endauth
</x-app-layout>