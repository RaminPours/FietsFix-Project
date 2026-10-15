<?php

namespace Tests\Feature;

use App\Models\Bestelling;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class BestellingAnnulerenTest extends TestCase
{
    use RefreshDatabase;

    private function bestelling(User $user, string $status = 'Ontvangen'): Bestelling
    {
        return Bestelling::create([
            'user_id' => $user->id, 'product_naam' => 'Fietslamp', 'prijs_cent' => 1495,
            'aantal' => 2, 'naam' => $user->name, 'email' => $user->email,
            'telefoonnummer' => '0612345678', 'vestiging' => 'Nijmegen',
            'status' => $status, 'formuliernummer' => (string) Str::uuid(),
        ]);
    }

    public function test_customer_can_cancel_an_owned_received_item_and_keep_history(): void
    {
        $this->withoutVite();
        $user = User::factory()->create();
        $bestelling = $this->bestelling($user);
        $this->actingAs($user)->get(route('bestellingen.index'))->assertSee('Annuleren');
        $this->patch(route('bestellingen.annuleren', $bestelling->id))
            ->assertRedirect(route('bestellingen.index'))->assertSessionHas('success');
        $this->assertDatabaseHas('bestellingen', ['id' => $bestelling->id, 'status' => 'Geannuleerd', 'aantal' => 2]);
        $this->get(route('bestellingen.index'))->assertSee('Geannuleerd')->assertDontSee('>Annuleren<', false);
    }

    public function test_guests_and_other_customers_cannot_cancel_an_order(): void
    {
        $bestelling = $this->bestelling(User::factory()->create());
        $this->patch(route('bestellingen.annuleren', $bestelling->id))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())
            ->patch(route('bestellingen.annuleren', $bestelling->id))->assertNotFound();
        $this->assertSame('Ontvangen', $bestelling->fresh()->status);
    }

    public function test_processed_and_already_cancelled_orders_cannot_be_changed(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        foreach (['Geannuleerd', 'In behandeling', 'Afgehaald'] as $status) {
            $bestelling = $this->bestelling($user, $status);
            $this->patch(route('bestellingen.annuleren', $bestelling->id))->assertSessionHasErrors('annuleren');
            $this->assertSame($status, $bestelling->fresh()->status);
        }
    }

    public function test_other_items_in_the_same_order_are_not_cancelled(): void
    {
        $user = User::factory()->create();
        $eerste = $this->bestelling($user);
        $tweede = $this->bestelling($user);
        $nummer = (string) Str::uuid();
        $eerste->update(['bestelnummer' => $nummer]);
        $tweede->update(['bestelnummer' => $nummer]);
        $this->actingAs($user)->patch(route('bestellingen.annuleren', $eerste->id));
        $this->assertSame('Geannuleerd', $eerste->fresh()->status);
        $this->assertSame('Ontvangen', $tweede->fresh()->status);
    }
}
