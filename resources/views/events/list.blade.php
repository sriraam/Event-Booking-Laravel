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

            @if($et->category?->name)
                <span class="inline-block mt-1 text-xs px-2 py-0.5 rounded" 
                style="background: {{ $color }}20; border: 1px solid {{ $color }}; color: {{ $color }}">
                    {{ $et->category->name }}
                </span>
            @endif
        </li>
    @endforeach
</ul>

@if($events->hasPages())
    <div class="pagination-ajax mt-4">
        {{ $events->onEachSide(1)->links() }}
    </div>
@endif