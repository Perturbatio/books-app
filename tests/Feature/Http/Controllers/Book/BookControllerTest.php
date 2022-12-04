<?php

namespace Tests\Feature\Http\Controllers\Book;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * @group Book
 *
 * @see \App\Http\Controllers\BookController
 */
class BookControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testThatBooksCanBeIndexed()
    {
        Book::factory(10)->create();

        $response = $this->get('/api/books');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(['data' => []])
            ->assertJsonCount(10, 'data');
    }
}
