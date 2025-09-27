<x-app-layout>
    <h1 class="text-2xl mb-4">Create Event</h1>
    <form method="POST" action="{{ route('events.store') }}">@csrf
        <label>Title <input name="title" value="{{old('title')}}" required></label><br>
        <label>Starts At <input type="datetime-local" name="starts_at" required></label><br>
        <label>Location <input name="location" value="{{ old('location')}}" required></label><br>
        <label>Capacity <input type="number" name="capacity" min="1" required></label><br>
        <button>Save</button>
    </form>
</x-app-layout>