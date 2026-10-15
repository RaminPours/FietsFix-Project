<?php

namespace Tests\Feature;

use Tests\TestCase;

class NavigationTest extends TestCase
{
    public function test_navigation_links_point_to_existing_pages(): void
    {
        $this->get('/')
            ->assertSee('Merken', false)
            ->assertSee('Fietsonderhoud', false)
            ->assertSee('Contact', false);

        $this->get('/merken')->assertOk();
        $this->get('/fietsonderhoud')->assertOk();
        $this->get('/contact')->assertOk();
    }
}
