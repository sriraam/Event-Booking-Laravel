<x-app-layout>
@if(session('ok'))
    <div class="text-green-600">{{ session('ok') }}</div>
    @endif
    <h1 class="text-2x1 mb-2">{{$event->title}}</h1>
    <p>{{ $event->starts_at->toDayDateTimeString() }} | {{$event->location}}</p>
    
    @if(auth()->id() === $event->creator_id)
        <a class="underline" href="{{ route('events.edit',$event) }}">Edit</a>
        <form action="{{ route('events.destroy',$event) }}" method="POST" style="display:inline">
            @csrf @method('DELETE') <button>Delete</button>
        </form>
    @endif
</x-app-layout>