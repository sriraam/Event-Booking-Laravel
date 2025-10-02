<x-app-layout>
    <h1 class="text-2xl mb-4">Create Event</h1>
    <!--List any errors-->
    @if ($errors->any())
        <div class="alert-error">
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('events.store') }}">@csrf
        <label>Title <input name="title" value="{{old('title')}}" required></label><br>
        <label>Description</label><textarea name="description" rows="4" class="w-full border rounded">
        {{ old('description', $event->description ?? '') }}</textarea><br>
        <label>Starts At <input type="datetime-local" name="starts_at" required></label><br>
        <label>Location <input name="location" value="{{ old('location')}}" required></label><br>
        <label>Capacity <input type="number" name="capacity" min="1" required></label><br>
        <label class="block mb-1">Categories</label>
        <select name="category_ids[]" multiple class="multi-category">
          @foreach($categories as $cat)
              <option value="{{ $cat->id }}" @selected(in_array($cat->id, old('category_ids', [])))>
               {{ $cat->name }}
              </option>
         @endforeach
        </select>
        <div class="pt-2">
            <button class="btn btn-primary">Update Event</button>
            <a class="btn btn-secondary" href="{{ route('dashboard') }}">Cancel</a>
        </div>
    </form>
</x-app-layout>