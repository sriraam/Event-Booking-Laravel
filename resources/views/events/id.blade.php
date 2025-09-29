<x-app-layout>
<h1 class="text-2xl mb-4">Upcoming Events</h1>
    <div class="mb-3">
        @auth
            @if(auth()->user()->role ==='organiser')
                <a href="{{route('events.create')}}">Add new Event</a>
            @endif
        @endauth

<!-- Category Dropdown-->
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
window.addEventListener('error', e => console.log('[events] JS error:', e.message));

(function () {
  // ---- helpers -------------------------------------------------------------
  function $(sel) { return document.querySelector(sel); }
  function log(...a) { try { console.log('[events]', ...a); } catch(_){} }

  const listEl  = $('#event-list');
  const select  = $('#categorySelect');

  function loadList(url) {
    // add a cache-buster just in case
    const u = new URL(url, window.location.origin);
    u.searchParams.set('_ts', Date.now());

    log('AJAX →', u.toString());
    return fetch(u.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
      .then(r => { log('AJAX status', r.status); return r.text(); })
      .then(html => {
        log('AJAX html length', html.length);
        listEl.innerHTML = html;

        
        const pretty = new URL(`{{ route('events.public') }}`, window.location.origin);
        const id = u.searchParams.get('category_id') || '';
        if (id) pretty.searchParams.set('category_id', id);
        history.replaceState(null, '', pretty.pathname + pretty.search);
      })
      .catch(err => log('AJAX error', err));
  }

  // ---- events --------------------------------------------------------------

  select.addEventListener('change', function () {
    const id = this.value;
    log('change → category_id', id);
    loadList(`{{ route('events.filter') }}?category_id=${encodeURIComponent(id)}`);
    localStorage.setItem('event_filter_category', id);
  });

  // 2) AJAX pagination inside the list
  listEl.addEventListener('click', function (e) {
    const a = e.target.closest('a');
    if (a && a.closest('.pagination-ajax')) {
      e.preventDefault();
      log('pagination →', a.href);
      loadList(a.href);
    }
  });

  // 3) Restore saved filter on load
  const saved = localStorage.getItem('event_filter_category');
  if (saved && select.value === '') {
    log('restore saved category_id', saved);
    select.value = saved;
    loadList(`{{ route('events.filter') }}?category_id=${encodeURIComponent(saved)}`);
  } else {
    log('initial category_id', select.value || '(all)');
  }
})();
</script>
</x-app-layout>

