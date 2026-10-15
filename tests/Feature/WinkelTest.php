<?php

namespace Tests\Feature;

use App\Models\Bestelling;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WinkelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(ProductSeeder::class);
    }

    public function test_visitors_can_browse_filter_and_view_products(): void
    {
        $this->get('/winkel')->assertOk()->assertSee('FietsFix Stad')->assertSee('FietsFix Samen');
        $this->get('/winkel?categorie=E-bike')->assertOk()
            ->assertSee('FietsFix Elektrisch')->assertDontSee('FietsFix Stad');

        foreach (Product::all() as $product) {
            $this->get(route('winkel.show', $product))->assertOk()
                ->assertSee($product->naam)->assertSee('Specificaties');
            $this->assertFileExists(public_path($product->afbeelding));
        }
        $this->get('/winkel/99999')->assertNotFound();
    }

    public function test_guests_must_log_in_to_order(): void
    {
        $product = Product::first();
        $this->get(route('bestellingen.create', $product))->assertRedirect(route('login'));
        $this->post(route('bestellingen.store', $product))->assertRedirect(route('login'));
        $this->get(route('bestellingen.index'))->assertRedirect(route('login'));
    }

    private function gegevens(Product $product): array
    {
        $response = $this->get(route('bestellingen.create', $product))->assertOk();

        return [
            'naam' => 'Test Klant', 'email' => 'klant@example.com',
            'telefoonnummer' => '0612345678', 'vestiging' => 'Nijmegen',
            'aantal' => 2, 'formuliernummer' => $response->viewData('formuliernummer'),
        ];
    }

    public function test_order_uses_database_price_and_cannot_be_submitted_twice(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = Product::first();
        $gegevens = $this->gegevens($product);
        $gegevens['prijs_cent'] = 1;
        $gegevens['user_id'] = 999;

        for ($i = 0; $i < 2; $i++) {
            $this->post(route('bestellingen.store', $product), $gegevens)
                ->assertRedirect(route('bestellingen.index'))->assertSessionHasNoErrors();
        }
        $this->assertDatabaseCount('bestellingen', 1);
        $this->assertDatabaseHas('bestellingen', [
            'user_id' => $user->id, 'prijs_cent' => $product->prijs_cent, 'aantal' => 2,
        ]);
        $product->update(['prijs_cent' => 1, 'naam' => 'Nieuwe productnaam']);
        $this->get(route('bestellingen.index'))->assertOk()->assertSee('FietsFix Stad')->assertDontSee('Nieuwe productnaam');
    }

    public function test_invalid_orders_and_inactive_products_are_rejected(): void
    {
        $this->actingAs(User::factory()->create());
        $product = Product::first();
        $gegevens = $this->gegevens($product);
        $this->post(route('bestellingen.store', $product), array_merge($gegevens, [
            'aantal' => 0, 'email' => 'ongeldig', 'vestiging' => 'Onbekend',
        ]))->assertSessionHasErrors(['aantal', 'email', 'vestiging']);
        $this->post(route('bestellingen.store', $product), array_merge($gegevens, [
            'formuliernummer' => '00000000-0000-4000-8000-000000000000',
        ]))->assertStatus(419);
        $product->update(['actief' => false]);
        $this->get(route('winkel.show', $product))->assertNotFound();
        $this->post(route('bestellingen.store', $product), $gegevens)->assertNotFound();
        $this->assertDatabaseCount('bestellingen', 0);
    }

    public function test_customers_only_see_their_own_orders(): void
    {
        $this->actingAs(User::factory()->create());
        $product = Product::first();
        $this->post(route('bestellingen.store', $product), $this->gegevens($product))
            ->assertRedirect(route('bestellingen.index'));
        $this->actingAs(User::factory()->create());
        $this->get(route('bestellingen.index'))->assertOk()
            ->assertSee('Je hebt nog geen fietsen besteld.')->assertDontSee('FietsFix Stad');
    }
}
