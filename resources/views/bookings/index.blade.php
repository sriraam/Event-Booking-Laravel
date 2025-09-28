<x-app-layout>
    <h1 class="text-2xl mb-4"> My Bookings</h1>
    <ul>
        @forelse($bookings as $bk)
        <li>
            <a class="underline" href="{{ route('events.show',$bk->event) }}">{{$bk->event->title}}</a>
            -{{ $bk->event->starts_at->format('s M Y H:i') }} at {{ $bk->event->location }}
        </li>
        @empty
        <li>No Bookings</li>
        @endforelse
    </ul> 
</x-app-layout>