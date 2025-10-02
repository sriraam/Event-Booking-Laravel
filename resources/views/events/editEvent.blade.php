<x-app-layout>
    <hi class="text-2xl mb-4">Edit Event</h1>

    <form method="POST" action="{{ route('events.update',$event) }}" class="space-y-3">
        @csrf 
        @method('PUT')

        <div>
            <label class"block">Title</label>
            <input name="title" value="{{ old('title', $event->title) }}" class="border p-2 w-full" required>
        </div>
        <label class="block mb-2">Description</label>
        <textarea name="description" rows="4" 
                  class="w-full border rounded mb-3 p-2">{{ old('description', $event->description) }}</textarea>
        <div>
            <label class="block">Starts At</label>
            <input type="datetime-local" name="starts_at"
                  value="{{ old('starts_at', $event->starts_at->format('Y-m-d\TH:i')) }}"
                  class="border p-2 w-full" required>
        </div>
        <div>
            <label class="block">Location</label>
            <input name="location"
                  value="{{ old('location', $event->location) }}"
                  class="border p-2 w-full" required>
        </div>

        <div>
            <label class="block">Capacity</label>
            <input type="number" min="1" max="1000" name="capacity"
                  value="{{ old('capacity', $event->capacity) }}" class="border p-2 w-full" required>
        </div>

        <div>
        <label class="block mb-2 font-semibold">Categories</label>
        <select name="category_ids[]" multiple class="multi-category">
        @php 
            $selected = old('category_ids', $event->categories->pluck('id')->toArray()); 
        @endphp
            @foreach($categories as $cat)
             <option value="{{ $cat->id }}" @selected(in_array($cat->id, $selected))>
              {{ $cat->name }}
             </option>
             @endforeach
        </select>
        </div>

        <button type="submit" class="px-3 py-2 border">Update</button>
  </form>
  @if ($errors->any())
    <div class="alert-error mt-2">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
</x-app-layout>