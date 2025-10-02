<x-app-layout>
<h1 class="text-2xl mb-4">Upcoming Events</h1>
    <div class="mb-3">
        @auth
            @if(auth()->user()->role ==='organiser')
                <a href="{{route('events.create')}}">Add new Event</a>
            @endif
        @endauth
    </div>
<!-- Category Dropdown-->
<div class="mb-3">
        <label class="mr-2">Category:</label>
        <select id="categorySelect" class="border p-1 rounded">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

<!-- Event list -->
    <div id="event-list">
        @include('events.list',['events' => $events])
    </div>
    <script>
(function () {
    // Shortcuts for DOM elements
    const eventList = document.querySelector('#event-list');
    const categoryMenu = document.querySelector('#categorySelect');

    if (!eventList || !categoryMenu) return; // nothing to do if missing

    // Function: refresh the event list from server
    function refreshEvents(targetUrl) {
        // timestamp param avoids cached responses
        const urlObj = new URL(targetUrl, window.location.origin);
        urlObj.searchParams.set('t', Date.now());

        fetch(urlObj.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            eventList.innerHTML = html;

            // update browser address bar nicely
            const displayUrl = new URL("{{ route('events.public') }}", window.location.origin);
            const chosen = urlObj.searchParams.get('category_id');
            if (chosen) displayUrl.searchParams.set('category_id', chosen);
            history.replaceState({}, '', displayUrl.pathname + displayUrl.search);
        })
        .catch(err => console.error('Event filter error:', err));
    }

    // When category dropdown changes
    categoryMenu.addEventListener('change', e => {
        const chosenId = e.target.value;
        refreshEvents(`{{ route('events.filter') }}?category_id=${encodeURIComponent(chosenId)}`);
        localStorage.setItem('last_category', chosenId);
    });

    // Handle AJAX pagination clicks
    eventList.addEventListener('click', e => {
        const link = e.target.closest('a');
        if (link && link.closest('.pagination-ajax')) {
            e.preventDefault();
            refreshEvents(link.href);
        }
    });

    // If user had a saved filter, reload with that
    const remembered = localStorage.getItem('last_category');
    if (remembered && categoryMenu.value === '') {
        categoryMenu.value = remembered;
        refreshEvents(`{{ route('events.filter') }}?category_id=${encodeURIComponent(remembered)}`);
    }
})();
</script>

@guest
  <p class="mt-4 text-sm text-gray-600">
    To Book an Event, Please <a class="underline" href="{{route('login')}}">Login Here</a> / 
    <a class="underline" href="{{ route('register') }}">Register</a>.
  </p>
  @endguest
</x-app-layout>

