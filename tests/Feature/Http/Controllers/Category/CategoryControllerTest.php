<?php

namespace Tests\Feature\Http\Controllers\Category;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCategoryIndex()
    {
        $this->seed();
        $route = route('categories.index');
        $response = $this->get($route);

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }
}
