<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GuestAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_can_view_the_paginated_list_of_upcoming_events()
    {
        Event::factory()->count(12)->create(); // > one page

        $res = $this->get(route('events.index'));
        $res->assertOk();
        $res->assertSee('Upcoming Events'); // your page heading
    }


    public function test_a_guest_can_view_a_specific_event_details_page()
    {
        $event = Event::factory()->create();

        $res = $this->get(route('events.show', $event));
        $res->assertOk()
            ->assertSee($event->title)
            ->assertSee($event->location);
    }

    public function test_a_guest_is_redirected_when_accessing_protected_routes()
    {
        // organiser-only pages
        $this->get(route('events.create'))->assertRedirect(route('login'));
        // booking (attendee only)
        $event = Event::factory()->create();
        $this->post(route('bookings.store', $event))->assertRedirect(route('login'));
    }

    public function test_a_guest_cannot_see_action_buttons_on_event_details_page()
    {
        $event = Event::factory()->create();

        $res = $this->get(route('events.show', $event));
        $res->assertOk()
            ->assertDontSee('Edit')
            ->assertDontSee('Delete')
            ->assertDontSee('Book Now');
    }
}
