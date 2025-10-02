<x-guest-layout>
    <div class="max-w-3xl mx-auto bg-white shadow p-6 rounded">
        <h1 class="text-2xl font-bold mb-4">Terms of Service</h1>

        <p>Welcome to <strong>Coimbatore Boardgame House</strong>. By using our platform, you agree to the following terms:</p>

        <h2 class="mt-4 font-semibold">Use of Service</h2>
        <p>Users must register with accurate information. Organisers manage events responsibly.</p>

        <h2 class="mt-4 font-semibold">Event Participation</h2>
        <p>Bookings are subject to event availability. Organisers may cancel or reschedule events if required.</p>

        <h2 class="mt-4 font-semibold">User Responsibilities</h2>
        <p>You are responsible for keeping your account secure. Sharing login credentials is prohibited.</p>

        <h2 class="mt-4 font-semibold">Limitations</h2>
        <p>We are not liable for issues beyond our control, including third-party services or cancellations.</p>

        <p class="mt-6 text-sm text-gray-600">
            &copy; {{ now()->year }} Coimbatore Boardgame House. All rights reserved.
        </p>
    </div>
        <p class="mt-6">
            <a href="{{ route('register') }}" class="text-blue-600 underline">← Back to Registration</a>
        </p>
    
</x-guest-layout>