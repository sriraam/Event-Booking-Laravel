<x-guest-layout>
    <h1 class="text-2xl font-bold mb-4">Privacy Policy</h1>
    <p>Last updated: {{ now()->format('d M Y') }}</p>

    <p>
        This Privacy Policy explains how <strong>Coimbatore Boardgame House</strong> (“we,” “our,” “us”) 
        collects, uses, and protects your personal information when you interact with our platform.
        By creating an account or booking an event, you agree to this policy.
    </p>

    <h2 class="font-semibold mt-4">1. Data We Collect</h2>
    <ul class="list-disc ml-6">
        <li>Your name, email address, and password (for account creation)</li>
        <li>Booking details for events you register for</li>
        <li>Optional information you provide, such as feedback or preferences</li>
    </ul>

    <h2 class="font-semibold mt-4">2. Why We Collect It</h2>
    <p>We collect this information to:</p>
    <ul class="list-disc ml-6">
        <li>Manage user authentication and event participation</li>
        <li>Provide better service and support</li>
        <li>Ensure the security of our platform</li>
    </ul>

    <h2 class="font-semibold mt-4">3. How We Store and Protect It</h2>
    <ul class="list-disc ml-6">
        <li>Passwords are encrypted and stored securely</li>
        <li>Only authorised staff can access system-related data</li>
        <li>We implement technical and organisational safeguards to protect your data</li>
    </ul>

    <h2 class="font-semibold mt-4">4. User Rights</h2>
    <ul class="list-disc ml-6">
        <li>You can view and update your profile information anytime</li>
        <li>You can request account deletion by contacting support</li>
        <li>You have the right to withdraw consent to data collection by closing your account</li>
    </ul>

    <h2 class="font-semibold mt-4">5. Third-Party Services</h2>
    <p>
        We do not sell or rent your data. However, we may share limited information with
        trusted partners strictly for providing our services (e.g., payment processing if enabled).
    </p>

    <h2 class="font-semibold mt-4">6. Policy Updates</h2>
    <p>
        This policy may be updated from time to time. Users will be notified of significant
        changes via email or notice on our website.
    </p>

    <p class="mt-6 text-sm text-gray-600">
        © {{ now()->year }} <strong>Coimbatore Boardgame House</strong>.  
        All rights reserved.
    </p>

    <p class="mt-6">
        <a href="{{ route('register') }}" class="text-blue-600 underline">← Back to Registration</a>
    </p>
</x-guest-layout>