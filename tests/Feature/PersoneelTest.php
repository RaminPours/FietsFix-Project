<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
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
}
