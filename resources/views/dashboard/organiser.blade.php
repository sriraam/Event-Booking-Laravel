<x-app-layout>
    <h1 class="font-semibold text-xl text-gray-800 leading-tight">Organiser Dashboard</h1>
    <a href="{{ route('events.create') }}" class="btn btn-success mb-3"> Create New Event</a>
    
    @if(empty($events))
        <p>Ready to host something awesome? Create your first event.</p>
    @else
        <table class"table-report">
            <thead>
                <tr>
                    <th>Event Title</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Total Capacity</th>
                    <th>Bookings</th>
                    <th>Available</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $row)
                    <tr>
                        <td><a class="link" href="{{route('events.show', $row->id)}}">{{$row->title}}</a></td>
                        <td>{{\Carbon\Carbon::parse($row->starts_at)->format('d M Y H:i')}}</td>
                        <td> {{$row->location}} </td>
                        <td class="text-center">{{$row->capacity}} </td>
                        <td class="text-center">{{$row->total_bookings}} </td>
                        <td class="text-center">{{$row->available_seats}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
 </x-app-layout>