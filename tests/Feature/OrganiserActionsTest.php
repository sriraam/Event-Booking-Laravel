<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrganiserActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_organiser_can_log_in_and_view_their_specific_dashboard()
    {
        $org = User::factory()->organiser()->create(['password' => bcrypt('password')]);

        $this->post(route('login'), ['email' => $org->email, 'password' => 'password'])
             ->assertRedirect();

        $this->assertAuthenticatedAs($org);

        $this->get(route('dashboard'))->assertOk();
    }

    public function test_an_organiser_can_successfully_create_an_event_with_valid_data()
    {
        $org = User::factory()->organiser()->create();
        $cats = Category::factory()->count(2)->create();

        $payload = [
            'title' => 'Boardgame Night',
            'description' => 'Fun night',
            'starts_at' => now()->addDays(2)->toDateTimeString(),
            'location' => 'Main Hall',
            'capacity' => 20,
            'category_ids' => $cats->pluck('id')->all(),
        ];

        $res = $this->actingAs($org)->post(route('events.store'), $payload);
        $res->assertRedirect(); // to events.show

        $this->assertDatabaseHas('events', ['title' => 'Boardgame Night', 'creator_id' => $org->id]);
        $this->assertDatabaseCount('category_event', 2);
    }

    public function test_an_organiser_receives_validation_errors_for_invalid_event_data()
    {
        $org = User::factory()->organiser()->create();

        $res = $this->actingAs($org)->post(route('events.store'), [
            // missing title, starts_at (past), no categories etc.
            'title' => '',
            'starts_at' => now()->subDay()->toDateTimeString(),
            'location' => '',
            'capacity' => 0,
            'category_ids' => [], // required|array|min:1
        ]);

        $res->assertSessionHasErrors(['title','starts_at','location','capacity','category_ids']);
    }

    public function test_an_organiser_can_successfully_update_an_event_they_own()
    {
        $org = User::factory()->organiser()->create();
        $event = Event::factory()->create(['creator_id' => $org->id]);
        $cats = Category::factory()->count(2)->create();

        $res = $this->actingAs($org)->patch(route('events.update', $event), [
            'title' => 'Updated Title',
            'starts_at' => now()->addDays(3)->toDateTimeString(),
            'location' => 'Updated Hall',
            'capacity' => 30,
            'category_ids' => $cats->pluck('id')->all(),
        ]);

        $res->assertRedirect(route('events.show', $event));
        $this->assertDatabaseHas('events', ['id' => $event->id, 'title' => 'Updated Title']);
        $this->assertDatabaseCount('category_event', 2);
    }

    public function test_an_organiser_cannot_update_an_event_created_by_another_organiser()
    {
        $owner = User::factory()->organiser()->create();
        $other = User::factory()->organiser()->create();

        $event = Event::factory()->create(['creator_id' => $owner->id]);

        $this->actingAs($other)
             ->patch(route('events.update', $event), [
                 'title' => 'Nope',
                 'starts_at' => now()->addDays(2)->toDateTimeString(),
                 'location' => 'X',
                 'capacity' => 10,
                 'category_ids' => [],
             ])->assertStatus(403); // abort_unless guard
    }

    public function test_an_organiser_can_delete_an_event_they_own_that_has_no_bookings()
    {
        $org = User::factory()->organiser()->create();
        $event = Event::factory()->create(['creator_id' => $org->id]);

        $res = $this->actingAs($org)->delete(route('events.destroy', $event));
        $res->assertRedirect(); // to public/events page

        $this->assertModelMissing($event);
    }

    public function test_an_organiser_cannot_delete_an_event_that_has_active_bookings()
    {
        $org = User::factory()->organiser()->create();
        $event = Event::factory()->create(['creator_id' => $org->id]);

        // one attendee books
        $att = User::factory()->attendee()->create();
        \App\Models\Booking::factory()->create(['user_id' => $att->id, 'event_id' => $event->id]);

        $res = $this->actingAs($org)->delete(route('events.destroy', $event));
        $res->assertRedirect(route('events.show', $event));
        $res->assertSessionHas('error');

        $this->assertDatabaseHas('events', ['id' => $event->id]); // not deleted
    }
}
