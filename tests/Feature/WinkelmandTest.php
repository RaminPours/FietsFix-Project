<?php

namespace Tests\Feature;

use App\Models\Bestelling;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\MerkenSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WinkelmandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(MerkenSeeder::class);
    }

    public function test_all_eight_brands_have_images_and_can_be_filtered(): void
    {
        $this->seed(MerkenSeeder::class);
        $this->assertDatabaseCount('producten', 8);
        foreach (config('winkel.merken') as $merk) {
            $product = Product::where('merk', $merk)->firstOrFail();
            $this->assertFileExists(public_path($product->afbeelding));
            $this->get(route('winkel.index', ['merk' => $merk]))->assertOk()
                ->assertSee($product->naam)->assertViewHas('producten', fn ($producten) => $producten->total() === 1);
        }
        $this->get(route('winkel.index', ['merk' => 'Cube', 'categorie' => 'Stadsfiets']))
            ->assertOk()->assertSee('Er zijn op dit moment geen fietsen in deze categorie.');
    }

    public function test_guest_can_add_update_and_remove_a_bike(): void
    {
        $product = Product::first();
        $this->post(route('winkelmand.toevoegen', $product))->assertRedirect(route('winkelmand.index'));
        $this->get(route('winkelmand.index'))->assertOk()->assertSee($product->naam)->assertSee('Winkelmandje (1)');
        $this->patch(route('winkelmand.wijzigen', $product), ['aantal' => 3])
            ->assertSessionHas('winkelmand.'.$product->id, 3);
        $this->get(route('afrekenen.create'))->assertRedirect(route('login'));
        $this->delete(route('winkelmand.verwijderen', $product))->assertRedirect(route('winkelmand.index'));
        $this->get(route('winkelmand.index'))->assertOk()->assertSee('Je winkelmandje is nog leeg.');
    }

    public function test_quantity_limits_and_unavailable_products(): void
    {
        $product = Product::first();
        $this->post(route('winkelmand.toevoegen', $product));
        foreach ([0, 6, -1, 'abc', 1.5] as $aantal) {
            $this->patch(route('winkelmand.wijzigen', $product), ['aantal' => $aantal])->assertSessionHasErrors('aantal');
        }
        $this->patch(route('winkelmand.wijzigen', $product), ['aantal' => 5]);
        $this->post(route('winkelmand.toevoegen', $product))->assertSessionHasErrors('aantal')
            ->assertSessionHas('winkelmand.'.$product->id, 5);
        $product->update(['actief' => false]);
        $this->post(route('winkelmand.toevoegen', $product))->assertNotFound();
        $this->get(route('winkelmand.index'))->assertOk()->assertViewHas('kanBestellen', false);
        $this->actingAs(User::factory()->create())->get(route('afrekenen.create'))->assertRedirect(route('winkelmand.index'));
        $product->delete();
        $this->get(route('winkelmand.index'))->assertOk()->assertSee('Dit artikel is verwijderd');
        $this->delete(route('winkelmand.verwijderen', $product->id))->assertRedirect(route('winkelmand.index'));
    }

    private function checkoutGegevens(): array
    {
        $response = $this->get(route('afrekenen.create'))->assertOk();

        return [
            'naam' => 'Shop Klant', 'email' => 'shop@example.com',
            'telefoonnummer' => '0612345678', 'vestiging' => 'Nijmegen',
            'formuliernummer' => $response->viewData('formuliernummer'),
        ];
    }

    public function test_whole_cart_is_saved_once_with_correct_prices_and_owner(): void
    {
        $producten = Product::orderBy('id')->take(2)->get();
        foreach ($producten as $product) {
            $this->post(route('winkelmand.toevoegen', $product));
        }
        $this->patch(route('winkelmand.wijzigen', $producten[0]), ['aantal' => 2]);
        $user = User::factory()->create();
        $this->actingAs($user);
        $gegevens = $this->checkoutGegevens();
        $gegevens['prijs_cent'] = 1;
        $gegevens['aantal'] = 999;
        $gegevens['user_id'] = 999;
        $this->post(route('afrekenen.store'), $gegevens)->assertRedirect(route('bestellingen.index'))
            ->assertSessionMissing('winkelmand');
        $this->post(route('afrekenen.store'), $gegevens)->assertRedirect(route('bestellingen.index'));
        $this->assertDatabaseCount('bestellingen', 2);
        $this->assertDatabaseHas('bestellingen', [
            'user_id' => $user->id, 'product_id' => $producten[0]->id,
            'aantal' => 2, 'prijs_cent' => $producten[0]->prijs_cent,
            'bestelnummer' => $gegevens['formuliernummer'],
        ]);
        $this->assertSame(1, Bestelling::distinct()->count('bestelnummer'));
    }

    public function test_changed_price_rolls_back_whole_order_and_keeps_cart(): void
    {
        $this->actingAs(User::factory()->create());
        $producten = Product::orderBy('id')->take(2)->get();
        foreach ($producten as $product) {
            $this->post(route('winkelmand.toevoegen', $product));
        }
        $gegevens = $this->checkoutGegevens();
        $producten[1]->update(['prijs_cent' => 1]);
        $this->post(route('afrekenen.store'), $gegevens)->assertSessionHasErrors('mandje')->assertSessionHas('winkelmand');
        $this->assertDatabaseCount('bestellingen', 0);
    }

    public function test_empty_cart_changed_cart_and_invalid_details_cannot_be_ordered(): void
    {
        $this->actingAs(User::factory()->create());
        $this->get(route('afrekenen.create'))->assertRedirect(route('winkelmand.index'));
        $product = Product::first();
        $this->post(route('winkelmand.toevoegen', $product));
        $gegevens = $this->checkoutGegevens();
        $this->post(route('afrekenen.store'), array_merge($gegevens, ['email' => 'ongeldig', 'vestiging' => 'Onbekend']))
            ->assertSessionHasErrors(['email', 'vestiging']);
        $this->patch(route('winkelmand.wijzigen', $product), ['aantal' => 2]);
        $this->post(route('afrekenen.store'), $gegevens)->assertRedirect(route('winkelmand.index'));
        $this->assertDatabaseCount('bestellingen', 0);
    }
}
