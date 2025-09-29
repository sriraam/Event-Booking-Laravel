<x-app-layout>
<div class="flex gap-3 mb-4">
    <a href="{{ route('events.index') }}">List View</a>
    <strong>Calendar View</strong>
  </div>
  <h1 class="text-2xl mb-3">
    {{ \Carbon\Carbon::create($year,$month,1)->format('F Y') }}
  </h1>
  <div class="grid grid-cols-7 gap-2">
  @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $weekday)
    <div class="font-semibold text-center">{{ $weekday }}</div>
  @endforeach

  @php
    $firstDay = $start->copy()->startOfMonth();
    $lastDay = $start->copy()->endOfMonth();
    $offset = (int) $firstDay->format('N');
    $totalDays = (int) $lastDay->format('j');
  @endphp

  @for ($blank = 1; $blank < $offset; $blank++)
    <div class="p-2 border rounded bg-gray-100"></div>
  @endfor

  @for ($day = 1; $day <= $totalDays; $day++)
    @php
      $currentDate = \Carbon\Carbon::create($year, $month, $day);
      $dateKey = $currentDate->format('Y-m-d');
      $dailyEvents = $eventsByDay->get($dateKey) ?? collect();
    @endphp

    <div class="p-2 border rounded">
      <div class="text-xs text-gray-500 mb-2">{{ $day }}</div>

      @foreach($dailyEvents as $entry)
        @php
          $category = $entry->category;
          $highlight = $category?->color ?? '#3b82f6';
        @endphp

        <div class="mb-2 p-2 rounded text-sm" style="border-left:4px solid {{ $highlight }}; background-color: {{ $highlight }}15;">
          <div class="font-semibold">
            <a class="hover:underline" href="{{ route('events.show', $entry) }}">{{ $entry->title }}</a>
          </div>
          <div class="text-xs">{{ $entry->starts_at->format('H:i') }} • {{ $entry->location }}</div>

          @auth
            @if(auth()->user()->role === 'attendee')
              @if($entry->is_full)
                <form method="POST" action="{{ route('waitlist.store', $entry) }}" class="mt-1">@csrf
                  <button class="text-xs px-2 py-1 border rounded">Join Waitlist</button>
                </form>
              @else
                <form method="POST" action="{{ route('bookings.store', $entry) }}" class="mt-1">@csrf
                  <button class="text-xs px-2 py-1 border rounded">Book Now</button>
                </form>
              @endif
            @endif
          @endauth
        </div>
      @endforeach
    </div>
  @endfor
</div>
</x-app-layout>