<x-app-layout>
    <hi class="text-2xl mb-4">Edit Event</h1>

    <form method="POST" action="{{ route('events.update',$event) }}" class="space-y-3">
        @csrf @method('PUT')

        <div>
            <label class"block">Title</label>
            <input name="title" value="{{ old('title', $event->title) }}" class="border p-2 w-full" required>
        </div>

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

        <button class="px-3 py-2 border">Update</button>
  </form>


</x-app-layout>