<x-app-layout>
  <h1 class="text-2xl font-semibold mb-4">Privacy Policy</h1>
  <p>Last updated: {{ now()->toDateString() }}</p>

  <h2 class="font-semibold mt-4">Data We Collect</h2>
  <ul class="list-disc ms-6">
    <li>Name, email, password</li>
    <li>Booking history</li>
    <li>Consent logs (time)</li>
  </ul>

  <h2 class="font-semibold mt-4">Purpose</h2>
  <p>Authentication, event participation, and service improvements.</p>

  <h2 class="font-semibold mt-4">Protection</h2>
  <p>Passwords are hashed; data access is restricted.</p>

  <h2 class="font-semibold mt-4">Your Rights</h2>
  <ul class="list-disc ms-6">
    <li>View, update, and delete your data</li>
    <li>Request account deletion</li>
  </ul>
</x-app-layout>