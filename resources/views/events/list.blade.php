<ul class="space-y-3">
    @foreach($events as $et)
        @php
            $color = $et->category?->color ?? '#3bffff';
        @endphp

        <li class="p-3 border rounded hover:bg-gray-50">
            <a href="{{ route('events.show',$et) }}" class="font-semibold text-blue-600 hover:underline">
                {{ $et->title }}
            </a>

            <div class="text-sm text-gray-600">
                {{$et->starts_at->format('d M Y H:i') }} | {{$et->location}}
            </div>

            <div class="text-sm text-gray-700">
                Organiser:{{$et->creator->name ?? '---'}}
            </div>

            <!-- Category badges -->
            @if($et->categories && $et->categories->isNotEmpty())
            <div class="mt-1 flex flex-wrap gap-2">
              @foreach($et->categories as $cat)
                  <span class="inline-block text-xs px-2 py-0.5 rounded"
                  style="background: {{ $cat->color }}20; border: 1px solid {{ $cat->color }}; color: {{ $cat->color }}">
                   {{ $cat->name }}
                 </span>
             @endforeach
            </div>
            @endif
            
        </li>
    @endforeach
</ul>

@if($events->hasPages())
    <div class="pagination-ajax mt-4">
        {{ $events->onEachSide(1)->links() }}
    </div>
@endif