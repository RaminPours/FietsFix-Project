<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Database\Seeders\AccessoiresSeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessoiresTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(ProductSeeder::class);
    }

    public function test_menu_categories_and_product_details_are_available_to_visitors(): void
    {
        $this->get('/winkel')->assertOk()->assertSee('id="webshop-menu"', false)
            ->assertSee('Accessoires')->assertDontSee('LED verlichtingsset');
        $this->get('/accessoires')->assertOk()->assertSee('LED verlichtingsset')
            ->assertDontSee('FietsFix Stad');

        foreach (config('winkel.accessoires') as $categorie) {
            $response = $this->get(route('winkel.accessoires', ['categorie' => $categorie]))->assertOk();
            $response->assertViewHas('producten', function ($producten) use ($categorie) {
                return $producten->count() > 0 && $producten->every(fn ($product) => $product->categorie === $categorie);
            });
        }

        foreach (Product::whereIn('categorie', config('winkel.accessoires'))->get() as $product) {
            $this->assertFileExists(public_path($product->afbeelding));
            $this->get(route('winkel.show', $product))->assertOk()
                ->assertSee($product->naam)->assertSee('Terug naar alle accessoires');
        }
    }

    public function test_filters_reject_unknown_categories_and_hide_inactive_items(): void
    {
        $this->getJson(route('winkel.accessoires', ['categorie' => 'Onbekend']))
            ->assertUnprocessable()->assertJsonValidationErrors('categorie');
        $this->getJson(route('winkel.index', ['categorie' => 'Sloten']))
            ->assertUnprocessable()->assertJsonValidationErrors('categorie');
        $product = Product::where('categorie', 'Sloten')->firstOrFail();
        $product->update(['actief' => false]);
        $this->get(route('winkel.accessoires', ['categorie' => 'Sloten']))
            ->assertOk()->assertSee('Er zijn op dit moment geen accessoires in deze categorie.');
        $this->post(route('winkelmand.toevoegen', $product))->assertNotFound();
    }

    public function test_bike_and_accessory_can_be_ordered_together_with_correct_total(): void
    {
        $fiets = Product::where('naam', 'FietsFix Stad')->firstOrFail();
        $lamp = Product::where('naam', 'LED verlichtingsset')->firstOrFail();

        // Ook zonder account kun je een accessoire samen met een fiets in het mandje doen.
        $this->post(route('winkelmand.toevoegen', $fiets))->assertRedirect(route('winkelmand.index'));
        $this->post(route('winkelmand.toevoegen', $lamp))->assertRedirect(route('winkelmand.index'));
        $this->patch(route('winkelmand.wijzigen', $lamp), ['aantal' => 2]);
        $totaal = $fiets->prijs_cent + 2 * $lamp->prijs_cent;
        $this->get(route('winkelmand.index'))->assertOk()->assertViewHas('totaal', $totaal);

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('afrekenen.create'))->assertOk()
            ->assertViewHas('totaal', $totaal)->assertSee('Jouw artikelen');
        $gegevens = [
            'naam' => 'Klant', 'email' => 'klant@example.com', 'telefoonnummer' => '0612345678',
            'vestiging' => 'Nijmegen', 'formuliernummer' => $response->viewData('formuliernummer'),
        ];
        $this->post(route('afrekenen.store'), $gegevens)->assertRedirect(route('bestellingen.index'))
            ->assertSessionMissing('winkelmand');
        $this->post(route('afrekenen.store'), $gegevens)->assertRedirect(route('bestellingen.index'));
        $this->assertDatabaseCount('bestellingen', 2);
        $this->assertDatabaseHas('bestellingen', [
            'product_id' => $lamp->id, 'aantal' => 2, 'prijs_cent' => $lamp->prijs_cent,
            'user_id' => $user->id, 'bestelnummer' => $gegevens['formuliernummer'],
        ]);
    }

    public function test_reseeding_does_not_duplicate_or_overwrite_accessories(): void
    {
        $product = Product::where('naam', 'Comfortzadel')->firstOrFail();
        $product->update(['prijs_cent' => 1234]);
        $this->seed(AccessoiresSeeder::class);
        $this->assertSame(8, Product::whereIn('categorie', config('winkel.accessoires'))->count());
        $this->assertSame(1234, $product->fresh()->prijs_cent);
    }
}
