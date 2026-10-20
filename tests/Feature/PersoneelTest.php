<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Bestelling;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersoneelTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_staff_can_open_the_staff_dashboard(): void
    {
        $this->withoutVite();
        $this->get(route('personeel.index'))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())->get(route('personeel.index'))->assertForbidden();
        $this->actingAs(User::factory()->create(['is_personeel' => true]))
            ->get(route('personeel.index'))
            ->assertOk()
            ->assertSee('Personeelsbeheer');
    }

    public function test_staff_can_add_stock_but_customers_cannot(): void
    {
        $product = Product::create([
            'naam' => 'Testfiets', 'categorie' => 'Stadsfiets', 'beschrijving' => 'Testproduct',
            'prijs_cent' => 10000, 'afbeelding' => 'images/test.png', 'specificaties' => [], 'voorraad' => 2,
        ]);
        $staff = User::factory()->create(['is_personeel' => true]);

        $this->actingAs(User::factory()->create())
            ->patch(route('personeel.voorraad.aanvullen', $product), ['aantal' => 3])
            ->assertForbidden();
        $this->actingAs($staff)
            ->patch(route('personeel.voorraad.aanvullen', $product), ['aantal' => 3])
            ->assertRedirect(route('personeel.voorraad'));

        $this->assertDatabaseHas('producten', ['id' => $product->id, 'voorraad' => 5]);

        $this->actingAs($staff)
            ->patch(route('personeel.voorraad.verlagen', $product), ['aantal' => 2])
            ->assertRedirect(route('personeel.voorraad'));
        $this->assertDatabaseHas('producten', ['id' => $product->id, 'voorraad' => 3]);
    }

    public function test_staff_can_manage_order_statuses(): void
    {
        $klant = User::factory()->create();
        $bestelling = Bestelling::create([
            'user_id' => $klant->id, 'product_naam' => 'Testfiets', 'prijs_cent' => 10000,
            'aantal' => 1, 'naam' => $klant->name, 'email' => $klant->email,
            'telefoonnummer' => '0612345678', 'vestiging' => 'Nijmegen',
            'status' => 'Ontvangen', 'formuliernummer' => (string) Str::uuid(),
        ]);
        $staff = User::factory()->create(['is_personeel' => true]);

        $this->actingAs($klant)->get(route('personeel.bestellingen'))->assertForbidden();
        $this->actingAs($staff)->patch(route('personeel.bestellingen.status', $bestelling), [
            'status' => 'In behandeling',
        ])->assertRedirect(route('personeel.bestellingen'));

        $this->assertDatabaseHas('bestellingen', ['id' => $bestelling->id, 'status' => 'In behandeling']);
    }
}
