<?php

namespace Tests\Feature;

use App\Models\Afspraken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AfsprakenTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function afspraakData(): array
    {
        return [
            'naam' => 'Ramin', 'email' => 'ramin@example.com',
            'telefoonnummer' => '0612345678', 'fietstype' => 'E-bike',
            'fietsmerk' => 'Gazelle', 'probleem' => 'Lekke band',
            'vestiging' => 'Nijmegen',
            'datum' => now()->addDay()->toDateString(), 'tijd' => '10:30',
        ];
    }

    public function test_guests_see_login_and_cannot_access_appointments(): void
    {
        $this->get('/')->assertOk()->assertSee('Inloggen')->assertDontSee('Afspraak inzien');
        foreach (['/dashboard', '/afspraken', '/afspraken/create'] as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }
        $this->post('/afspraken', $this->afspraakData())->assertRedirect(route('login'));
        $this->assertDatabaseCount('afspraken', 0);
    }

    public function test_user_sees_empty_dashboard_and_authenticated_navigation(): void
    {
        $this->actingAs(User::factory()->create());
        $this->get('/')->assertOk()->assertSee('Afspraak inzien')->assertDontSee('>Inloggen<', false);
        $this->get('/dashboard')->assertOk()->assertSee('Je hebt nog geen afspraken.');
        $this->get('/afspraken/create')->assertOk()->assertSee('Afspraak inplannen');
    }

    public function test_booking_is_assigned_to_authenticated_user(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/afspraken', array_merge($this->afspraakData(), ['user_id' => 999]))
            ->assertRedirect(route('dashboard'))->assertSessionHas('success');
        $this->assertDatabaseHas('afspraken', ['user_id' => $user->id, 'probleem' => 'Lekke band', 'vestiging' => 'Nijmegen']);
        $this->get('/dashboard')->assertOk()->assertSee('Lekke band')->assertSee('10:30')->assertSee('Nijmegen');
    }

    public function test_users_cannot_view_or_delete_another_users_appointment(): void
    {
        $owner = User::factory()->create();
        $afspraak = Afspraken::create(array_merge($this->afspraakData(), ['user_id' => $owner->id, 'probleem' => 'Privé reparatie']));
        $this->actingAs(User::factory()->create());
        $this->get('/dashboard')->assertOk()->assertDontSee('Privé reparatie');
        $this->get('/afspraken')->assertOk()->assertDontSee('Privé reparatie');
        $this->delete('/afspraken/'.$afspraak->id)->assertNotFound();
        $this->assertDatabaseHas('afspraken', ['id' => $afspraak->id]);
        $this->actingAs($owner)->delete('/afspraken/'.$afspraak->id)->assertRedirect(route('afspraken.index'));
        $this->assertDatabaseMissing('afspraken', ['id' => $afspraak->id]);
    }

    public function test_invalid_booking_is_not_saved(): void
    {
        $this->actingAs(User::factory()->create())->post('/afspraken', array_merge($this->afspraakData(), [
            'email' => 'invalid', 'datum' => now()->subDay()->toDateString(), 'tijd' => '25:99',
        ]))->assertSessionHasErrors(['email', 'datum', 'tijd']);
        $this->assertDatabaseCount('afspraken', 0);
    }

    public function test_branch_is_required_and_must_be_an_available_location(): void
    {
        $this->actingAs(User::factory()->create());

        foreach (['', 'Onbekende vestiging'] as $vestiging) {
            $this->post('/afspraken', array_merge($this->afspraakData(), [
                'vestiging' => $vestiging,
            ]))->assertSessionHasErrors('vestiging');
        }

        $this->assertDatabaseCount('afspraken', 0);
    }

    public function test_booking_form_shows_all_ten_branches(): void
    {
        $response = $this->actingAs(User::factory()->create())->get('/afspraken/create')->assertOk();

        $this->assertCount(10, config('vestigingen.locaties'));
        foreach (config('vestigingen.locaties') as $vestiging) {
            $response->assertSee('value="'.$vestiging.'"', false);
        }
    }

    public function test_same_slot_cannot_be_booked_by_another_customer_at_the_same_branch(): void
    {
        $this->actingAs(User::factory()->create())->post('/afspraken', $this->afspraakData())
            ->assertRedirect(route('dashboard'));

        $this->actingAs(User::factory()->create())->from(route('afspraken.create'))
            ->post('/afspraken', $this->afspraakData())
            ->assertRedirect(route('afspraken.create'))
            ->assertSessionHasErrors('tijd')
            ->assertSessionHasInput('vestiging', 'Nijmegen');

        $this->assertDatabaseCount('afspraken', 1);
    }

    public function test_availability_only_returns_reserved_times_for_selected_branch_and_date(): void
    {
        $owner = User::factory()->create();
        foreach ([[], ['vestiging' => 'Amsterdam', 'tijd' => '11:00'],
            ['datum' => now()->addDays(2)->toDateString(), 'tijd' => '12:00']] as $change) {
            Afspraken::create(array_merge($this->afspraakData(), ['user_id' => $owner->id], $change));
        }

        $this->actingAs(User::factory()->create())
            ->post(route('afspraken.store'), array_merge($this->afspraakData(), [
                'actie' => 'beschikbaarheid',
            ]))->assertRedirect(route('afspraken.create'));

        $this->get(route('afspraken.create'))->assertOk()
            ->assertViewHas('bezetteTijden', ['10:30'])
            ->assertSee('value="10:30" disabled', false)
            ->assertSee('10:30 (gereserveerd)')
            ->assertSee('value="11:00"  ', false)
            ->assertSee('value="Ramin"', false)
            ->assertDontSee('<script>', false);
        $this->assertDatabaseCount('afspraken', 3);
    }

    public function test_availability_requires_authentication_and_valid_branch_and_date(): void
    {
        $this->post(route('afspraken.store'), ['actie' => 'beschikbaarheid'])
            ->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())
            ->post(route('afspraken.store'), [
                'actie' => 'beschikbaarheid', 'vestiging' => 'Onbekend', 'datum' => 'ongeldig',
            ])->assertSessionHasErrors(['vestiging', 'datum']);
    }

    public function test_fully_booked_day_disables_booking_and_availability_needs_no_customer_details(): void
    {
        $user = User::factory()->create();
        foreach (Afspraken::tijdstippen() as $tijd) {
            Afspraken::create(array_merge($this->afspraakData(), ['user_id' => $user->id, 'tijd' => $tijd.':00']));
        }

        $this->actingAs($user)->post(route('afspraken.store'), [
            'actie' => 'beschikbaarheid', 'vestiging' => 'Nijmegen',
            'datum' => $this->afspraakData()['datum'],
        ])->assertRedirect(route('afspraken.create'))->assertSessionHasNoErrors();
        $this->get(route('afspraken.create'))->assertOk()
            ->assertViewHas('volgeboekt', true)->assertSee('Deze dag is volgeboekt.');
    }

    public function test_other_branch_date_or_time_can_still_be_booked(): void
    {
        $this->actingAs(User::factory()->create());
        $this->post('/afspraken', $this->afspraakData())->assertRedirect(route('dashboard'));

        foreach ([
            ['vestiging' => 'Amsterdam'],
            ['datum' => now()->addDays(2)->toDateString()],
            ['tijd' => '11:30'],
        ] as $change) {
            $this->post('/afspraken', array_merge($this->afspraakData(), $change))
                ->assertRedirect(route('dashboard'))->assertSessionHasNoErrors();
        }

        $this->assertDatabaseCount('afspraken', 4);
    }

    public function test_times_outside_opening_hours_or_between_quarters_are_rejected(): void
    {
        $this->actingAs(User::factory()->create());

        foreach (['07:45', '17:15', '10:10', '08:01', '16:59'] as $tijd) {
            $this->post('/afspraken', array_merge($this->afspraakData(), ['tijd' => $tijd]))
                ->assertSessionHasErrors('tijd');
        }

        $this->assertDatabaseCount('afspraken', 0);
    }

    public function test_opening_closing_and_quarter_hour_times_are_accepted(): void
    {
        $this->actingAs(User::factory()->create());

        foreach (['08:00', '08:15', '08:30', '08:45', '17:00'] as $tijd) {
            $this->post('/afspraken', array_merge($this->afspraakData(), ['tijd' => $tijd]))
                ->assertRedirect(route('dashboard'))->assertSessionHasNoErrors();
        }

        $this->assertDatabaseCount('afspraken', 5);
    }
}
