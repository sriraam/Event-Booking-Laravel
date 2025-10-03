<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendeeActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_successfully_register_as_an_attendee()
    {
        $res = $this->post(route('register'), [
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'accept_terms' => '1',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'alice@example.com', 'role' => 'attendee']);
    }

    public function test_a_registered_attendee_can_log_in_and_log_out()
    {
        $user = User::factory()->attendee()->create(['password' => bcrypt('password')]);

        $this->post(route('login'), ['email' => $user->email, 'password' => 'password'])
             ->assertRedirect();

        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect();
        $this->assertGuest();
    }

    public function test_a_logged_in_attendee_can_book_an_available_upcoming_event()
{
    $user  = \App\Models\User::factory()->attendee()->create();
    $event = \App\Models\Event::factory()->create([
        'capacity'  => 3,
        'starts_at' => now()->addDays(3),
    ]);

    $this->actingAs($user);

    // Route param is the event; body data is optional if controller doesn't validate event_id
    $response = $this->post(route('bookings.store', $event), [
        'event_id' => $event->id, // safe even if controller stops requiring it
    ]);

    $response->assertRedirect(route('bookings.index'));

    $this->assertDatabaseHas('bookings', [
        'user_id'  => $user->id,
        'event_id' => $event->id,
    ]);
    }

    public function test_after_booking_an_attendee_can_see_the_event_on_their_bookings_page()
    {
    $user  = \App\Models\User::factory()->attendee()->create();
    $event = \App\Models\Event::factory()->create([
        'capacity'  => 3,
        'starts_at' => now()->addDays(2),
    ]);

    $this->actingAs($user)->post(route('bookings.store', $event), [
        'event_id' => $event->id,
    ]);

    $res = $this->actingAs($user)->get(route('bookings.index'));
    $res->assertOk()->assertSeeText($event->title);
    }

public function test_an_attendee_cannot_book_the_same_event_more_than_once()
{
    $user  = \App\Models\User::factory()->attendee()->create();
    $event = \App\Models\Event::factory()->create(['capacity' => 5]);

    // First booking succeeds
    $this->actingAs($user)
         ->post(route('bookings.store', $event), ['event_id' => $event->id])
         ->assertRedirect(route('bookings.index'));

    // Second booking should hit the duplicate guard in controller
    $this->actingAs($user)
         ->post(route('bookings.store', $event), ['event_id' => $event->id])
         ->assertSessionHasErrors('booking');

    // Still only one booking in DB
    $this->assertDatabaseCount('bookings', 1);
}

public function test_an_attendee_cannot_book_a_full_event()
{
    // Create an event with capacity 1
    $event = \App\Models\Event::factory()->create(['capacity' => 1]);

    // Fill it with another user
    $firstUser = \App\Models\User::factory()->attendee()->create();
    \App\Models\Booking::create(['user_id' => $firstUser->id, 'event_id' => $event->id]);

    // Try to book with a different attendee -> should fail capacity check
    $user = \App\Models\User::factory()->attendee()->create();

    $this->actingAs($user)
         ->post(route('bookings.store', $event), ['event_id' => $event->id])
         ->assertSessionHasErrors('booking');
}

    public function test_an_attendee_cannot_see_edit_or_delete_buttons_on_any_event_page()
    {
        $event = Event::factory()->create();
        $user = User::factory()->attendee()->create();

        $res = $this->actingAs($user)->get(route('events.show', $event));
        $res->assertOk()
            ->assertDontSee('Edit')
            ->assertDontSee('Delete');
    }
}
